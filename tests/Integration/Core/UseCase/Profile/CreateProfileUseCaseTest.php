<?php

namespace App\Tests\Integration\Core\UseCase\Profile;

use App\Core\Entity\Profile;
use App\Core\Entity\User;
use App\Core\Exception\Profile\ProfileCreatedException;
use App\Core\UseCase\Profile\CreateProfileUseCase;
use App\DataFixtures\Core\UserFixtures;
use App\Tests\Integration\IntegrationTest;
use App\Validation\Exception\ValidationException;
use DateTimeImmutable;

class CreateProfileUseCaseTest extends IntegrationTest
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
        $this->assertNotNull($user->getProfile()->getId());
        $this->assertEquals($user->getProfile()->getId(), $profile->getId());
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
