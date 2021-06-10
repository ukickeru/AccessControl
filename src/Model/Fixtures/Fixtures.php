<?php

namespace ukickeru\AccessControl\Model\Fixtures;

use ukickeru\AccessControl\Model\GroupInterface;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControl\Model\User;

class Fixtures
{

    public function getDataFixturesToPersist(): FixturesDTO
    {
        $user = new User(
            'user',
            'user123456',
            ['user']
        );

        $userGroup = new GroupInterface(
            'Пользователи',
            $user,
            null,
            ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES
        );
        $userGroup->addUser($user);

        $admin = new User(
            'admin',
            'admin123456',
            ['user', 'admin']
        );
        $admin->setAdmin(true);

        $adminGroup = new GroupInterface(
            'Администраторы',
            $admin,
            $userGroup,
            ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES_FOR_ADMIN
        );
        $adminGroup->addUser($admin);
        $adminGroup->setParentGroup($userGroup);

        return new FixturesDTO(
            $user,
            $userGroup,
            $admin,
            $adminGroup
        );
    }

}