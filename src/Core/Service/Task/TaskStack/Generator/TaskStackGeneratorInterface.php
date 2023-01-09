<?php

namespace App\Core\Service\Task\TaskStack\Generator;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;

interface TaskStackGeneratorInterface
{
    const TYPE_RECOMMENDED = 'recommended';
    const TYPE_CATEGORY = 'category';

    public function type(): string;

    /**
     * @return Task[]
     */
    public function generateForProfile(Profile $profile): array;
}
