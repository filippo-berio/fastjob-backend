<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\ProfilePhoto as DomainProfilePhotos;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class ProfilePhoto extends DomainProfilePhotos
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    protected ?int $id = null;

    #[ManyToOne(targetEntity: Profile::class)]
    protected DomainProfile $profile;

    #[Column(type: 'smallint')]
    protected bool $main;

    public function __construct(DomainProfile $profile, string $path, bool $main = false)
    {
        $this->profile = $profile;
        $this->main = $main;
        $this->path = $path;
    }

    public function setPathPrefix(string $prefix)
    {
        $this->path = "$prefix$this->path";
    }
}
