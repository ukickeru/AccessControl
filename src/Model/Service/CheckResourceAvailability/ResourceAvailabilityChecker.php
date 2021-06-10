<?php

namespace ukickeru\AccessControl\Model\Service\CheckResourceAvailability;

use ukickeru\AccessControl\Model\UserInterface;

class ResourceAvailabilityChecker
{

    public function checkResourceAvailableForUser(
        UserInterface $user,
        ResourceInfo $resourceInfo
    ): bool
    {
        $resourceRoutePath = $resourceInfo->getRoutePath();
        $resourceRouteName = $resourceInfo->getRouteName();

        if (
            $user instanceof UserInterface &&
            !$user->isAdmin() &&
            !(
                !is_null($resourceRoutePath) && ( is_string($resourceRoutePath) && $user->isRouteAvailable($resourceRoutePath) ) ||
                !is_null($resourceRouteName) && ( is_string($resourceRouteName) && $user->isRouteAvailable($resourceRouteName) )
            )
        ) {
            return false;
        }

        return true;
    }

}