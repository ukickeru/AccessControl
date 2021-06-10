<?php

namespace ukickeru\AccessControl\UseCase;

use DomainException;
use ukickeru\AccessControl\Model\GroupInterface;

interface GroupRepositoryInterface
{
    /**
     * @return array|GroupInterface[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return GroupInterface
     * @throws DomainException
     */
    public function getOneById(string $id): GroupInterface;

    /**
     * @param GroupInterface $group
     * @return GroupInterface
     */
    public function save(GroupInterface $group): GroupInterface;

    /**
     * @param GroupInterface $group
     * @return GroupInterface
     */
    public function update(GroupInterface $group): GroupInterface;

    /**
     * @param string $id
     * @return bool
     * @throws DomainException
     */
    public function remove(string $id): bool;
}
