<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Application\UseCase\Category\GetCategoryTreeUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends BaseController
{
    #[Route('/tree', methods: ['GET'])]
    public function tree(GetCategoryTreeUseCase $useCase): JsonResponse
    {
        return $this->json($useCase->get(), context: [
            'category_full'
        ]);
    }
}
