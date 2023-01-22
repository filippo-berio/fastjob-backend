<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\Location\AddressFixtures;
use App\Location\Entity\Address;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const TASK_1 = 1;
    const TASK_2 = 2;
    const TASK_3 = 3;
    const TASK_4 = 4;
    const TASK_5 = 5;
    const TASK_6 = 6;
    const TASK_7 = 7;
    const TASK_8 = 8;
    const TASK_9 = 9;
    const TASK_10 = 10;

    protected function getEntity(): string
    {
        return Task::class;
    }

    public function load(ObjectManager $manager)
    {
        $task2 = new Task(
            'Архивированное задание',
            $this->getReference(ProfileFixtures::PROFILE_1, Profile::class),
            [$this->getReference(CategoryFixtures::FISH, Category::class)],
            false,
        );
        $task2->delete();

        $this->save([
            $task1 = new Task(
                'Научить языку с++',
                $this->getReference(ProfileFixtures::PROFILE_3, Profile::class),
                [$this->getReference(CategoryFixtures::CPLUS, Category::class)],
                true,
                1500,
            ),
            $task2,
            $task3 = new Task(
                'Починить кран',
                $this->getReference(ProfileFixtures::PROFILE_1, Profile::class),
                [$this->getReference(CategoryFixtures::PLUMBING, Category::class)],
                false,
                3000,
                $this->getReference(AddressFixtures::ADDRESS_1, Address::class),
                deadline: (new DateTimeImmutable())->modify('+1 day')
            ),
            $task4 = new Task(
                'Написать курсач по плюсам',
                $this->getReference(ProfileFixtures::PROFILE_3, Profile::class),
                [
                    $this->getReference(CategoryFixtures::CPLUS, Category::class),
                ],
                true,
            ),
            $task5 = new Task(
                'Написать алгоритм для станка',
                $this->getReference(ProfileFixtures::PROFILE_3, Profile::class),
                [
                    $this->getReference(CategoryFixtures::CPLUS, Category::class),
                    $this->getReference(CategoryFixtures::COMPUTERS, Category::class),
                ],
                true,
                address: $this->getReference(AddressFixtures::ADDRESS_3, Address::class),
            ),
            $task6 = new Task(
                'Выгулять пса',
                $this->getReference(ProfileFixtures::PROFILE_1, Profile::class),
                [
                    $this->getReference(CategoryFixtures::PETS, Category::class),
                ],
                false,
                500,
                address: $this->getReference(AddressFixtures::ADDRESS_2, Address::class)
            ),
            $task7 = new Task(
                'Проконсультировать типа по собаке',
                $this->getReference(ProfileFixtures::PROFILE_2, Profile::class),
                [
                    $this->getReference(CategoryFixtures::PETS, Category::class),
                ],
                true,
                500,
            ),
            $task8 = new Task(
                'Кастрировать кота',
                $this->getReference(ProfileFixtures::PROFILE_5, Profile::class),
                [
                    $this->getReference(CategoryFixtures::PETS, Category::class),
                ],
                false,
                address: $this->getReference(AddressFixtures::ADDRESS_4, Address::class),
            ),
            $task9 = new Task(
                'Какое-нибудь удаленное задание по животным',
                $this->getReference(ProfileFixtures::PROFILE_5, Profile::class),
                [
                    $this->getReference(CategoryFixtures::PETS, Category::class),
                ],
                true,
            )
        ], $manager);

        $this->addReference(self::TASK_1, $task1);
        $this->addReference(self::TASK_2, $task2);
        $this->addReference(self::TASK_3, $task3);
        $this->addReference(self::TASK_4, $task4);
        $this->addReference(self::TASK_5, $task5);
        $this->addReference(self::TASK_9, $task9);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ProfileFixtures::class,
            AddressFixtures::class,
        ];
    }
}
