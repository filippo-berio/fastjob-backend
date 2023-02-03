<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Entity\Swipe;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskSwipe;
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
        $task14 = $this->getReference(TaskFixtures::TASK_14, Task::class);
        $task15 = $this->getReference(TaskFixtures::TASK_15, Task::class);
        $task16 = $this->getReference(TaskFixtures::TASK_16, Task::class);
        $task17 = $this->getReference(TaskFixtures::TASK_17, Task::class);
        $task18 = $this->getReference(TaskFixtures::TASK_18, Task::class);
        $task19 = $this->getReference(TaskFixtures::TASK_19, Task::class);
        $task20 = $this->getReference(TaskFixtures::TASK_20, Task::class);

        $profile2 = $this->getReference(ProfileFixtures::PROFILE_2, Profile::class);
        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);
        $profile7 = $this->getReference(ProfileFixtures::PROFILE_7, Profile::class);
        $profile8 = $this->getReference(ProfileFixtures::PROFILE_8, Profile::class);
        $profile10 = $this->getReference(ProfileFixtures::PROFILE_10, Profile::class);
        $profile12 = $this->getReference(ProfileFixtures::PROFILE_12, Profile::class);
        $profile13 = $this->getReference(ProfileFixtures::PROFILE_13, Profile::class);
        $profile14 = $this->getReference(ProfileFixtures::PROFILE_14, Profile::class);
        $profile15 = $this->getReference(ProfileFixtures::PROFILE_15, Profile::class);
        $profile16 = $this->getReference(ProfileFixtures::PROFILE_16, Profile::class);
        $profile17 = $this->getReference(ProfileFixtures::PROFILE_17, Profile::class);

        $this->save([
            new TaskSwipe($task9, $profile7, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task2, $profile7, Swipe::TYPE_REJECT),
            new TaskSwipe($task3, $profile8, Swipe::TYPE_REJECT),
            new TaskSwipe($task10, $profile2, Swipe::TYPE_REJECT),

            new TaskSwipe($task3, $profile4, Swipe::TYPE_ACCEPT, 300),
            new TaskSwipe($task7, $profile8, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task3, $profile10, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task3, $profile7, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task7, $profile10, Swipe::TYPE_ACCEPT),

            new TaskSwipe($task14, $profile12, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task14, $profile13, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task14, $profile14, Swipe::TYPE_ACCEPT, 2000),

            new TaskSwipe($task15, $profile13, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task15, $profile14, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task15, $profile15, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task15, $profile7, Swipe::TYPE_REJECT),

            new TaskSwipe($task16, $profile16, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task16, $profile17, Swipe::TYPE_ACCEPT),

            new TaskSwipe($task17, $profile16, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task18, $profile16, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task19, $profile16, Swipe::TYPE_ACCEPT),
            new TaskSwipe($task20, $profile16, Swipe::TYPE_ACCEPT),
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
