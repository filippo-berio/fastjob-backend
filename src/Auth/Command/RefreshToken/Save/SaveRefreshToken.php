<?php

namespace App\Auth\Command\RefreshToken\Save;

use App\Auth\Entity\RefreshToken;
use App\CQRS\BaseCommand;

class SaveRefreshToken extends BaseCommand
{
    public function __construct(
        public RefreshToken $refreshToken
    ) {
    }
}
