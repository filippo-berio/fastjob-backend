<?php

namespace App\Review\Infrastructure\Entity;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\ReviewAvailability as DomainReviewAvailability;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;

#[Document]
class ReviewAvailability extends DomainReviewAvailability
{
    #[Id]
    protected string $id;

    #[Field]
    protected string $authorId;

    #[Field]
    protected string $targetId;

    #[Field]
    protected bool $allowComment;

    public function __construct(Profile $author, Profile $target)
    {
        parent::__construct($author, $target);
        $this->authorId = $author->getId();
        $this->targetId = $target->getId();
    }
}
