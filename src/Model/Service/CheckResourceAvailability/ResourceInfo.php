<?php

namespace ukickeru\AccessControl\Model\Service\CheckResourceAvailability;

use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;

class ResourceInfo
{

    private $routePath;

    private $routeName;

    public function __construct(
        string $routePath,
        ?string $routeName
    )
    {
        if ($routePath === null) {
            throw new \DomainException('Невозможно определить маршрут ресурса, доступ к которому необходимо проверить!');
        }

        $this->routePath = $routePath;
        $this->routeName = $routeName;
    }

    public function getRoutePath(): ?string
    {
        return $this->routePath;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function isApiLoginPage(): bool
    {
        return $this->routePath === ApplicationRoutesContainer::API_LOGIN_ROUTE_PATH;
    }

}