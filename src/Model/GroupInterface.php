<?php

namespace ukickeru\AccessControl\Model;

use ukickeru\AccessControl\Model\Service\Collection\Collection;

interface GroupInterface
{
    public function getId();

    public function getName(): string;

    public function setName(string $name): GroupInterface;

    public function getCreator(): UserInterface;

    public function getCreationDate(): ?string;

    public function setCreationDate(): GroupInterface;

    public function getParentGroup(): ?GroupInterface;

    public function setParentGroup(?GroupInterface $group = null): GroupInterface;

    public function isParentGroup(GroupInterface $group);

    /**
     * @return Collection|string[]
     */
    public function getAvailableRoutes(): iterable;

    public function addAvailableRoute(string $route): GroupInterface;

    public function addAvailableRoutes(iterable $routes): GroupInterface;

    public function removeAvailableRoute(string $route): GroupInterface;

    public function removeAllAvailableRoutes(): GroupInterface;

    public function setAvailableRoutes(iterable $availableRoutes): GroupInterface;

    public function isRouteAvailable(string $route);

    /**
     * @return Collection|UserInterface[]
     */
    public function getUsers(): iterable;

    public function addUser(UserInterface $user): GroupInterface;

    public function addUsers(array $users): GroupInterface;

    public function removeUser(UserInterface $user): GroupInterface;

    public function removeAllUsers(): GroupInterface;

    public function setUsers(iterable $users): GroupInterface;
}