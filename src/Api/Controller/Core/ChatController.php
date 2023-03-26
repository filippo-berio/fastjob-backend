<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Chat\CreateMessageRequest;
use App\Core\Application\UseCase\Chat\CreateMessageUseCase;
use App\Core\Application\UseCase\Chat\GetChatUseCase;
use App\Core\Application\UseCase\Chat\GetProfileChatsUseCase;
use App\Core\Infrastructure\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/chat')]
class ChatController extends BaseController
{
    #[Route('', methods: ['GET'])]
    public function profileChats(
        #[CurrentUser] Profile $profile,
        GetProfileChatsUseCase $useCase,
    ): JsonResponse {
        $chats = $useCase->get($profile);
        return $this->json($chats, context: [
            'chat_short'
        ]);
    }

    #[Route('/{companionId}', methods: ['GET'])]
    public function getChat(
        #[CurrentUser] Profile $profile,
        int $companionId,
        GetChatUseCase $useCase,
    ): JsonResponse {
        $chat = $useCase->get($profile, $companionId);
        return $this->json($chat, context: [
            'chat_short'
        ]);
    }

    #[Route('/message', methods: ['POST'])]
    public function createMessage(
        #[CurrentUser] Profile $profile,
        CreateMessageUseCase   $useCase,
        CreateMessageRequest   $body,
    ): JsonResponse {
        $useCase->create($profile, $body->chatId, $body->content);
        return $this->json();
    }
}
