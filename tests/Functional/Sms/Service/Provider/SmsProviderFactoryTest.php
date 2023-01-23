<?php

namespace App\Tests\Functional\Sms\Service\Provider;

use App\Sms\Service\Provider\SmsProviderFactory;
use App\Sms\Service\Provider\TargetSmsProvider;
use App\Sms\Service\Provider\TelegramSmsProvider;
use App\Tests\Functional\FunctionalTest;

class SmsProviderFactoryTest extends FunctionalTest
{
    public function testProviderCreation()
    {
        $this->bootContainer();
        $factory = $this->getDependency(SmsProviderFactory::class);
        $this->assertInstanceOf(TelegramSmsProvider::class, $factory->create('telegram'));
        $this->assertInstanceOf(TargetSmsProvider::class, $factory->create('targetSms'));

        $this->expectExceptionMessage('Несуществующий провайдер test');
        $factory->create('test');
    }
}
