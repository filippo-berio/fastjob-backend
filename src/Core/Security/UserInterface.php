<?php

namespace App\Core\Security;

use App\Core\Entity\Profile;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    public function getProfile(): Profile;

    public function getAccessToken(): string;

    public function getRefreshToken(): string;
}
