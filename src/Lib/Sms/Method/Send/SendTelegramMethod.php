<?php

namespace App\Lib\Sms\Method\Send;

use App\Lib\Http\Method\Method;

class SendTelegramMethod extends Method
{
    public function __construct(
        private string $botToken,
        private string $chatId,
        private string $text,
    ) {
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }

    public function getUri(): string
    {
        return "/bot$this->botToken/sendMessage";
    }

    public function buildQuery(): array
    {
        return [
            'chat_id' => '@' . $this->chatId,
            'text' => $this->text
        ];
    }
}
