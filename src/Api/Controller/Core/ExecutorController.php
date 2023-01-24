<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Entity\Profile;
use App\Core\UseCase\Task\NextSwipedExecutorUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('executor')]
class ExecutorController extends BaseController
{
    #[Route('/next-task', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile $profile,
        Request $request,
        NextSwipedExecutorUseCase $useCase,
    ): JsonResponse {
        return $this->json($tasks, context: ['task_full']);
    }
}
