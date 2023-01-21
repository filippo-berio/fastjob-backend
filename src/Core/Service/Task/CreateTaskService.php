<?php

namespace App\Core\Service\Task;

use App\Core\Command\Task\Save\SaveTask;
use App\Core\DTO\Task\CreateTaskDTO;
use App\Core\Entity\Task;
use App\CQRS\Bus\CommandBusInterface;
use App\Location\UseCase\Address\CreateAddressUseCase;
use App\Validation\ValidatorInterface;

class CreateTaskService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private CreateAddressUseCase $createAddressUseCase,
        private ValidatorInterface $validator,
    ) {
    }

    public function create(CreateTaskDTO $createTaskDTO): Task
    {
        $this->validator->validate($createTaskDTO);

        $address = $createTaskDTO->addressPlain ? $this->createAddressUseCase->create(
            $createTaskDTO->addressPlain->cityId,
            $createTaskDTO->addressPlain->title,
        ) : null;

        $task = new Task(
            $createTaskDTO->title,
            $createTaskDTO->profile,
            $createTaskDTO->categories,
            $createTaskDTO->price,
            $address,
            $createTaskDTO->deadline,
            $createTaskDTO->description,
        );

        return $this->commandBus->handle(new SaveTask($task));
    }
}
