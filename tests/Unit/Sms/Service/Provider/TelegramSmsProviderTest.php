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

        $expectedQuery = [
            'chat_id' => '@chatId',
            'text' => 'text',
        ];
        $this->assertEquals('GET', $response->getRequestMethod());
        $this->assertEquals('http://telegram/bot1234/sendMessage?' . http_build_query($expectedQuery), $response->getRequestUrl());
        $this->assertEquals($expectedQuery, $response->getRequestOptions()['query']);
    }
}
