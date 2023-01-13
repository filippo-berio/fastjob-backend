<?php

namespace App\Tests\Application\Core\Controller\SwipeController;

use App\Core\Entity\Swipe;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Application\ApplicationTest;

class CreateExecutorSwipeControllerTest extends ApplicationTest
{
    public function testNotAuthorized()
    {
        $client = static::createClient();
        $this->notAuthorizedTest($client, 'POST', '/api/swipe/executor', [
            'taskId' => TaskFixtures::TASK_1,
            'executorId' => ProfileFixtures::PROFILE_4,
            'type' => Swipe::TYPE_ACCEPT,
        ]);
    }
}
