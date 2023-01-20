<?php

namespace App\Tests\Integration\Core\UseCase\Auth;

use App\Auth\Service\Confirmation\SendConfirmationCodeService;
use App\Auth\Service\Token\RedisTokenService;
use App\Auth\UseCase\UserConfirmation\SendConfirmationCodeUseCase;
use App\DataFixtures\Core\UserFixtures;
use App\Sms\Service\SmsService;
use App\Tests\Integration\IntegrationTest;

class SendConfirmationCodeUseCaseTest extends IntegrationTest
{
    // TODO BEHAT
    public function testNewUser()
    {
        $this->bootContainer();
        $redisService = $this->getDependency(RedisTokenService::class);
        $useCase = $this->createUseCase($redisService);
        $useCase->send(UserFixtures::NOT_EXIST_USER_PHONE);
        $this->assertIsInt(2);
//        $this->assertNotNull();
    }

    // todo mock telegram
    private function createUseCase(RedisTokenService $redisService): SendConfirmationCodeUseCase
    {
        $smsService = $this->createMock(SmsService::class);
        return new SendConfirmationCodeUseCase(new SendConfirmationCodeService($smsService, $redisService));
    }
}
