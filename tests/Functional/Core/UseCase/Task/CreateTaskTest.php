<?php

namespace App\Tests\Functional\Core\UseCase\Task;

use App\Core\Application\UseCase\Task\CreateTaskUseCase;
use App\Core\Domain\DTO\Address\AddressPlain;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Category\CategoryNotFoundException;
use App\DataFixtures\Core\CategoryFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Location\Exception\CityNotFoundException;
use App\Tests\Functional\FunctionalTest;
use App\Validation\Exception\ValidationException;
use DateTimeImmutable;

class CreateTaskTest extends FunctionalTest
{
    /**
     * @dataProvider successData
     */
    public function testSuccess(
        int $profileId,
        string $title,
        bool $remote = false,
        array $categoryIds = [],
        ?string $description = null,
        ?int $price = null,
        ?AddressPlain $addressPlain = null,
        ?string $deadline = null,
    ) {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateTaskUseCase::class);
        $task = $useCase->create(
            $this->getEntity(Profile::class, $profileId),
            $title,
            $remote,
            $categoryIds,
            $description,
            $price,
            $addressPlain,
            $deadline,
        );
        $this->assertNotNull($task->getId());
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($description, $task->getDescription());
        $this->assertEquals($price, $task->getPrice());
        $this->assertEquals($addressPlain?->cityId, $task->getAddress()?->getCity()->getId());
        $this->assertEquals($addressPlain?->title, $task->getAddress()?->getTitle());
        $this->assertEquals($deadline, $task->getDeadline()?->format('Y-m-d'));
        $this->assertEquals($remote, $task->isRemote());

        $this->assertCount(count($categoryIds), $task->getCategories());
        foreach ($task->getCategories() as $category) {
            $this->assertContains($category->getId(), $categoryIds);
        }
    }

    public function testOldDate()
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateTaskUseCase::class);
        $this->expectException(ValidationException::class);
        $useCase->create(
            $this->getEntity(Profile::class, ProfileFixtures::PROFILE_1),
            '$title',
            true,
            deadline: '2010-01-01'
        );
    }

    public function testCityDoesntExist()
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateTaskUseCase::class);
        $this->expectException(CityNotFoundException::class);
        $useCase->create(
            $this->getEntity(Profile::class, ProfileFixtures::PROFILE_1),
            '$title',
            true,
            addressPlain: new AddressPlain(
                CityFixtures::CITY_NOT_EXIST,
                'title',
            )
        );
    }

    /**
     * @dataProvider categoriesDontExistData
     */
    public function testCategoriesDontExist(array $ids)
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateTaskUseCase::class);
        $this->expectException(CategoryNotFoundException::class);
        $useCase->create(
            $this->getEntity(Profile::class, ProfileFixtures::PROFILE_1),
            '$title',
            false,
            $ids
        );
    }

    private function successData()
    {
        return [
            [
                ProfileFixtures::PROFILE_1,
                'title',
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                true,
                [
                    CategoryFixtures::COMPUTER_REPAIR,
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                false,
                [
                    CategoryFixtures::COMPUTER_REPAIR,
                    CategoryFixtures::CLEANING,
                    CategoryFixtures::COMPUTERS,
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                true,
                [
                    CategoryFixtures::COMPUTERS,
                ],
                'description',
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                false,
                [
                    CategoryFixtures::COMPUTERS,
                ],
                'description',
                1000,
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                false,
                [
                    CategoryFixtures::COMPUTERS,
                ],
                'description',
                1000,
                new AddressPlain(CityFixtures::CITY_1, 'address'),
                (new DateTimeImmutable())->modify('+10 days')->format('Y-m-d'),
            ],
        ];
    }

    private function categoriesDontExistData()
    {
        return [
            [
                [
                    CategoryFixtures::NOT_EXIST_CATEGORY,
                ],
            ],
            [
                [
                    CategoryFixtures::NOT_EXIST_CATEGORY,
                    CategoryFixtures::COMPUTER_REPAIR,
                ],
            ],
        ];
    }
}
