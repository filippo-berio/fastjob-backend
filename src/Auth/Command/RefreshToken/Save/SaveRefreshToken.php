<?php

namespace App\Auth\Command\RefreshToken\Save;

use App\Auth\Entity\RefreshToken;
use App\CQRS\CommandInterface;

class SaveRefreshToken implements CommandInterface
{
    public function __construct(
        public RefreshToken $refreshToken
    ) {
    }
}
