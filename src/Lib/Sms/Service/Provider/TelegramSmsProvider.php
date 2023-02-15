<?php

namespace App\Lib\Sms\Service\Provider;

use App\Lib\Http\Client\ClientBuilder;
use App\Lib\Sms\Exception\SmsServiceException;
use App\Lib\Sms\Method\Send\SendTelegramMethod;
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
                "Телефон: $phone. Текст: $text"
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
