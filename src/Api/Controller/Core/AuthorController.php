<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Author\CreateExecutorSwipeRequest;
use App\Api\Request\Author\FinishTaskRequest;
use App\Core\Application\UseCase\Author\GetSwipedNextExecutorUseCase;
use App\Core\Application\UseCase\Swipe\CreateExecutorSwipeUseCase;
use App\Core\Application\UseCase\Task\FinishTaskUseCase;
use App\Core\Application\UseCase\Task\GetProfileTasksUseCase;
use App\Core\Infrastructure\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/author')]
class AuthorController extends BaseController
{
    #[Route('/executors', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile $profile,
        Request $request,
        GetSwipedNextExecutorUseCase $useCase,
    ): JsonResponse {
        $taskSwipes = $useCase->get($profile, $request->get('taskId'));
        return $this->json($taskSwipes, context: ['task_swipe_short', 'category_short', 'profile_short', 'task_full']);
    }

    #[Route('/tasks', methods: ['GET'])]
    public function getTasks(
        #[CurrentUser] Profile $profile,
        GetProfileTasksUseCase $useCase,
    ): JsonResponse {
        return $this->json(
            $useCase->get($profile),
            context: [
                'task_full',
                'task_private',
                'profile_short',
                'task_offer_short',
                'match',
                'category_short',
            ]
        );
    }

    #[Route('/finish-task', methods: ['POST'])]
    public function finishTask(
        #[CurrentUser] Profile $profile,
        FinishTaskUseCase $useCase,
        FinishTaskRequest $body
    ): JsonResponse {
        return $this->json(
            $useCase->finish(
                $profile,
                $body->taskId,
                $body->rating,
                $body->comment
            ),
            context: [
                'task_full',
                'task_private',
                'profile_short',
                'task_offer_short',
                'match',
            ]
        );
    }

    #[Route('/swipe', methods: ['POST'])]
    public function createExecutorSwipe(
        #[CurrentUser] Profile $profile,
        CreateExecutorSwipeUseCase $useCase,
        CreateExecutorSwipeRequest $body,
    ): JsonResponse {
        $this->validator->validate($body);
        $nextExecutor = $useCase->create($profile, $body->taskId, $body->executorId, $body->type);
        return $this->json(
            [
                'success' => true,
                'next' => $nextExecutor
            ],
            context: ['task_swipe_short', 'category_short', 'profile_short', 'task_full']
        );
    }
}
