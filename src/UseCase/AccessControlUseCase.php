<?php

namespace ukickeru\AccessControl\UseCase;

use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use ukickeru\AccessControl\Model\Service\UserPasswordEncoderInterface;
use ukickeru\AccessControl\Model\Service\SecurityInterface;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

final class AccessControlUseCase
{

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var SecurityInterface */
    private $security;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * @todo Эта зависимость здесь не нужна, удалить
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        SecurityInterface $security,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array|GroupDTO[]
     */
    public function getAllGroups(): iterable
    {
        $groupDTOs = [];

        $groups = $this->groupRepository->getAll();

        foreach ($groups as $group) {
            $groupDTOs[] = GroupDTO::createFromGroup($group);
        }

        return $groupDTOs;
    }

    /**
     * @param string $id
     * @return GroupDTO
     */
    public function getGroup(string $id)
    {
        return GroupDTO::createFromGroup($this->groupRepository->getOneById($id));
    }

    /**
     * @param GroupDTO $groupDTO
     * @return GroupDTO
     */
    public function createGroup(GroupDTO $groupDTO): GroupDTO
    {
        $creator = $this->security->getUser();

        if (!is_null($groupDTO->getParentGroup())) {
            $parentGroup = $this->groupRepository->getOneById($groupDTO->getParentGroup()->getId());
        } else {
            $parentGroup = null;
        }

        $group = new Group(
            $groupDTO->getName(),
            $creator,
            $parentGroup,
            $groupDTO->getAvailableRoutes()
        );

        /** @var UserDTO $user */
        foreach ($groupDTO->getUsers() as $user) {
            $user = $this->userRepository->getOneById($user->getId());
            $group->addUser($user);
        }

        $this->groupRepository->save($group);

        return $groupDTO;
    }

    /**
     * @param GroupDTO $groupDTO
     * @return GroupDTO
     */
    public function editGroup(GroupDTO $groupDTO): GroupDTO
    {
        $group = $this->groupRepository->getOneById($groupDTO->id);

        if (!is_null($groupDTO->getParentGroup())) {
            $parentGroup = $this->groupRepository->getOneById($groupDTO->getParentGroup()->getId());
        } else {
            $parentGroup = null;
        }

        $group
            ->setName($groupDTO->getName())
            ->setParentGroup($parentGroup)
            ->setAvailableRoutes($groupDTO->getAvailableRoutes())
        ;

        $group->removeAllUsers();
        /** @var UserDTO $user */
        foreach ($groupDTO->getUsers() as $user) {
            $user = $this->userRepository->getOneById($user->getId());
            $group->addUser($user);
        }

        $this->groupRepository->update($group);

        return $groupDTO;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function removeGroup(string $id): bool
    {
        return $this->groupRepository->remove($id);
    }

    /**
     * @return array|UserDTO[]
     */
    public function getAllUsers(): iterable
    {
        $userDTOs = [];

        $users = $this->userRepository->getAll();

        foreach ($users as $user) {
            $userDTOs[] = UserDTO::createFromUser($user);
        }

        return $userDTOs;
    }

    /**
     * @param string $id
     * @return UserDTO
     */
    public function getUser(string $id): UserDTO
    {
        return UserDTO::createFromUser($this->userRepository->getOneById($id));
    }

    /**
     * @param UserDTO $userDTO
     * @return UserDTO
     */
    public function createUser(UserDTO $userDTO): UserDTO
    {
        $user = new User(
            $userDTO->username,
            $userDTO->password,
            $userDTO->roles
        );

        /** @var GroupDTO $groupDTO */
        foreach ($userDTO->getGroups() as $groupDTO) {
            $group = $this->groupRepository->getOneById($groupDTO->getId());
            $user->addGroup($group);
        }

        $user->updatePassword($this->passwordEncoder->encodePassword($user,$userDTO->password));

        $this->userRepository->save($user);

        return $userDTO;
    }

    /**
     * @param UserDTO $userDTO
     * @return UserDTO
     */
    public function editUser(UserDTO $userDTO): UserDTO
    {
        $user = $this->userRepository->getOneById($userDTO->id);

        $user
            ->setUsername($userDTO->username)
            ->updatePassword($this->passwordEncoder->encodePassword($user,$userDTO->password))
            ->setRoles($userDTO->roles)
            ->removeAllGroups()
        ;

        /** @var GroupDTO $groupDTO */
        foreach ($userDTO->getGroups() as $groupDTO) {
            $group = $this->groupRepository->getOneById($groupDTO->getId());
            $user->addGroup($group);
        }

        $this->userRepository->save($user);

        return $userDTO;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function removeUser(string $id): bool
    {
        return $this->userRepository->remove($id);
    }

    /**
     * @param ChangeAdminPermissionsDTO $changeAdminPermissionsDTO
     * @return bool
     */
    public function turnAdministrativePermissionsToAnotherUser(ChangeAdminPermissionsDTO $changeAdminPermissionsDTO): bool
    {
        $currentUser = $this->security->getUser();
        $newAdmin = $changeAdminPermissionsDTO->getNewAdmin();

        if ($currentUser->isAdmin() === false) {
            throw new DomainException('У вас нет административных прав!');
        }

        if (!$newAdmin instanceof UserDTO) {
            throw new DomainException('Должен быть определён новый администратор!');
        }

        if ($changeAdminPermissionsDTO->isConfirmed() === false) {
            throw new DomainException('Должно быть получено подтверждение на передачу административных прав!');
        }

        if ($currentUser->getId() === $newAdmin->getId()) {
            return true;
        }

        $user = $this->userRepository->getOneById($newAdmin->getId());

        /*
         * todo Обеспечить атомарность операции
         */
        $user->setAdmin(true);
        $this->userRepository->save($user);

        $currentUser->setAdmin(false);
        $this->userRepository->save($currentUser);

        return true;
    }
}
