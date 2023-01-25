<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\Category;
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

    const NOT_EXIST_CATEGORY = 999;

    protected function getEntity(): string
    {
        return Category::class;
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            $computers = new Category('Компьютеры', false),
            $coding = new Category('Программирование', true, $computers),
            $cpp = new Category('C++', parent: $coding),
            $computerRepair = new Category('Ремонт компьютеров', parent: $computers),

            $houseWork = new Category('Работа по дому', false,),
            $plumbing = new Category('Сантехника', parent: $houseWork),
            $cleaning = new Category('Чистка', parent: $houseWork),

            $pets = new Category('Домашние питомцы', false),
            $fish = new Category('Рыбы', parent: $pets),
        ], $manager);

        $this->addReference(self::COMPUTERS, $computers);
        $this->addReference(self::CODING, $coding);
        $this->addReference(self::CPLUS, $cpp);
        $this->addReference(self::COMPUTER_REPAIR, $computerRepair);
        $this->addReference(self::HOUSEWORK, $houseWork);
        $this->addReference(self::PLUMBING, $plumbing);
        $this->addReference(self::CLEANING, $cleaning);
        $this->addReference(self::PETS, $pets);
        $this->addReference(self::FISH, $fish);
    }
}
