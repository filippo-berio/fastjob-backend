<?php

namespace App\Core\Data\Command\RefreshToken;

use App\Core\Entity\RefreshToken;
use App\CQRS\BaseCommand;

class SaveRefreshToken extends BaseCommand
{
    public function __construct(
        public RefreshToken $refreshToken
    ) {
    }
}
