<?php

namespace App\Core\Application\UseCase\Category;

use App\Core\Domain\Repository\CategoryRepositoryInterface;

class GetCategoryTreeUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function get(): array
    {
        return $this->categoryRepository->getTree();
    }
}
