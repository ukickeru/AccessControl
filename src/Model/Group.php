<?php

namespace ukickeru\AccessControl\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use DomainException;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;

class Group
{

    private $id;

    private $name;

    private $creator;

    private $creationDate;

    private $parentGroup;

    private $availableRoutes;

    private $users;

    public function __construct(
        string $name,
        User $creator,
        self $parentGroup = null,
        array $availableRoutes = [],
        array $users = []
    )
    {
        $this->setName($name);
        $this->setCreator($creator);

        if (empty($availableRoutes)) {
            $this->availableRoutes = [];
        } else {
            $this->availableRoutes = $availableRoutes;
        }

        $users = array_unique($users);
        if (empty($users)) {
            $this->users = new ArrayCollection();
        } else {
            if (empty($this->users)) {
                $this->users = new ArrayCollection((array) $users);
            } else {
                $this->setUsers($users);
            }
        }

        $this->setParentGroup($parentGroup);

        $this->setCreationDate();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        if (mb_strlen($name) > 255) {
            throw new DomainException('Имя группы не должно быть длиннее 255 символов!');
        }

        $this->name = $name;

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    private function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate ? $this->creationDate->format('d.m.Y') : null;
    }

    /**
     * @return $this
     */
    public function setCreationDate(): self
    {
        if ($this->creationDate === null) {
            $this->creationDate = new DateTime();
        }

        return $this;
    }

    public function getParentGroup(): ?self
    {
        return $this->parentGroup;
    }

    public function setParentGroup(?self $group = null): self
    {
        if ($group === null) {
            $this->parentGroup = null;

            return $this;
        }

        if ($this->id === $group->id) {
            throw new DomainException('Нельзя назначить группу самой себе в качестве родительской!');
        }

        if (
            $group->getParentGroup() === null ||
            $group->getParentGroup() instanceof self && $group->getParentGroup()->isParentGroup($this) === false
        ) {
            $this->parentGroup = $group;
        }

        return $this;
    }

    public function isParentGroup(self $group)
    {
        if ($this->id === $group->id) {
            throw new DomainException('Нельзя назначить группу самой себе в качестве родительской!');
        }

        if ($this->getParentGroup() instanceof self) {
            return $this->getParentGroup()->isParentGroup($group);
        }

        return false;
    }

    /**
     * @return array|string[]
     */
    public function getAvailableRoutes(): array
    {
        if ($this->getParentGroup() instanceof self) {
            return array_unique(
                array_merge(
                    $this->availableRoutes,
                    $this->getParentGroup()->getAvailableRoutes()
                )
            );
        }

        return array_unique(
            array_merge($this->availableRoutes,ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES)
        );
    }

    public function addAvailableRoute(string $route): self
    {
        if (!in_array($route,$this->availableRoutes)) {
            $this->availableRoutes[] = $route;
        }

        return $this;
    }

    public function removeAvailableRoute(string $route): self
    {
        $elementPosition = array_search($route,$this->availableRoutes);

        if ($elementPosition !== false) {
            unset($this->availableRoutes[$elementPosition]);
        }

        return $this;
    }

    public function removeAllAvailableRoutes(): self
    {
        $this->availableRoutes = [];

        return $this;
    }

    public function setAvailableRoutes(array $availableRoutes): self
    {
        $this->removeAllAvailableRoutes();

        foreach ($availableRoutes as $availableRoute) {
            $this->addAvailableRoute($availableRoute);
        }

        return $this;
    }

    public function isRouteAvailable(string $route)
    {
        if (in_array($route,$this->getAvailableRoutes())) {
            return true;
        }

        if ($this->getParentGroup() instanceof self) {
            return $this->getParentGroup()->isRouteAvailable($route);
        }

        return false;
    }

    /**
     * @return array|User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeGroup($this);
        }

        return $this;
    }

    public function addUsers(array $users): self
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    public function removeAllUsers(): self
    {
        foreach ($this->users as $user) {
            $this->removeUser($user);
        }

        return $this;
    }

    public function setUsers(array $users): self
    {
        foreach ($this->users as $user) {
            if (!in_array($user,(array) $users)) {
                $this->removeUser($user);
            }
        }

        $this->addUsers($users);

        return $this;
    }

    public function __toString(): string
    {
        return $this->id.' \ '.$this->name;
    }

}
