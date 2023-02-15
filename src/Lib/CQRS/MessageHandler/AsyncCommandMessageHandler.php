<?php

namespace App\Lib\CQRS\MessageHandler;

use App\Lib\CQRS\Message\AsyncCommandMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(handles: AsyncCommandMessage::class)]
class AsyncCommandMessageHandler extends CommandMessageHandler
{

}
