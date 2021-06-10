<?php

namespace ukickeru\AccessControl\Model\Service;

use ukickeru\AccessControl\Model\UserInterface;

interface SecurityInterface
{

    public function getUser(): ?UserInterface;

}