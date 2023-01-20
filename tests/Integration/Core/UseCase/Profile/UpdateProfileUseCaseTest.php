<?php

namespace App\Tests\Integration\Core\UseCase\Profile;

use App\Auth\Entity\User;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\UseCase\Profile\UpdateProfileUseCase;
use App\DataFixtures\Core\UserFixtures;
use App\DataFixtures\Location\CityFixtures;
use App\Location\Exception\CityNotFoundException;
use App\Tests\Integration\IntegrationTest;
use App\Validation\Exception\ValidationException;

class UpdateProfileUseCaseTest extends IntegrationTest
{
    /**
     * @dataProvider invalidData
     */
    public function testError(
        string $exception,
        int $userId,
        string $firstName,
        ?string $lastName = null,
        ?string $description = null,
        ?int $cityId = null,
    ) {
        $this->bootContainer();
        $useCase = $this->getDependency(UpdateProfileUseCase::class);
        $user = $this->getEntity(User::class, $userId);
        $this->expectException($exception);
        $useCase->update($user, $firstName, $lastName, $description, $cityId);
    }

    /**
     * @dataProvider validData
     */
    public function testSuccess(
        int $userId,
        string $firstName,
        ?string $lastName = null,
        ?string $description = null,
        ?int $cityId = null,
    ) {
        $this->bootContainer();
        $useCase = $this->getDependency(UpdateProfileUseCase::class);
        $user = $this->getEntity(User::class, $userId);
        $profile = $useCase->update($user, $firstName, $lastName, $description, $cityId);

        $this->assertEquals($firstName, $profile->getFirstName());
        $this->assertEquals($lastName, $lastName);
        $this->assertEquals($description, $description);
        $this->assertEquals($profile->getCity()?->getId(), $cityId);
    }

    private function validData()
    {
        return [
            [
                UserFixtures::USER_2,
                'Имя',
                'LastName',
                'description',
                CityFixtures::CITY_1,
            ],
            [
                UserFixtures::USER_2,
                'Имя',
                null,
                null,
                CityFixtures::CITY_1,
            ],
            [
                UserFixtures::USER_2,
                'Имя',
                null,
                'Описание',
                null,
            ],
            [
                UserFixtures::USER_2,
                'Имя',
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
                ProfileNotFoundException::class,
                UserFixtures::USER_6,
                'Имя',
            ],
            [
                CityNotFoundException::class,
                UserFixtures::USER_2,
                'Имя',
                null,
                null,
                999,
            ],
            [
                ValidationException::class,
                UserFixtures::USER_2,
                'Имя123',
                null,
                null,
                CityFixtures::CITY_1,
            ],
            [
                ValidationException::class,
                UserFixtures::USER_2,
                'Имя',
                'LastName123',
                'description',
                CityFixtures::CITY_1,
            ],
        ];
    }
}
