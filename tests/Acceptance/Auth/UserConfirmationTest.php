<?php

namespace App\Tests\Acceptance\Auth;

use App\Auth\DTO\CodeConfirmationResult;
use App\Auth\Entity\User;
use App\Auth\Exception\ConfirmationTimeoutException;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Repository\AccessTokenRepository;
use App\Auth\Repository\BannedPhoneRepository;
use App\Auth\Repository\ConfirmationTokenRepository;
use App\Auth\Service\Confirmation\SendConfirmationCodeService;
use App\Auth\UseCase\UserConfirmation\ConfirmCodeUseCase;
use App\Auth\UseCase\UserConfirmation\SendConfirmationCodeUseCase;
use App\DataFixtures\Auth\UserFixtures;
use App\Sms\Message\SmsMessage;
use App\Tests\Acceptance\AcceptanceTest;

class UserConfirmationTest extends AcceptanceTest
{
    public function testBanUser()
    {
        $bannedPhoneRepo = $this->getDependency(BannedPhoneRepository::class);
        $confirmationTokenRepository = $this->getDependency(ConfirmationTokenRepository::class);
        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::USER_4_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);

        for ($i = 1; $i <= 5 + 1; $i++) {
            $result = $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, 999);
            $this->assertConfirmationFailure($result, $i);
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

    public function testTimeout()
    {
        $useCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $useCase->send('+79999999999');
        $this->expectException(ConfirmationTimeoutException::class);
        try {
            $useCase->send('+79999999999');
        } finally {}
        $this->redisClear();
        $useCase->send('+79999999999');
    }

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

    public function testWrongCode()
    {
        $sendCodeUseCase = $this->getDependency(SendConfirmationCodeUseCase::class);
        $sendCodeUseCase->send(UserFixtures::USER_3_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $result = $confirmCodeUseCase->confirm(UserFixtures::USER_3_PHONE, 999);
        $this->assertConfirmationFailure($result);
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
        $result = $confirmCodeUseCase->confirm($phone, $confirmData->getConfirmationCode());
        $user = $this->getEntityBy(User::class, [
            'phone' => $phone
        ])[0];
        $this->assertEquals($result->tokens->accessToken, $this->getAccessToken($user));
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
        $result = $confirmCodeUseCase->confirm(UserFixtures::NOT_EXIST_USER_PHONE, $confirmData->getConfirmationCode());
        $users = $this->getEntityBy(User::class, [
            'phone' => UserFixtures::NOT_EXIST_USER_PHONE
        ]);
        $this->assertCount(1, $users);
        $user = $users[0];
        $this->assertEquals($result->tokens->accessToken, $this->getAccessToken($user));
    }

    private function assertConfirmationFailure(CodeConfirmationResult $result, int $failsCount = 1)
    {
        $this->assertEquals(SendConfirmationCodeService::RETRIES - $failsCount, $result->retries);
        $this->assertFalse($result->success);
        $this->assertNull($result->tokens);
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
