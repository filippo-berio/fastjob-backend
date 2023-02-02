<?php

namespace App\CQRS\MessageHandler;

use App\CQRS\Message\AsyncCommandMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: AsyncCommandMessage::class)]
class AsyncCommandMessageHandler extends CommandMessageHandler
{

}
