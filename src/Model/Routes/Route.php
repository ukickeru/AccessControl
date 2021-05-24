<?php

namespace ukickeru\AccessControl\Model\Routes;

use Psr\Container\ContainerInterface;
use DomainException;

class Route
{
    protected $name;

    protected $path;

    protected $controllerClass;

    protected $controllerMethod;

    protected $methods;

    public function __construct(
        ?string $name,
        string $path,
        ?string $controllerClass,
        ?string $controllerMethod,
        ?array $methods
    ) {
        if ($path === '' || $path === null) {
            throw new DomainException('Маршрут контроллера не может быть пустым! Имя маршрута: '.$name.', класс контроллера: '.$controllerClass.', метод контроллера: '.$controllerMethod.'.');
        }

        $this->name = $name;
        $this->path = $path;
        $this->controllerClass = $controllerClass;
        $this->controllerMethod = $controllerMethod;
        $this->methods = $methods;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function getPath(): string
    {
        return $this->path;
    }
    
    public function getControllerClass(): ?string
    {
        return $this->controllerClass;
    }
    
    public function getControllerMethod(): ?string
    {
        return $this->controllerMethod;
    }
    
    public function getMethods(): ?array
    {
        return $this->methods;
    }
}
