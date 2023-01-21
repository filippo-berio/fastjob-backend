<?php

namespace App\Tests\Integration\Core\UseCase\Profile;

use App\Auth\Entity\User;
use App\Core\Entity\Profile;
use App\Core\Exception\Profile\ProfileCreatedException;
use App\Core\UseCase\Profile\CreateProfileUseCase;
use App\DataFixtures\Auth\UserFixtures;
use App\Tests\Integration\IntegrationTest;
use App\Validation\Exception\ValidationException;
use DateTimeImmutable;

class CreateProfileTest extends IntegrationTest
{
    /**
     * @dataProvider errorData
     */
    public function testError(string $exception, int $userId, string $firstName, DateTimeImmutable $birthDate)
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateProfileUseCase::class);
        $user = $this->getEntity(User::class, $userId);
        $this->expectException($exception);
        $useCase->create($user, $firstName, $birthDate->format('Y-m-d'));
    }

    /**
     * @dataProvider successData
     */
    public function testSuccess(int $userId, string $firstName, string $birthDate)
    {
        $this->bootContainer();
        $useCase = $this->getDependency(CreateProfileUseCase::class);
        $user = $this->getEntity(User::class, $userId);
        $profile = $useCase->create($user, $firstName, $birthDate);
        $this->assertEquals($user->getId(), $profile->getUser()->getId());
    }

    private function errorData()
    {
        return [
            [
                ProfileCreatedException::class,
                UserFixtures::USER_1,
                'Name',
                new DateTimeImmutable('14.12.2000'),
            ],
            [
                ValidationException::class,
                UserFixtures::USER_6,
                'Name',
                new DateTimeImmutable('-' . Profile::MINIMAL_AGE - 1 . ' years'),
            ],
            [
                ValidationException::class,
                UserFixtures::USER_6,
                'Name',
                new DateTimeImmutable('+' . Profile::MINIMAL_AGE + 1 . ' years'),
            ],
            [
                ValidationException::class,
                UserFixtures::USER_6,
                'Name',
                new DateTimeImmutable('+1 day'),
            ],
            [
                ValidationException::class,
                UserFixtures::USER_6,
                'Имя123',
                new DateTimeImmutable('2000-12-14'),
            ],
        ];
    }

    private function successData()
    {
        return [
            [
                UserFixtures::USER_6,
                'Имя',
                '2000-12-14',
            ],
            [
                UserFixtures::USER_6,
                'Name',
                '2000-12-14',
            ],
            [
                UserFixtures::USER_6,
                'Name-имя',
                '2000-12-14',
            ],
        ];
    }
}
