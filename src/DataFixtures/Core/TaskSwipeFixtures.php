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
        $task2 = $this->getReference(TaskFixtures::TASK_2, Task::class);
        $task3 = $this->getReference(TaskFixtures::TASK_3, Task::class);
        $task7 = $this->getReference(TaskFixtures::TASK_7, Task::class);
        $task9 = $this->getReference(TaskFixtures::TASK_9, Task::class);
        $task10 = $this->getReference(TaskFixtures::TASK_8, Task::class);

        $profile2 = $this->getReference(ProfileFixtures::PROFILE_2, Profile::class);
        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);
        $profile7 = $this->getReference(ProfileFixtures::PROFILE_7, Profile::class);
        $profile8 = $this->getReference(ProfileFixtures::PROFILE_8, Profile::class);
        $profile10 = $this->getReference(ProfileFixtures::PROFILE_10, Profile::class);

        $this->save([
            new TaskSwipe($task9, $profile7, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task2, $profile7, Swipe::TYPE_REJECT),
            new TaskSwipe($task3, $profile8, Swipe::TYPE_REJECT),
            new TaskSwipe($task10, $profile2, Swipe::TYPE_REJECT),

            new TaskSwipe($task3, $profile4, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task7, $profile8, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task3, $profile10, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task3, $profile7, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task7, $profile10, Swipe::TYPE_ACCEPT),
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
