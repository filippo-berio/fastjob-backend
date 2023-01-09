<?php

namespace App\DataFixtures;

use App\Core\Entity\Category;
use App\Core\Entity\CategoryBranch;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryBranchFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return CategoryBranch::class;
    }

    public function load(ObjectManager $manager)
    {
        $this->save([
            $computers = new CategoryBranch($this->getCategoryReference(CategoryFixtures::COMPUTERS)),
            $coding = new CategoryBranch($this->getCategoryReference(
                CategoryFixtures::CODING),
                $computers
            ),
            $cplus = new CategoryBranch($this->getCategoryReference(
                CategoryFixtures::CPLUS),
                $coding
            ),
            $computerRepair = new CategoryBranch($this->getCategoryReference(
                CategoryFixtures::COMPUTER_REPAIR),
                $computers
            ),

            $houseWork = new CategoryBranch($this->getCategoryReference(CategoryFixtures::HOUSEWORK)),
            $plumbing = new CategoryBranch(
                $this->getCategoryReference(CategoryFixtures::PLUMBING),
                $houseWork
            ),
            $cleaning = new CategoryBranch(
                $this->getCategoryReference(CategoryFixtures::CLEANING),
                $houseWork
            ),

            $pets = new CategoryBranch($this->getCategoryReference(CategoryFixtures::PETS)),
            $fish = new CategoryBranch(
                $this->getCategoryReference(CategoryFixtures::FISH),
                $pets
            ),
        ], $manager);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }

    private function getCategoryReference(string $category): Category
    {
        return $this->getReference($category, Category::class);
    }
}
