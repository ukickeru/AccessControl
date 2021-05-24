<?php

namespace ukickeru\AccessControl\UseCase;

use DomainException;
use ukickeru\AccessControl\Model\User;

interface UserRepositoryInterface
{

    /**
     * @return array|User[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return User
     * @throws DomainException
     */
    public function getOneById(string $id): User;

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User;

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user): User;

    /**
     * @param string $id
     * @return bool
     * @throws DomainException
     */
    public function remove(string $id): bool;
}
