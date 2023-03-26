<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Executor\AcceptOfferRequest;
use App\Api\Request\Swipe\CreateTaskSwipeRequest;
use App\Core\Application\UseCase\Swipe\CreateTaskSwipeUseCase;
use App\Core\Application\UseCase\Task\GetExecutorTasksUseCase;
use App\Core\Application\UseCase\Task\GetProfileNextTaskUseCase;
use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/executor')]
class ExecutorController extends BaseController
{
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

    #[Route('/next-tasks', methods: ['GET'])]
    public function getNext(
        #[CurrentUser] Profile    $profile,
        GetProfileNextTaskUseCase $useCase,
    ): JsonResponse {
        $tasks = $useCase->get($profile);
        return $this->json($tasks, context: ['task_full', 'category_short', 'profile_short']);
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


    #[Route('/swipe', methods: ['POST'])]
    public function createTaskSwipe(
        #[CurrentUser] Profile $profile,
        CreateTaskSwipeUseCase $useCase,
        CreateTaskSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $nextTasks = $useCase->create($profile, $body->taskId, $body->type, $body->customPrice);
        return $this->json(
            [
                'success' => true,
                'next' => $nextTasks
            ],
            context: ['task_full', 'category_short', 'profile_short']
        );
    }
}
