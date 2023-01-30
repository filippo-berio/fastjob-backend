<?php

namespace App\Review\Infrastructure\Entity;

use App\Core\Domain\Entity\Profile as CoreProfile;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\Review as DomainReview;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[Entity]
#[HasLifecycleCallbacks]
class Review extends DomainReview
{
    #[Id]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[Column(type: UuidType::NAME, unique: true)]
    #[CustomIdGenerator('doctrine.uuid_generator')]
    protected Uuid $uuid;

    #[ManyToOne(targetEntity: CoreProfile::class)]
    protected CoreProfile $doctrineAuthor;

    #[ManyToOne(targetEntity: CoreProfile::class)]
    protected CoreProfile $doctrineTarget;

    #[Column]
    protected int $rating;

    #[Column(nullable: true)]
    protected ?string $comment = null;

    #[PostLoad]
    public function init()
    {
        $this->id = $this->uuid->toRfc4122();
        $this->author = new Profile($this->doctrineAuthor->getId());
        $this->target = new Profile($this->doctrineTarget->getId());
    }
}
