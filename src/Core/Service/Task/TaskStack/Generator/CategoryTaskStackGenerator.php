<?php

namespace App\Core\Service\Task\TaskStack\Generator;

use App\Core\Entity\Profile;

class CategoryTaskStackGenerator implements TaskStackGeneratorInterface
{
    public function type(): string
    {
        return self::TYPE_CATEGORY;
    }

    public function generateForProfile(Profile $profile): array
    {
    }
}
