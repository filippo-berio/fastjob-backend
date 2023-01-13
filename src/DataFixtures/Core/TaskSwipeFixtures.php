<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskSwipeFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return TaskSwipe::class;
    }

    public function load(ObjectManager $manager)
    {
        $task3 = $this->getReference(TaskFixtures::TASK_3, Task::class);
        $profile2 = $this->getReference(ProfileFixtures::PROFILE_2, Profile::class);

        $this->save([
            new TaskSwipe($task3, $profile2, Swipe::TYPE_ACCEPT),
        ], $manager);
    }

    public function getDependencies()
    {
        return [
            TaskFixtures::class,
            ProfileFixtures::class,
        ];
    }
}
