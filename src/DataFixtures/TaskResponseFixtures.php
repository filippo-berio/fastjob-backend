<?php

namespace App\DataFixtures;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskResponse;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskResponseFixtures extends BaseFixtures implements DependentFixtureInterface
{

    const RESPONSE_2 = 2;

    protected function getEntity(): string
    {
        return TaskResponse::class;
    }

    public function load(ObjectManager $manager)
    {
        $task1 = $this->getReference(TaskFixtures::TASK_1, Task::class);
        $task2 = $this->getReference(TaskFixtures::TASK_2, Task::class);

        $profile2 = $this->getReference(ProfileFixtures::PROFILE_2, Profile::class);
        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);

        $this->save([
            new TaskResponse($task1, $profile2, true),
            $response2 = new TaskResponse($task1, $profile4, true, 1500),
            new TaskResponse($task2, $profile4, false),
        ], $manager);

        $this->addReference(self::RESPONSE_2, $response2);
    }

    public function getDependencies()
    {
        return [
            TaskFixtures::class,
            ProfileFixtures::class
        ];
    }
}
