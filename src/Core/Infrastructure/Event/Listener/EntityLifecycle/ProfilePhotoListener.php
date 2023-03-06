<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle;

use App\Core\Infrastructure\Entity\ProfilePhoto;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postLoad, entity: ProfilePhoto::class)]
#[AsEntityListener(event: Events::postPersist, entity: ProfilePhoto::class)]
class ProfilePhotoListener
{
    public function __construct(
        private string $storageEndpoint,
    ) {
    }

    public function __invoke(ProfilePhoto $photo, LifecycleEventArgs $args)
    {
        $photo->setPathPrefix($this->storageEndpoint);
    }
}
