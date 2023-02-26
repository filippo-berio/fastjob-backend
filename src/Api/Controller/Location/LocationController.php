<?php

namespace App\Api\Controller\Location;

use App\Api\Controller\BaseController;
use App\Location\UseCase\City\GetCitiesUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/location')]
class LocationController extends BaseController
{
    #[Route('/city', methods: ['GET'])]
    public function cities(
        GetCitiesUseCase $useCase
    ): JsonResponse {
        return $this->json($useCase->findAll());
    }
}
