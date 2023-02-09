<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Core\Domain\Entity\TaskOffer as DomainTaskOffer;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
#[Index(['status'])]
#[Index(['created_at'])]
class TaskOffer extends DomainTaskOffer
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    protected ?int $id = null;

    #[ManyToOne(targetEntity: Task::class)]
    protected DomainTask $task;

    #[ManyToOne(targetEntity: Profile::class)]
    protected DomainProfile $profile;

    #[Column]
    protected string $status;

    #[Column]
    protected \DateTimeImmutable $createdAt;
}
