<?php

namespace App\Core\Domain\Command\Profile;

use App\Core\Domain\Entity\Profile;
use App\CQRS\CommandInterface;

class SaveProfile implements CommandInterface
{
    public function __construct(
        public Profile $profile
    ) {
    }
}
