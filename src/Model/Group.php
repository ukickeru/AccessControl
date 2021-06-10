<?php

namespace ukickeru\AccessControl\Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControl\Model\Service\Collection\ArrayCollection;
use ukickeru\AccessControl\Model\Service\Collection\Collection;
use ukickeru\AccessControl\Model\Service\IdGenerator;

class Group implements GroupInterface
{

    protected $id;

    protected $name;

    protected $creator;

    protected $creationDate;

    /**
     * @ORM\OneToOne(targetEntity=self::class)
     * @
     */
    protected $parentGroup = null;

    protected $availableRoutes;

    protected $users;

    public function __construct(
        string $name,
        UserInterface $creator,
        GroupInterface $parentGroup = null,
        iterable $availableRoutes = [],
        iterable $users = []
    )
    {
        $this->id = IdGenerator::generate();
        $this->setName($name);
        $this->setCreator($creator);
        $this->availableRoutes = new ArrayCollection();
        $this->setAvailableRoutes($availableRoutes);
        $this->users = new ArrayCollection();
        $this->setUsers($users);
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

    public function setName(string $name): GroupInterface
    {
        if (mb_strlen($name) > 255) {
            throw new DomainException('Имя группы не должно быть длиннее 255 символов!');
        }

        $this->name = $name;

        return $this;
    }

    public function getCreator(): UserInterface
    {
        return $this->creator;
    }

    protected function setCreator(UserInterface $creator): GroupInterface
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
    public function setCreationDate(): GroupInterface
    {
        if ($this->creationDate === null) {
            $this->creationDate = new DateTime();
        }

        return $this;
    }

    public function getParentGroup(): ?GroupInterface
    {
        return $this->parentGroup;
    }

    public function setParentGroup(?GroupInterface $group = null): GroupInterface
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

    public function isParentGroup(GroupInterface $group)
    {
        if ($this->id === $group->id) {
            throw new DomainException('Нельзя назначить группу самой себе в качестве родительской!');
        }

        if ($this->getParentGroup() instanceof self) {
            return $this->getParentGroup()->isParentGroup($group);
        }

        return false;
    }

    public function getAvailableRoutesAsArray(): array
    {
        if (is_array($this->availableRoutes)) {
            return $this->availableRoutes;
        }

        return $this->availableRoutes->toArray();
    }

    /**
     * @return string[]
     */
    public function getAvailableRoutes(): iterable
    {
        if ($this->getParentGroup() instanceof self) {
            return array_unique(
                array_merge(
                    $this->getAvailableRoutesAsArray(),
                    $this->getParentGroup()->getAvailableRoutes()
                )
            );
        }

        return array_unique(
            array_merge($this->getAvailableRoutesAsArray(),ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES)
        );
    }

    public function addAvailableRoute(string $route): GroupInterface
    {
        if (!$this->availableRoutes->contains($route)) {
            $this->availableRoutes[] = $route;
        }

        return $this;
    }

    public function addAvailableRoutes(iterable $routes): GroupInterface
    {
        foreach ($routes as $route) {
            $this->addAvailableRoute($route);
        }

        return $this;
    }

    public function removeAvailableRoute(string $route): GroupInterface
    {
        if ($this->availableRoutes->contains($route)) {
            $this->availableRoutes->removeElement($route);
        }

        return $this;
    }

    public function removeAllAvailableRoutes(): GroupInterface
    {
        $this->availableRoutes->clear();

        return $this;
    }

    public function setAvailableRoutes(iterable $availableRoutes): GroupInterface
    {
        $this->removeAllAvailableRoutes();
        $this->addAvailableRoutes($availableRoutes);

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
     * @return Collection|UserInterface[]
     */
    public function getUsers(): iterable
    {
        return $this->users->toArray();
    }

    public function addUser(UserInterface $user): GroupInterface
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addGroup($this);
        }

        return $this;
    }

    public function addUsers(array $users): GroupInterface
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }

    public function removeUser(UserInterface $user): GroupInterface
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeGroup($this);
        }

        return $this;
    }

    public function removeAllUsers(): GroupInterface
    {
        foreach ($this->users as $user) {
            $this->removeUser($user);
        }

        return $this;
    }

    public function setUsers(iterable $users): GroupInterface
    {
        $this->addUsers($users);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

}
