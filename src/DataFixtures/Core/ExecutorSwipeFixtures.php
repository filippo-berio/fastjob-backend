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

        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);

        $this->save([
            new ExecutorSwipe($task3, $profile4, Swipe::TYPE_ACCEPT),
        ], $manager);
    }
}
