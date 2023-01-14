<?php

namespace App\Core\Data\Command\Profile\Save;

use App\Core\Entity\Profile;
use App\CQRS\BaseCommand;

class SaveProfile extends BaseCommand
{
    public function __construct(
        public Profile $profile
    ) {
    }
}
