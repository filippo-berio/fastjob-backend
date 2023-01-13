<?php

namespace App\DataFixtures\Core;

use App\Core\Entity\Category;
use App\DataFixtures\BaseFixtures;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixtures
{
    const COMPUTERS = 1;
    const CODING = 2;
    const CPLUS = 3;
    const COMPUTER_REPAIR = 4;

    const HOUSEWORK = 5;
    const PLUMBING = 6; // сантехника
    const CLEANING = 7;

    const PETS = 8;
    const FISH = 9;

    protected function getEntity(): string
    {
        return Category::class;
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            $category1 = new Category('Компьютеры'),
            $category2 = new Category('Программирование'),
            $category3 = new Category('C++'),
            $category4 = new Category('Ремонт компьютеров'),

            $category5 = new Category('Работа по дому'),
            $category6 = new Category('Сантехника'),
            $category7 = new Category('Чистка'),

            $category8 = new Category('Домашние питомцы'),
            $category9 = new Category('Рыбы'),
        ], $manager);

        $this->addReference(self::COMPUTERS, $category1);
        $this->addReference(self::CODING, $category2);
        $this->addReference(self::CPLUS, $category3);
        $this->addReference(self::COMPUTER_REPAIR, $category4);
        $this->addReference(self::HOUSEWORK, $category5);
        $this->addReference(self::PLUMBING, $category6);
        $this->addReference(self::CLEANING, $category7);
        $this->addReference(self::PETS, $category8);
        $this->addReference(self::FISH, $category9);
    }
}
