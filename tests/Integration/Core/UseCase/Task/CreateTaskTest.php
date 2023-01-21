<?php

namespace App\Tests\Integration\Core\UseCase\Task;

use App\Core\DTO\Address\AddressPlain;
use App\Core\Entity\Profile;
use App\Core\Exception\Category\CategoryNotFoundException;
use App\Core\UseCase\Task\CreateTaskUseCase;
use App\DataFixtures\Core\CategoryFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Location\Exception\CityNotFoundException;
use App\Tests\Integration\IntegrationTest;
use App\Validation\Exception\ValidationException;
use DateTimeImmutable;

class CreateTaskTest extends IntegrationTest
{
    /**
     * @dataProvider successData
     */
    public function testSuccess(
        int $profileId,
        string $title,
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
                [
                    CategoryFixtures::COMPUTER_REPAIR,
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                [
                    CategoryFixtures::COMPUTER_REPAIR,
                    CategoryFixtures::CLEANING,
                    CategoryFixtures::COMPUTERS,
                ]
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                [
                    CategoryFixtures::COMPUTERS,
                ],
                'description',
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
                [
                    CategoryFixtures::COMPUTERS,
                ],
                'description',
                1000,
            ],
            [
                ProfileFixtures::PROFILE_1,
                'title',
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
