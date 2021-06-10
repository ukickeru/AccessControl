<?php

namespace ukickeru\AccessControl\UseCase;

use ukickeru\AccessControl\Model\GroupInterface;
use ukickeru\AccessControl\Model\Service\Collection\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ukickeru\AccessControl\Model\UserInterface;

final class UserDTO
{
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /** @var ArrayCollection|string[] */
    public $roles = [];

    /** @var ArrayCollection|GroupInterface[] */
    public $groups = [];

    /** @var array|string[] */
    public $availableRoutes = [];

    /** @var boolean */
    public $admin;

    public static function createFromUser(UserInterface $user) {
        return (new self())
            ->setId($user->getId())
            ->setUsername($user->getUsername())
            ->setPassword($user->getPassword())
            ->setRoles($user->getRoles())
            ->setGroups($user->getGroups())
            ->setAvailableRoutes($user->getAvailableRoutes())
            ->setAdmin($user->isAdmin())
        ;
    }

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): iterable
    {
        return $this->roles;
    }

    public function setRoles(?iterable $roles): self
    {
        if (!is_null($roles)) {
            foreach ($roles as $role) {
                $this->roles->add($role);
            }
        }

        return $this;
    }

    public function getGroups(): iterable
    {
        return $this->groups;
    }

    public function setGroups(?iterable $groups): self
    {
        if (!is_null($groups)) {
            foreach ($groups as $group) {
                $this->groups->add($group);
            }
        }

        return $this;
    }

    public function getAvailableRoutes(): iterable
    {
        return $this->availableRoutes;
    }

    public function setAvailableRoutes(?iterable $availableRoutes): self
    {
        if ($availableRoutes === null) {
            $this->availableRoutes = [];
        } else {
            $this->availableRoutes = $availableRoutes;
        }

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

}