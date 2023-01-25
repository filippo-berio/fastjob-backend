<?php

namespace App\Core\Domain\Service\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\DTO\Task\CreateTaskDTO;
use App\Core\Domain\Entity\Task;
use App\CQRS\Bus\CommandBusInterface;
use App\Location\UseCase\Address\CreateAddressUseCase;
use App\Validation\ValidatorInterface;

class CreateTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private CreateAddressUseCase $createAddressUseCase,
        private ValidatorInterface $validator,
        private EntityMapperInterface $entityMapper,
    ) {
    }

    public function create(CreateTaskDTO $createTaskDTO): Task
    {
        $this->validator->validate($createTaskDTO);

        $address = $createTaskDTO->addressPlain ? $this->createAddressUseCase->create(
            $createTaskDTO->addressPlain->cityId,
            $createTaskDTO->addressPlain->title,
        ) : null;

        $entity = $this->entityMapper->persistenceEntity(Task::class);
        $task = new $entity(
            $createTaskDTO->title,
            $createTaskDTO->profile,
            $createTaskDTO->categories,
            $createTaskDTO->remote,
            $createTaskDTO->price,
            $address,
            $createTaskDTO->deadline,
            $createTaskDTO->description,
        );

        return $this->commandBus->execute(new SaveTask($task));
    }
}
