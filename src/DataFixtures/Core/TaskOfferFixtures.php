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
        $task18 = $this->getReference(TaskFixtures::TASK_18, Task::class);
        $task19 = $this->getReference(TaskFixtures::TASK_19, Task::class);
        $task20 = $this->getReference(TaskFixtures::TASK_20, Task::class);

        $taskOffer2 = new TaskOffer($task17, $profile16);
        $taskOffer2->accept();

        $taskOffer3 = new TaskOffer($task18, $profile16);
        $taskOffer3->accept();

        $taskOffer4 = new TaskOffer($task19, $profile16);
        $taskOffer4->accept();

        $taskOffer5 = new TaskOffer($task20, $profile16);
        $taskOffer5->accept();

        $this->save([
            new TaskOffer($task16, $profile16),
            $taskOffer2,
            $taskOffer3,
            $taskOffer4,
            $taskOffer5,
        ], $manager);
    }
}
