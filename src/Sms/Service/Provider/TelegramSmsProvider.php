<?php

namespace App\Sms\Service\Provider;

use App\Http\Client\ClientBuilder;
use App\Sms\Exception\SmsServiceException;
use App\Sms\Method\Send\SendTelegramMethod;
use Throwable;

class TelegramSmsProvider implements SmsProviderInterface
{

    public function __construct(
        private ClientBuilder $clientBuilder,
        private string $botToken,
        private string $host,
        private string $chatId,
    ) {
    }

    public function send(string $phone, string $text): void
    {
        $client = $this->clientBuilder->build($this->host);
        try {
            $client->request(new SendTelegramMethod(
                $this->botToken,
                $this->chatId,
                $text
            ));
        } catch (Throwable $exception) {
            throw new SmsServiceException('Произошла ошибка при обращении в телегу', 500, $exception);
        }
    }

    public function getAlias(): string
    {
        return 'telegram';
    }
}
