<?php

namespace App\Review\Infrastructure\Entity;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review as DomainReview;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PostLoad;

#[Entity]
#[HasLifecycleCallbacks]
class Review extends DomainReview
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    protected int $id;

    #[Column]
    protected int $authorId;

    #[Column]
    protected int $targetId;

    #[Column]
    protected int $rating;

    #[Column(nullable: true)]
    protected ?string $comment = null;

    #[PostLoad]
    public function init()
    {
        $this->author = new Profile($this->authorId);
        $this->target = new Profile($this->targetId);
    }

    public function __construct(Profile $author, Profile $target, int $rating, ?string $comment = null)
    {
        parent::__construct($author, $target, $rating, $comment);
        $this->authorId = $author->getId();
        $this->targetId = $target->getId();
    }
}
