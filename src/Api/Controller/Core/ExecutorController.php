<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Application\UseCase\Executor\GetSwipedNextExecutorUseCase;
use App\Core\Application\UseCase\Executor\SuggestNextExecutorUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/executor')]
class ExecutorController extends BaseController
{
    #[Route('/next/swiped', methods: ['GET'])]
    public function nextTask(
        #[CurrentUser] Profile       $profile,
        GetSwipedNextExecutorUseCase $useCase,
    ): JsonResponse {
        $executor = $useCase->get($profile);
        return $this->json([
            'next' => $executor,
        ], context: []);
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
}
