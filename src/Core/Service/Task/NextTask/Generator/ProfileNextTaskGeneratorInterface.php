<?php

namespace App\Core\Service\Task\NextTask\Generator;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;

interface ProfileNextTaskGeneratorInterface
{
    /**
     * @return Task[]
     */
    public function generateForProfile(Profile $profile, int $count): array;
}
