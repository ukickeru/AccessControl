<?php

namespace ukickeru\AccessControl\UseCase;

use DomainException;
use ukickeru\AccessControl\Model\User;
use ukickeru\AccessControl\Model\UserInterface;

interface UserRepositoryInterface
{

    /**
     * @return array|UserInterface[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return UserInterface
     * @throws DomainException
     */
    public function getOneById(string $id): UserInterface;

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function save(UserInterface $user): UserInterface;

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function update(UserInterface $user): UserInterface;

    /**
     * @param string $id
     * @return bool
     * @throws DomainException
     */
    public function remove(string $id): bool;
}
