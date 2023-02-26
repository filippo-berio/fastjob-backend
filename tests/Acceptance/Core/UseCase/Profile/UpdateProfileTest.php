<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\UpdateProfileUseCase;
use App\Core\Domain\Entity\Category;
use App\Core\Domain\Event\Task\GenerateNext\GenerateNextTaskEvent;
use App\Core\Domain\Exception\Category\CategoryNotFoundException;
use App\DataFixtures\Core\CategoryFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Lib\Validation\Exception\ValidationException;
use App\Location\Exception\CityNotFoundException;
use App\Tests\Acceptance\AcceptanceTest;

class UpdateProfileTest extends AcceptanceTest
{
    /**
     * @dataProvider regenerateStackData
     */
    public function testRegenerateStack(
        bool $shouldRegenerate,
        array $data,
    ) {
        $useCase = $this->getDependency(UpdateProfileUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_1);
        $useCase->update(
            $profile,
            $data['firstName'] ?? $profile->getFirstName(),
            $data['categories'] ?? array_map(
                fn(Category $category) => $category->getId(),
                $profile->getCategories()
            ),
            $data['lastName'] ?? $profile->getLastName(),
            $data['description'] ?? $profile->getDescription(),
            $data['city'] ?? $profile->getCity()->getId(),
        );

        $this->assertNull(null);

        if ($shouldRegenerate) {
            $this->assertAsyncEventDispatched(GenerateNextTaskEvent::class);
        } else {
            $this->assertAsyncEventNotDispatched(GenerateNextTaskEvent::class);
        }
    }

    /**
     * @dataProvider invalidData
     */
    public function testError(
        string  $exception,
        int     $profileId,
        string  $firstName,
        array   $categoryIds = [],
        ?string $lastName = null,
        ?string $description = null,
        ?int    $cityId = null,
    ) {
        $this->bootContainer();
        $useCase = $this->getDependency(UpdateProfileUseCase::class);
        $profile = $this->getCoreProfile($profileId);
        $this->expectException($exception);
        $useCase->update($profile, $firstName, $categoryIds, $lastName, $description, $cityId);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(
        int     $profileId,
        string  $firstName,
        array   $categoryIds = [],
        ?string $lastName = null,
        ?string $description = null,
        ?int    $cityId = null,
    ) {
        $this->bootContainer();
        $useCase = $this->getDependency(UpdateProfileUseCase::class);
        $profile = $this->getCoreProfile($profileId);
        $profile = $useCase->update($profile, $firstName, $categoryIds, $lastName, $description, $cityId);

        $this->assertEquals($firstName, $profile->getFirstName());
        $this->assertEquals($lastName, $lastName);
        $this->assertEquals($description, $description);
        $this->assertEquals($profile->getCity()?->getId(), $cityId);

        $this->assertCount(count($categoryIds), $profile->getCategories());
        foreach ($profile->getCategories() as $category) {
            $this->assertContains($category->getId(), $categoryIds);
        }
    }

    private function regenerateStackData()
    {
        return [
            [
                false,
                [
                    'firstName' => 'new',
                ],
            ],
            [
                false,
                [
                    'firstName' => 'new',
                    'lastName' => 'newLast',
                    'description' => 'newLast',
                ],
            ],
            [
                false,
                [
                    'firstName' => 'new',
                    'categories' => [
                        CategoryFixtures::PLUMBING,
                    ],
                    'city' => CityFixtures::CITY_1,
                ],
            ],
            [
                true,
                [
                    'firstName' => 'new',
                    'categories' => [
                        CategoryFixtures::PLUMBING,
                        CategoryFixtures::CPLUS,
                    ],
                    'city' => CityFixtures::CITY_1,
                ],
            ],
            [
                true,
                [
                    'firstName' => 'new',
                    'categories' => [
                        CategoryFixtures::CLEANING,
                    ],
                    'city' => CityFixtures::CITY_1,
                ],
            ],
            [
                true,
                [
                    'firstName' => 'new',
                    'categories' => [
                        CategoryFixtures::PLUMBING,
                    ],
                    'city' => CityFixtures::CITY_2,
                ],
            ],
        ];
    }

    private function validData()
    {
        return [
            [
                ProfileFixtures::PROFILE_2,
                'Имя',
                [],
                'LastName',
                'description',
                CityFixtures::CITY_1,
            ],
            [
                ProfileFixtures::PROFILE_2,
                'Имя',
                [
                    CategoryFixtures::CLEANING,
                    CategoryFixtures::FISH,
                ],
                null,
                null,
                CityFixtures::CITY_1,
            ],
            [
                ProfileFixtures::PROFILE_2,
                'Имя',
                [],
                null,
                'Описание',
                null,
            ],
            [
                ProfileFixtures::PROFILE_1,
                'Имя',
                [],
            ],
            [
                ProfileFixtures::PROFILE_2,
                'Имя',
                [
                    CategoryFixtures::COMPUTERS,
                    CategoryFixtures::COMPUTER_REPAIR,
                    CategoryFixtures::CPLUS,
                ],
                'Фамилия',
                null,
                null,
            ],
        ];
    }
    private function invalidData()
    {
        return [
            [
                CityNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                'Имя',
                [
                    CategoryFixtures::COMPUTERS,
                    CategoryFixtures::COMPUTER_REPAIR,
                    CategoryFixtures::CPLUS,
                ],
                null,
                null,
                999,
            ],
            [
                ValidationException::class,
                ProfileFixtures::PROFILE_2,
                'Имя123',
                [],
                null,
                null,
                CityFixtures::CITY_1,
            ],
            [
                ValidationException::class,
                ProfileFixtures::PROFILE_2,
                'Имя',
                [],
                'LastName123',
                'description',
                CityFixtures::CITY_1,
            ],
            [
                CategoryNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                'Имя',
                [
                    CategoryFixtures::NOT_EXIST_CATEGORY,
                ],
            ],
            [
                CategoryNotFoundException::class,
                ProfileFixtures::PROFILE_2,
                'Имя',
                [
                    CategoryFixtures::NOT_EXIST_CATEGORY,
                    CategoryFixtures::CPLUS,
                ],
            ],
        ];
    }
}
