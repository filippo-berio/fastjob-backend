<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\ExecutorSwipe as DomainExecutorSwipe;
use App\Core\Domain\Entity\Task as DomainTask;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[UniqueConstraint(columns: [
    'task_id',
    'profile_id'
])]
class ExecutorSwipe extends DomainExecutorSwipe
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['executor_swipe_short'])]
    protected ?int $id = null;

    #[ManyToOne(targetEntity: Task::class)]
    protected DomainTask $task;

    #[ManyToOne(targetEntity: Profile::class)]
    protected DomainProfile $profile;

    #[Column]
    protected string $type;
}
