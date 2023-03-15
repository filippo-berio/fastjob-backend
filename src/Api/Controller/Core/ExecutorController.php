<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Api\Request\Executor\AcceptOfferRequest;
use App\Core\Application\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\Application\UseCase\Executor\SuggestNextExecutorUseCase;
use App\Core\Application\UseCase\Task\GetExecutorTasksUseCase;
use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/executor')]
class ExecutorController extends BaseController
{
    #[Route('/next', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile       $profile,
        GetSwipedNextExecutorUseCase $useCase,
    ): JsonResponse {
        $executor = $useCase->get($profile);
        return $this->json($executor, context: ['category_short', 'profile_short', 'task_full']);
    }

    #[Route('/next/suggest', methods: ['GET'])]
    public function suggestNext(
        #[CurrentUser] Profile       $profile,
        SuggestNextExecutorUseCase   $useCase,
    ): JsonResponse {
        $executor = $useCase->suggest($profile);
        return $this->json([
            'next' => $executor,
        ], context: []);
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
