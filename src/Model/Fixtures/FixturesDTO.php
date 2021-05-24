<?php

namespace ukickeru\AccessControl\Model\Fixtures;

use ukickeru\AccessControl\Model\Group;
use ukickeru\AccessControl\Model\User;

class FixturesDTO
{
    private $user;

    private $userGroup;

    private $admin;

    private $adminGroup;

    public function __construct(
        User $user,
        Group $userGroup,
        User $admin,
        Group $adminGroup
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

    public function getUserGroup(): Group
    {
        return $this->userGroup;
    }

    public function getAdmin(): User
    {
        return $this->admin;
    }

    public function getAdminGroup(): Group
    {
        return $this->adminGroup;
    }

}