<?php

namespace App\Tests\Integration\Core\UseCase\Auth;

use App\Core\Service\Auth\Confirmation\SendConfirmationCodeService;
use App\Core\Service\Auth\Token\RedisTokenService;
use App\Core\UseCase\Auth\SendConfirmationCodeUseCase;
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
        $this->assertNotNull();
    }

    // todo mock telegram
    private function createUseCase(RedisTokenService $redisService): SendConfirmationCodeUseCase
    {
        $smsService = $this->createMock(SmsService::class);
        return new SendConfirmationCodeUseCase(new SendConfirmationCodeService($smsService, $redisService));
    }
}
