<?php

namespace App\Core\Controller;

use App\Core\DTO\Request\Task\AcceptTaskRequest;
use App\Core\Security\UserInterface;
use App\Core\UseCase\Task\Accept\AcceptTaskUseCase;
use App\Core\UseCase\Task\Reject\RejectUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('task')]
class TaskController extends BaseController
{
    #[Route('/{taskId}/reject', requirements: ['taskId' => '\d+'], methods: ['POST'])]
    public function reject(
        int $taskId,
        #[CurrentUser] UserInterface $user,
        RejectUseCase $useCase,
    ): JsonResponse {
        $useCase($user->getProfile(), $taskId);
        return $this->json([
            'success' => true
        ], 200, $this->makeResponseTokenHeaders($user));
    }

    #[Route('/{taskId}/accept', requirements: ['taskId' => '\d+'], methods: ['POST'])]
    public function accept(
        int                          $taskId,
        #[CurrentUser] UserInterface $user,
        AcceptTaskUseCase            $useCase,
        AcceptTaskRequest            $body,
    ): JsonResponse {
        $useCase($user->getProfile(), $taskId, $body->customPrice);
        return $this->json([
            'success' => true
        ], 200, $this->makeResponseTokenHeaders($user));
    }
}
