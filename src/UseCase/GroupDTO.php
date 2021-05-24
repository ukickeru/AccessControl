<?php

namespace ukickeru\AccessControl\UseCase;

use ukickeru\AccessControl\Model\Group;
use ukickeru\AccessControl\Model\User;
use Symfony\Component\Validator\Constraints as Assert;

final class GroupDTO
{
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @Assert\Type(User::class)
     */
    public $creator;

    public $creationDate;

    /**
     * @Assert\Type(Group::class)
     */
    public $parentGroup;

    public $availableRoutes = [];

    public $users = [];

    public static function createFromGroup(Group $group) {
        return (new self())
            ->setId($group->getId())
            ->setName($group->getName())
            ->setCreator($group->getCreator())
            ->setCreationDate($group->getCreationDate())
            ->setParentGroup($group->getParentGroup())
            ->setAvailableRoutes($group->getAvailableRoutes())
            ->setUsers($group->getUsers())
        ;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getParentGroup(): ?Group
    {
        return $this->parentGroup;
    }

    public function setParentGroup(?Group $parentGroup): self
    {
        $this->parentGroup = $parentGroup;

        return $this;
    }

    public function getAvailableRoutes(): array
    {
        if (is_null($this->availableRoutes)) $this->availableRoutes = [];

        return $this->availableRoutes;
    }

    public function setAvailableRoutes(array $availableRoutes = []): self
    {
        $this->availableRoutes = $availableRoutes;

        return $this;
    }

    public function getUsers(): array
    {
        if (is_null($this->users)) $this->users = [];

        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;

        return $this;
    }

}