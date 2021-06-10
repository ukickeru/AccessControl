<?php

namespace ukickeru\AccessControl\Model\Service\CheckResourceAvailability;

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

}