<?php

namespace App\Core\Application\UseCase\Chat;

use App\Chat\Exception\DirectChatNotFound;
use App\Chat\Repository\DirectChatRepositoryInterface;
use App\Chat\Service\Direct\CreateMessageService;
use App\Core\Domain\Entity\Profile;

class CreateMessageUseCase
{
    public function __construct(
        private CreateMessageService $createMessageService,
        private DirectChatRepositoryInterface $directChatRepository,
    ) {
    }

    public function create(Profile $profile, int $chatId, string $content)
    {
        $chat = $this->directChatRepository->find($chatId);
        if (!$chat) {
            throw new DirectChatNotFound();
        }
        $this->createMessageService->create($profile, $chat, $content);
    }
}
