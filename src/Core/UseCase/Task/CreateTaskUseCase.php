<?php

namespace App\Core\UseCase\Task;

use App\Core\DTO\Address\AddressPlain;
use App\Core\DTO\Task\CreateTaskDTO;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Exception\Category\CategoryNotFoundException;
use App\Core\Query\Category\FindByIds\FindCategoriesByIds;
use App\Core\Service\Task\CreateTaskService;
use App\CQRS\Bus\QueryBusInterface;
use DateTimeImmutable;

class CreateTaskUseCase
{
    public function __construct(
        private CreateTaskService $createTaskService,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function create(
        Profile $profile,
        string $title,
        bool $remote,
        array $categoryIds = [],
        ?string $description = null,
        ?int $price = null,
        ?AddressPlain $addressPlain = null,
        ?string $deadline = null,
    ): Task {
        $categories = empty($categoryIds) ? [] : $this->queryBus->query(new FindCategoriesByIds($categoryIds));
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
