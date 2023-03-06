<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle;

use App\Core\Infrastructure\Entity\ProfilePhoto;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, entity: ProfilePhoto::class)]
class ProfilePhotoPrePersistListener
{
    public function __construct(
        private string $storageEndpoint,
    ) {
    }

    public function __invoke(ProfilePhoto $photo)
    {
        $photo->removePathPrefix($this->storageEndpoint);
    }
}
