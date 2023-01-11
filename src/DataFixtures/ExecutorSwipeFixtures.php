<?php

namespace App\DataFixtures;

use App\Core\Entity\ExecutorSwipe;
use App\Core\Entity\Profile;
use App\Core\Entity\Swipe;
use App\Core\Entity\Task;
use Doctrine\Persistence\ObjectManager;

class ExecutorSwipeFixtures extends BaseFixtures implements \Doctrine\Common\DataFixtures\DependentFixtureInterface
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
        /** @var Task $task3 */
        $task3 = $this->getReference(TaskFixtures::TASK_3, Task::class);
        $profile4 = $this->getReference(ProfileFixtures::PROFILE_4, Profile::class);

        $this->save([
            new ExecutorSwipe($task3, $profile4, Swipe::TYPE_ACCEPT),
        ], $manager);
    }
}
