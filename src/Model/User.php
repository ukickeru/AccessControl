<?php

namespace ukickeru\AccessControl\Model;

use DomainException;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControl\Model\Service\Collection\ArrayCollection;
use ukickeru\AccessControl\Model\Service\Collection\Collection;
use ukickeru\AccessControl\Model\Service\IdGenerator;

class User implements UserInterface
{
    public const PASSWORD_MIN_LENGTH = 8;

    public const DEFAULT_ROLE = 'ROLE_USER';

    protected $id;

    protected $username;

    protected $password;

    protected $roles;

    protected $groups;

    protected $admin = false;

    public function __construct(
        string $username,
        string $password,
        iterable $roles = [],
        iterable $groups = []
    )
    {
        $this->id = IdGenerator::generate();
        $this->setUsername($username);
        $this->setPassword($password);
        $this->roles = new ArrayCollection();
        $this->setRoles($roles);
        $this->groups = new ArrayCollection();
        $this->setGroups($groups);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserInterface
    {
        $username = trim($username);

        if ($username === '') {
            throw new DomainException('Имя пользователя не может быть пустой строкой!');
        }

        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): UserInterface
    {
        return $this->updatePassword($password);
    }

    public function updatePassword(string $password): UserInterface
    {
        $password = trim($password);

        if ($password === '') {
            throw new DomainException('Пароль пользователя не может быть пустой строкой!');
        }

        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            throw new DomainException('Пароль пользователя не может быть короче '.self::PASSWORD_MIN_LENGTH.' символов!');
        }

        $this->password = $password;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRoles(): iterable
    {
        $roles = $this->roles->toArray();
        // guarantee every user at least has ROLE_USER
        $roles[] = self::DEFAULT_ROLE;

        return array_unique($roles);
    }

    public function addRole(string $role): UserInterface
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function addRoles(iterable $roles): UserInterface
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function removeRole(string $role): UserInterface
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function removeAllRoles(): UserInterface
    {
        $this->roles->clear();

        return $this;
    }

    public function setRoles(iterable $roles): UserInterface
    {
        $this->removeAllRoles();
        $this->addRoles($roles);

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): iterable
    {
        return $this->groups;
    }

    public function addGroup(GroupInterface $group): UserInterface
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function addGroups(iterable $groups): UserInterface
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    public function removeGroup(GroupInterface $group): UserInterface
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeUser($this);
        }

        return $this;
    }

    public function removeAllGroups(): UserInterface
    {
        foreach ($this->groups as $group) {
            $this->removeGroup($group);
        }

        return $this;
    }

    public function setGroups(iterable $groups): UserInterface
    {
        $this->removeAllGroups();
        $this->addGroups($groups);

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getAvailableRoutes(): array
    {
        $availableRoutes = ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES;

        foreach ($this->getGroups() as $group) {
            $availableRoutes = array_merge(
                $availableRoutes,
                $group->getAvailableRoutes()
            );
        }

        if ($this->isAdmin()) {
            $availableRoutes = array_merge(
                $availableRoutes,
                ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES_FOR_ADMIN
            );
        } else {
            $availableRoutesKeysToUnset = array_keys(
                $availableRoutes,
                ApplicationRoutesContainer::CHANGE_ADMIN_PATH
            );

            foreach ($availableRoutesKeysToUnset as $keyToUnset) {
                unset($availableRoutes[$keyToUnset]);
            }
        }

        return array_unique($availableRoutes);
    }

    public function isRouteAvailable(string $route): bool
    {
        $route = $this->trimLastSlash($route);

        return in_array(
            $route,
            $this->getAvailableRoutes()
        );
    }

    private function trimLastSlash(string $route): string
    {
        if (strlen($route) > 1 && mb_substr($route,strlen($route) - 1,1) === '/') {
            $route = mb_substr($route,0,strlen($route) - 1);
        }

        return $route;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): UserInterface
    {
        $this->admin = $admin;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
