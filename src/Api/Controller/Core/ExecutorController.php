<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Executor\AcceptOfferRequest;
use App\Core\Application\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\Application\UseCase\Task\GetExecutorTasksUseCase;
use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/executor')]
class ExecutorController extends BaseController
{
    #[Route('/next/{taskId}', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile       $profile,
        GetSwipedNextExecutorUseCase $useCase,
        int $taskId,
    ): JsonResponse {
        $executor = $useCase->get($profile, $taskId);
        return $this->json($executor, context: ['category_short', 'profile_short', 'task_full']);
    }

    #[Route('/tasks', methods: ['GET'])]
    public function tasks(
        #[CurrentUser] Profile $profile,
        GetExecutorTasksUseCase $useCase,
    ): JsonResponse {
        return $this->json($useCase->get($profile), context: [
            'profile_short',
            'task_full',
            'category_short',
        ]);
    }

    #[Route('/accept-offer', methods: ['POST'])]
    public function acceptOffer(
        #[CurrentUser] Profile $profile,
        AcceptOfferRequest $body,
        AcceptOfferUseCase $useCase,
    ): JsonResponse {
        $tasks = $useCase->acceptOffer($profile, $body->taskId);
        return $this->json($tasks, context: [
            'profile_short',
            'task_full'
        ]);
    }
}
