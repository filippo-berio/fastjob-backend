<?php

namespace App\Core\Command\Profile\Save;

use App\Core\Entity\Profile;
use App\CQRS\BaseCommand;

class SaveProfile extends BaseCommand
{
    public function __construct(
        public Profile $profile
    ) {
    }
}
