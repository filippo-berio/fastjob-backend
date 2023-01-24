<?php

namespace App\Tests\Functional\Auth;

use App\Auth\Entity\User;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Repository\AccessTokenRepository;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
use App\Auth\UseCase\UserConfirmation\ConfirmCodeUseCase;
use App\Auth\UseCase\UserConfirmation\SendConfirmationCodeUseCase;
use App\DataFixtures\Auth\UserFixtures;
use App\Sms\Message\SmsMessage;
use App\Tests\Functional\FunctionalTest;

class UserConfirmationTest extends FunctionalTest
{
    public function testRottenConfirmation()
    {
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);

        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::USER_5_PHONE);
        $confirmData = $confirmationTokenRepository->findByPhone(UserFixtures::USER_5_PHONE);

        $this->redisClear();

        $this->assertNull($confirmationTokenRepository->findByPhone(UserFixtures::USER_5_PHONE));
        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $this->expectException(InvalidConfirmationCodeException::class);
        $confirmCodeUseCase->confirm(UserFixtures::USER_5_PHONE, $confirmData->getConfirmationCode());
    }

    public function testBanUser()
    {
        $bannedPhoneRepo = $this->getDependency(BannedPhoneRepository::class);
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);
        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::USER_4_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);

        for ($i = 1; $i <= 5; $i++) {
            $this->expectException(InvalidConfirmationCodeException::class);
            try {
                $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, 999);
            } finally {}
            $confirmData = $confirmationTokenRepository->findByPhone(UserFixtures::USER_4_PHONE);
            $this->assertEquals(5 - $i, $confirmData->getRetries());
        }

        $this->assertTrue($bannedPhoneRepo->isPhoneBanned(UserFixtures::USER_4_PHONE));

        $this->expectException(PhoneBannedException::class);
        try {
            $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, 999);
        } finally {}


        $this->redisClear();

        $this->assertFalse($bannedPhoneRepo->isPhoneBanned(UserFixtures::USER_4_PHONE));

        $sendCodeUseCase->send(UserFixtures::USER_4_PHONE);
        $confirmData = $confirmationTokenRepository->findByPhone(UserFixtures::USER_4_PHONE);
        $this->assertEquals(5, $confirmData->getRetries());
        $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, $confirmData->getConfirmationCode());
    }

    public function testWrongCode()
    {
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);
        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::USER_3_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $this->expectException(InvalidConfirmationCodeException::class);
        try {
            $confirmCodeUseCase->confirm(UserFixtures::USER_3_PHONE, 999);
        } finally {}
        $confirmData = $confirmationTokenRepository->findByPhone(UserFixtures::USER_3_PHONE);
        $this->assertEquals(4, $confirmData->getRetries());
    }

    /**
     * @dataProvider existingUserData
     */
    public function testExistingUser(string $phone)
    {
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);

        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send($phone);

        $confirmData = $confirmationTokenRepository->findByPhone($phone);
        $this->assertNotNull($confirmData->getConfirmationCode());
        $this->assertEquals(5, $confirmData->getRetries());

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $tokens = $confirmCodeUseCase->confirm($phone, $confirmData->getConfirmationCode());
        $user = $this->getEntityBy(User::class, [
            'phone' => $phone
        ])[0];
        $this->assertEquals($tokens->accessToken, $this->getAccessToken($user));
    }

    public function testNewUser()
    {
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);

        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::NOT_EXIST_USER_PHONE);
        $this->messenger()->queue()->assertContains(SmsMessage::class, 1);

        $this->assertEmpty($this->getEntityBy(User::class, [
            'phone' => UserFixtures::NOT_EXIST_USER_PHONE
        ]));

        $confirmData = $confirmationTokenRepository->findByPhone(UserFixtures::NOT_EXIST_USER_PHONE);
        $this->assertNotNull($confirmData->getConfirmationCode());
        $this->assertEquals(5, $confirmData->getRetries());

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $tokens = $confirmCodeUseCase->confirm(UserFixtures::NOT_EXIST_USER_PHONE, $confirmData->getConfirmationCode());
        $users = $this->getEntityBy(User::class, [
            'phone' => UserFixtures::NOT_EXIST_USER_PHONE
        ]);
        $this->assertCount(1, $users);
        $user = $users[0];
        $this->assertEquals($tokens->accessToken, $this->getAccessToken($user));
    }

    private function getAccessToken(User $user): ?string
    {
        return $this->getDependency(AccessTokenRepository::class)
            ->findByUser($user)
            ?->getValue();
    }

    private function existingUserData()
    {
        return [
            [
                UserFixtures::USER_1_PHONE,
            ],
            [
                UserFixtures::USER_6_PHONE,
            ],
        ];
    }
}