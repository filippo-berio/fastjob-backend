<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Core\Domain\Entity\TaskSwipe as DomainTaskSwipe;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[UniqueConstraint(columns: [
    'task_id',
    'profile_id'
])]
#[Index(['type'])]
class TaskSwipe extends DomainTaskSwipe
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['task_swipe_short'])]
    protected ?int $id = null;

    #[ManyToOne(targetEntity: Task::class)]
    protected DomainTask $task;

    #[ManyToOne(targetEntity: Profile::class)]
    protected DomainProfile $profile;

    #[Column(nullable: true)]
    protected ?int $customPrice = null;

    #[Column]
    protected string $type;

}
