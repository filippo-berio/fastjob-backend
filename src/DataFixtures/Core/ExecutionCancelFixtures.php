<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\ExecutionCancel;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExecutionCancelFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return ExecutionCancel::class;
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
        $profile16 = $this->getReference(ProfileFixtures::PROFILE_16, Profile::class);

        $task19 = $this->getReference(TaskFixtures::TASK_19, Task::class);

        $this->save([
            new ExecutionCancel($profile16, $task19),
        ], $manager);
    }
}
