<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Entity\Profile;
use App\Core\UseCase\Executor\GetSwipedNextExecutorUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/executor')]
class ExecutorController extends BaseController
{
    #[Route('/next/swiped', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile $profile,
        GetSwipedNextExecutorUseCase $useCase,
    ): JsonResponse {
        $executor = $useCase->get($profile);
        return $this->json([
            'next' => $executor,
        ], context: []);
    }
}
