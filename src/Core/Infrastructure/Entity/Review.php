<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Review as DomainReview;
use App\Core\Domain\Entity\Task as DomainTask;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('task_review')]
class Review extends DomainReview
{
    #[Id]
    #[Column]
    protected int $id;

    #[ManyToOne(targetEntity: Task::class)]
    protected DomainTask $task;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setAuthor(Profile $author): void
    {
        $this->author = $author;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function setTarget(Profile $target): void
    {
        $this->target = $target;
    }
}
