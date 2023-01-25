<?php

namespace App\Core\Domain\Command\Profile\Save;

use App\Core\Domain\Entity\Profile;
use App\CQRS\BaseCommand;

class SaveProfile extends BaseCommand
{
    public function __construct(
        public Profile $profile
    ) {
    }
}
