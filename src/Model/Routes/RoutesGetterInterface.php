<?php

namespace ukickeru\AccessControl\Model\Routes;

interface RoutesGetterInterface
{
    public function createRoutesCollection(): iterable;
}
