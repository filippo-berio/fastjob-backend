<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\ExecutorSwipe;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Entity\Swipe;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExecutorSwipeFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return ExecutorSwipe::class;
    }

    public function getDependencies()
    {
        return [
            ProfileFixtures::class,
            TaskFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $task3 = $this->getReference(TaskFixtures::TASK_3, Task::class);
        $task14 = $this->getReference(TaskFixtures::TASK_14, Task::class);
        $task15 = $this->getReference(TaskFixtures::TASK_15, Task::class);

        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);
        $profile7 = $this->getReference(ProfileFixtures::PROFILE_7, Profile::class);
        $profile12 = $this->getReference(ProfileFixtures::PROFILE_12, Profile::class);
        $profile13 = $this->getReference(ProfileFixtures::PROFILE_13, Profile::class);
        $profile14 = $this->getReference(ProfileFixtures::PROFILE_14, Profile::class);
        $profile15 = $this->getReference(ProfileFixtures::PROFILE_15, Profile::class);

        $this->save([
            new ExecutorSwipe($task3, $profile4, Swipe::TYPE_ACCEPT),

            new ExecutorSwipe($task14, $profile12, Swipe::TYPE_REJECT),
            new ExecutorSwipe($task14, $profile13, Swipe::TYPE_ACCEPT),
            new ExecutorSwipe($task14, $profile14, Swipe::TYPE_ACCEPT),

            new ExecutorSwipe($task15, $profile12, Swipe::TYPE_ACCEPT),
            new ExecutorSwipe($task15, $profile13, Swipe::TYPE_ACCEPT),
            new ExecutorSwipe($task15, $profile15, Swipe::TYPE_ACCEPT),
            new ExecutorSwipe($task15, $profile7, Swipe::TYPE_ACCEPT),
        ], $manager);
    }
}
