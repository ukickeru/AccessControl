<?php

namespace ukickeru\AccessControl\UseCase;

use ukickeru\AccessControl\Model\GroupInterface;
use ukickeru\AccessControl\Model\Service\Collection\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ukickeru\AccessControl\Model\UserInterface;

final class GroupDTO
{
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    public $creator;

    public $creationDate;

    public $parentGroup;

    /**
     * @var ArrayCollection|string[]
     */
    public $availableRoutes = [];

    /**
     * @var ArrayCollection|UserInterface[]
     */
    public $users = [];

    public static function createFromGroup(GroupInterface $group) {
        $groupDTO = (new self())
            ->setId($group->getId())
            ->setName($group->getName())
            ->setCreator($group->getCreator())
            ->setCreationDate($group->getCreationDate())
            ->setAvailableRoutes($group->getAvailableRoutes())
            ->setUsers($group->getUsers())
        ;

        if ($group->getParentGroup() instanceof GroupInterface) {
            $groupDTO->setParentGroup(self::createFromGroup($group->getParentGroup()));
        }

        return $groupDTO;
    }

    public function __construct()
    {
        $this->availableRoutes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(UserInterface $creator): self
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

    public function getParentGroup(): ?self
    {
        return $this->parentGroup;
    }

    public function setParentGroup(?self $parentGroup): self
    {
        $this->parentGroup = $parentGroup;

        return $this;
    }

    public function getAvailableRoutes(): iterable
    {
        if ($this->availableRoutes instanceof ArrayCollection === false) {
            $this->availableRoutes = new ArrayCollection();
        }

        return $this->availableRoutes->toArray();
    }

    public function removeAllAvailableRoutes(): self
    {
        if ($this->availableRoutes instanceof ArrayCollection) {
            $this->availableRoutes->clear();
        } else {
            $this->availableRoutes = new ArrayCollection();
        }

        return $this;
    }

    public function addAvailableRoutes(iterable $availableRoutes): self
    {
        foreach ($availableRoutes as $availableRoute) {
            $this->availableRoutes->add($availableRoute);
        }

        return $this;
    }

    public function setAvailableRoutes(?iterable $availableRoutes): self
    {
        $this->removeAllAvailableRoutes();
        $this->addAvailableRoutes($availableRoutes);

        return $this;
    }

    public function getUsers(): iterable
    {
        if (is_null($this->users)) $this->users = [];

        return $this->users;
    }

    public function setUsers(iterable $users): self
    {
        if (!is_null($users)) {
            foreach ($users as $user) {
                $this->users->add($user);
            }
        }

        return $this;
    }

}