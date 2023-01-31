<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\Core\Infrastructure\Entity\Profile;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskOfferFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const OFFER_1 = 1;

    protected function getEntity(): string
    {
        return TaskOffer::class;
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

        $task16 = $this->getReference(TaskFixtures::TASK_16, Task::class);
        $task17 = $this->getReference(TaskFixtures::TASK_17, Task::class);

        $taskOffer2 = new TaskOffer($task17, $profile16);
        $taskOffer2->accept();

        $this->save([
            new TaskOffer($task16, $profile16),
            $taskOffer2,
        ], $manager);
    }
}
