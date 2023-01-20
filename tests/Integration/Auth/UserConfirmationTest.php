<?php

namespace App\Tests\Integration\Auth;

use App\Auth\Entity\User;
use App\Auth\Exception\InvalidConfirmationCodeException;
use App\Auth\Exception\PhoneBannedException;
use App\Auth\Service\Confirmation\SendConfirmationCodeService;
use App\Auth\Service\Token\RedisTokenService;
use App\Auth\UseCase\UserConfirmation\ConfirmCodeUseCase;
use App\Auth\UseCase\UserConfirmation\SendConfirmationCodeUseCase;
use App\DataFixtures\Core\UserFixtures;
use App\Sms\Service\SmsService;
use App\Tests\Integration\IntegrationTest;

class UserConfirmationTest extends IntegrationTest
{
    public function testRottenConfirmation()
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);

        $sendCodeUseCase = $this->createSendCodeUseCase($redisService);
        $sendCodeUseCase->send(UserFixtures::USER_5_PHONE);
        $confirmData = $redisService->getConfirmationCode(UserFixtures::USER_5_PHONE);

        sleep(3);

        $this->assertNull($redisService->getConfirmationCode(UserFixtures::USER_5_PHONE));
        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $this->expectException(InvalidConfirmationCodeException::class);
        $confirmCodeUseCase->confirm(UserFixtures::USER_5_PHONE, $confirmData->confirmationCode);
    }

    public function testBanUser()
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);
        $sendCodeUseCase = $this->createSendCodeUseCase($redisService);
        $sendCodeUseCase->send(UserFixtures::USER_4_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);

        for ($i = 1; $i <= 5; $i++) {
            $this->expectException(InvalidConfirmationCodeException::class);
            try {
                $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, 999);
            } finally {}
            $confirmData = $redisService->getConfirmationCode(UserFixtures::USER_4_PHONE);
            $this->assertEquals(5 - $i, $confirmData->retries);
        }

        $this->assertTrue($redisService->isPhoneBanned(UserFixtures::USER_4_PHONE));

        $this->expectException(PhoneBannedException::class);
        try {
            $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, 999);
        } finally {}


        sleep(3);

        $this->assertFalse($redisService->isPhoneBanned(UserFixtures::USER_4_PHONE));

        $sendCodeUseCase->send(UserFixtures::USER_4_PHONE);
        $confirmData = $redisService->getConfirmationCode(UserFixtures::USER_4_PHONE);
        $this->assertEquals(5, $confirmData->retries);
        $confirmCodeUseCase->confirm(UserFixtures::USER_4_PHONE, $confirmData->confirmationCode);
    }

    public function testWrongCode()
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);
        $sendCodeUseCase = $this->createSendCodeUseCase($redisService);
        $sendCodeUseCase->send(UserFixtures::USER_3_PHONE);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $this->expectException(InvalidConfirmationCodeException::class);
        try {
            $confirmCodeUseCase->confirm(UserFixtures::USER_3_PHONE, 999);
        } finally {}
        $confirmData = $redisService->getConfirmationCode(UserFixtures::USER_3_PHONE);
        $this->assertEquals(4, $confirmData->retries);
    }

    /**
     * @dataProvider existingUserData
     */
    public function testExistingUser(string $phone)
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);

        $sendCodeUseCase = $this->createSendCodeUseCase($redisService);
        $sendCodeUseCase->send($phone);

        $confirmData = $redisService->getConfirmationCode($phone);
        $this->assertNotNull($confirmData->confirmationCode);
        $this->assertEquals(5, $confirmData->retries);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $tokens = $confirmCodeUseCase->confirm($phone, $confirmData->confirmationCode);
        $user = $this->getEntityBy(User::class, [
            'phone' => $phone
        ])[0];
        $this->assertEquals($tokens->accessToken, $redisService->getAccessToken($user));
    }

    public function testNewUser()
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);

        $sendCodeUseCase = $this->createSendCodeUseCase($redisService);
        $sendCodeUseCase->send(UserFixtures::NOT_EXIST_USER_PHONE);

        $this->assertEmpty($this->getEntityBy(User::class, [
            'phone' => UserFixtures::NOT_EXIST_USER_PHONE
        ]));

        $confirmData = $redisService->getConfirmationCode(UserFixtures::NOT_EXIST_USER_PHONE);
        $this->assertNotNull($confirmData->confirmationCode);
        $this->assertEquals(5, $confirmData->retries);

        $confirmCodeUseCase = $this->getDependency(ConfirmCodeUseCase::class);
        $tokens = $confirmCodeUseCase->confirm(UserFixtures::NOT_EXIST_USER_PHONE, $confirmData->confirmationCode);
        $users = $this->getEntityBy(User::class, [
            'phone' => UserFixtures::NOT_EXIST_USER_PHONE
        ]);
        $this->assertCount(1, $users);
        $user = $users[0];
        $this->assertEquals($tokens->accessToken, $redisService->getAccessToken($user));
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

    // todo mock telegram
    private function createSendCodeUseCase(RedisTokenService $redisService): SendConfirmationCodeUseCase
    {
        $smsService = $this->createMock(SmsService::class);
        return new SendConfirmationCodeUseCase(new SendConfirmationCodeService($smsService, $redisService));
    }
}
