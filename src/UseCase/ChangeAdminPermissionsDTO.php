<?php

namespace ukickeru\AccessControl\UseCase;

use Symfony\Component\Validator\Constraints as Assert;


final class ChangeAdminPermissionsDTO
{

    /**
     * @Assert\Type(UserDTO::class)
     */
    public $newAdmin = null;

    /**
     * @Assert\IsTrue()
     */
    public $confirmed = false;

    public static function new(
        UserDTO $userDTO,
        bool $confirmed
    )
    {
        $DTO = new self();

        $DTO->newAdmin = $userDTO;
        $DTO->confirmed = $confirmed;

        return $DTO;
    }

    public function getNewAdmin(): ?UserDTO
    {
        return $this->newAdmin;
    }

    public function setNewAdmin($newAdmin): self
    {
        $this->newAdmin = $newAdmin;

        return $this;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }
}