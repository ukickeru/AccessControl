<?php

namespace ukickeru\AccessControl\Model;

use ukickeru\AccessControl\Model\Service\Collection\Collection;

interface UserInterface
{
    public function getId();

    public function getUsername(): string;

    public function setUsername(string $username): UserInterface;

    public function getPassword(): string;

    public function setPassword(string $password): UserInterface;

    public function updatePassword(string $password): UserInterface;

    /**
     * @return Collection|string[]
     */
    public function getRoles(): iterable;

    public function addRole(string $role): UserInterface;

    public function addRoles(iterable $roles): UserInterface;

    public function removeRole(string $role): UserInterface;

    public function removeAllRoles(): UserInterface;

    public function setRoles(iterable $roles): UserInterface;

    /**
     * @return Collection|GroupInterface[]
     */
    public function getGroups(): iterable;

    public function addGroup(GroupInterface $group): UserInterface;

    public function addGroups(iterable $groups): UserInterface;

    public function removeGroup(GroupInterface $group): UserInterface;

    public function removeAllGroups(): UserInterface;

    public function setGroups(iterable $groups): UserInterface;

    /**
     * @return array|string[]
     */
    public function getAvailableRoutes(): array;

    public function isRouteAvailable(string $route): bool;

    public function isAdmin(): bool;

    public function setAdmin(bool $admin): UserInterface;
}