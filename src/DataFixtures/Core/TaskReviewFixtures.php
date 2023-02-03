<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Review;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\Review\ReviewFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskReviewFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return Review::class;
    }

    public function getDependencies()
    {
        return [
            TaskFixtures::class,
            ProfileFixtures::class,
            ReviewFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $profile16 = $this->getReference(ProfileFixtures::PROFILE_16, Profile::class);
        $profile17 = $this->getReference(ProfileFixtures::PROFILE_17, Profile::class);

        $task20 = $this->getReference(TaskFixtures::TASK_20, Task::class);

        $this->save([
            (new Review($task20, $profile16, $profile17, 5))->setId(1),
            (new Review($task20, $profile17, $profile16, 4))->setId(2),
        ], $manager);
    }
}
