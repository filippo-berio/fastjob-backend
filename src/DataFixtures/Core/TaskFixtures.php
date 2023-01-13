<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const TASK_1 = 1;
    const TASK_2 = 2;
    const TASK_3 = 3;

    protected function getEntity(): string
    {
        return Task::class;
    }

    public function load(ObjectManager $manager)
    {
        $task2 = new Task(
            'Архивированное задание',
            $this->getReference(ProfileFixtures::PROFILE_1, Profile::class),
            [$this->getReference(CategoryFixtures::FISH, Category::class)]
        );
        $task2->delete();

        $this->save([
            $task1 = new Task(
                'Научить языку с++',
                $this->getReference(ProfileFixtures::PROFILE_3, Profile::class),
                [$this->getReference(CategoryFixtures::CPLUS, Category::class)],
                1500
            ),
            $task2,
            $task3 = new Task(
                'Починить кран',
                $this->getReference(ProfileFixtures::PROFILE_1, Profile::class),
                [$this->getReference(CategoryFixtures::PLUMBING, Category::class)],
                3000
            ),
        ], $manager);

        $this->addReference(self::TASK_1, $task1);
        $this->addReference(self::TASK_2, $task2);
        $this->addReference(self::TASK_3, $task3);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ProfileFixtures::class,
        ];
    }
}
