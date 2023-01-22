<?php

namespace App\Tests\Unit\Sms\Service\Provider;

use App\Http\Client\ClientBuilder;
use App\Sms\Service\Provider\TelegramSmsProvider;
use App\Tests\Unit\UnitTest;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TelegramSmsProviderTest extends UnitTest
{
    public function testSuccess()
    {
        $response = new MockResponse();
        $clientBuilder = new ClientBuilder(new MockHttpClient([$response]));
        $provider = new TelegramSmsProvider(
            $clientBuilder,
            '1234',
            'http://telegram',
            'chatId',
        );

        $provider->send('+79999999999', 'text');

        $this->assertEquals('GET', $response->getRequestMethod());
        $this->assertEquals([
            'chat_id' => '@chatId',
            'text' => 'Телефон: +79999999999. Текст: text',
        ], $response->getRequestOptions()['query']);
        $this->assertEquals(
            'http://telegram/bot1234/sendMessage?chat_id=%40chatId&text=%D0%A2%D0%B5%D0%BB%D0%B5%D1%84%D0%BE%D0%BD%3A%20%2B79999999999.%20%D0%A2%D0%B5%D0%BA%D1%81%D1%82%3A%20text',
            $response->getRequestUrl()
        );
    }
}
