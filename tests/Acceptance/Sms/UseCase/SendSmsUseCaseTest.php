<?php

namespace App\Tests\Acceptance\Sms\UseCase;

use App\Sms\Message\SmsMessage;
use App\Sms\UseCase\SendSmsUseCase;
use App\Tests\Acceptance\AcceptanceTest;
use WireMock\Client\WireMock;

class SendSmsUseCaseTest extends AcceptanceTest
{
    public function testSuccess()
    {
        $this->bootContainer();
        $useCase = $this->getDependency(SendSmsUseCase::class);
        $useCase->send('+79999999999', 'text');
        $this->messenger()->queue()->assertContains(SmsMessage::class, 1);
        /** @var SmsMessage $message */
        $message = $this->messenger()->queue()->first()->getMessage();
        $this->assertEquals('+79999999999', $message->getPhone());
        $this->assertEquals('text', $message->getText());

        $wiremock = $this->createWireMockClient();
        $urlMatchingStrategy = WireMock::urlMatching('^\/botToken\/sendMessage\?(.*)chat_id=%40tg_chat_id&text=(.*)79999999999(.*)text$');
        $wiremock->stubFor(WireMock::get($urlMatchingStrategy)->willReturn(WireMock::aResponse()->withStatus(200)));

        $this->messenger()->process(1);

        $wiremock->verify(WireMock::getRequestedFor($urlMatchingStrategy));
    }
}
