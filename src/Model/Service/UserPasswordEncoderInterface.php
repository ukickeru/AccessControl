<?php

namespace ukickeru\AccessControl\Model\Service;

use ukickeru\AccessControl\Model\UserInterface;

interface UserPasswordEncoderInterface
{

    public function encodePassword(UserInterface $user, string $password): string;

}