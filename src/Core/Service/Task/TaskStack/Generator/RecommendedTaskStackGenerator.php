<?php

namespace App\Core\Service\Task\TaskStack\Generator;

use App\Core\Entity\Profile;

class RecommendedTaskStackGenerator implements TaskStackGeneratorInterface
{
    public function type(): string
    {
        return self::TYPE_RECOMMENDED;
    }

    public function generateForProfile(Profile $profile): array
    {
    }
}
