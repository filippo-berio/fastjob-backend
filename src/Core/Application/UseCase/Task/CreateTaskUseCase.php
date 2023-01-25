<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Domain\DTO\Address\AddressPlain;
use App\Core\Domain\DTO\Task\CreateTaskDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Exception\Category\CategoryNotFoundException;
use App\Core\Domain\Service\Task\CreateTaskService;
use App\Core\Infrastructure\Repository\CategoryRepository;
use DateTimeImmutable;

class CreateTaskUseCase
{
    public function __construct(
        private CreateTaskService $createTaskService,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function create(
        Profile       $profile,
        string        $title,
        bool          $remote,
        array         $categoryIds = [],
        ?string       $description = null,
        ?int          $price = null,
        ?AddressPlain $addressPlain = null,
        ?string       $deadline = null,
    ): Task {
        $categories = empty($categoryIds) ? [] : $this->categoryRepository->findByIds($categoryIds);
        if (count($categories) !== count($categoryIds)) {
            throw new CategoryNotFoundException();
        }

        return $this->createTaskService->create(new CreateTaskDTO(
            $profile,
            $title,
            $remote,
            $categories,
            $description,
            $price,
            $addressPlain,
            $deadline ? new DateTimeImmutable($deadline) : null,
        ));
    }
}
