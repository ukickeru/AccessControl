<?php

namespace ukickeru\AccessControl\Model\Fixtures;

use ukickeru\AccessControl\Model\GroupInterface;
use ukickeru\AccessControl\Model\User;

class FixturesDTO
{
    private $user;

    private $userGroup;

    private $admin;

    private $adminGroup;

    public function __construct(
        User $user,
        GroupInterface $userGroup,
        User $admin,
        GroupInterface $adminGroup
    )
    {
        $this->user = $user;
        $this->userGroup = $userGroup;
        $this->admin = $admin;
        $this->adminGroup = $adminGroup;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserGroup(): GroupInterface
    {
        return $this->userGroup;
    }

    public function getAdmin(): User
    {
        return $this->admin;
    }

    public function getAdminGroup(): GroupInterface
    {
        return $this->adminGroup;
    }

}