<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Swipe\CreateExecutorSwipeRequest;
use App\Api\Request\Swipe\CreateTaskSwipeRequest;
use App\Core\Application\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\Core\Application\UseCase\Swipe\CreateTaskSwipeUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/swipe')]
class SwipeController extends BaseController
{
    #[Route('/executor', methods: ['POST'])]
    public function createExecutorSwipe(
        #[CurrentUser] Profile     $profile,
        CreateExecutorSwipeUseCase $useCase,
        CreateExecutorSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $executorSwipe = $useCase->create($profile, $body->taskId, $body->executorId, $body->type);
        return $this->json(
            [
                'success' => true,
                'next' => $executorSwipe
            ],
            context: ['task_short', 'profile_short']
        );
    }

    #[Route('/task', methods: ['POST'])]
    public function createTaskSwipe(
        #[CurrentUser] Profile $profile,
        CreateTaskSwipeUseCase $useCase,
        CreateTaskSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $nextTask = $useCase->create($profile, $body->taskId, $body->type, $body->customPrice);
        return $this->json(
            [
                'success' => true,
                'next' => $nextTask
            ],
            context: ['task_full', 'category_short', 'profile_short']
        );
    }
}
