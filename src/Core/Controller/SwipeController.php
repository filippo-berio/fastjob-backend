<?php

namespace App\Core\Controller;

use App\Core\DTO\Request\Swipe\CreateExecutorSwipeRequest;
use App\Core\DTO\Request\Swipe\CreateTaskSwipeRequest;
use App\Core\Entity\User;
use App\Core\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\Core\UseCase\Swipe\CreateTaskSwipeUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/swipe')]
class SwipeController extends BaseController
{
    #[Route('/executor', methods: ['POST'])]
    public function createExecutorSwipe(
        #[CurrentUser] User $user,
        CreateExecutorSwipeUseCase $useCase,
        CreateExecutorSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $executorSwipe = $useCase($user->getProfile(), $body->taskId, $body->executorId, $body->type);
        return $this->json(
            [
                'success' => true,
                'data' => $executorSwipe
            ],
            headers: $this->makeResponseTokenHeaders($user),
            context: ['executor_swipe_short']
        );
    }

    #[Route('/task', methods: ['POST'])]
    public function createTaskSwipe(
        #[CurrentUser] User $user,
        CreateTaskSwipeUseCase $useCase,
        CreateTaskSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $taskSwipe = $useCase($user->getProfile(), $body->taskId, $body->type, $body->customPrice);
        return $this->json(
            [
                'success' => true,
                'data' => $taskSwipe
            ],
            headers: $this->makeResponseTokenHeaders($user),
            context: ['task_swipe_short']
        );
    }
}
