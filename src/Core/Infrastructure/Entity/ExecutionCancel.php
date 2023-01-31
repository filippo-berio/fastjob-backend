<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\ExecutionCancel as DomainExecutionCancel;
use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Task as DomainTask;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class ExecutionCancel extends DomainExecutionCancel
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[ManyToOne(Profile::class)]
    protected DomainProfile $executor;

    #[ManyToOne(Task::class)]
    protected DomainTask $task;

    #[Column(type: 'smallint')]
    protected bool $forced;
}
