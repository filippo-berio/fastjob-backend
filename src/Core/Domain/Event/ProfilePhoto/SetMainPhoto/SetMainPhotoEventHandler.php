<?php

namespace App\Core\Domain\Event\ProfilePhoto\SetMainPhoto;

use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Event\EventHandlerInterface;
use App\Core\Domain\Event\EventInterface;
use App\Core\Domain\Repository\ProfilePhotoRepositoryInterface;

class SetMainPhotoEventHandler implements EventHandlerInterface
{
    public function __construct(
        private ProfilePhotoStorageInterface $photoStorage,
        private ProfilePhotoRepositoryInterface $photoRepository,
    ) {
    }

    public function executionType(): string
    {
        return self::EXECUTION_TYPE_NOW;
    }

    public function event(): string
    {
        return SetMainPhotoEvent::class;
    }

    /**
     * @param SetMainPhotoEvent $event
     * @return void
     */
    public function handle(EventInterface $event)
    {
        $profilePhotos = $this->photoStorage->getForProfile($event->photo->getProfile());
        foreach ($profilePhotos as $photo) {
            if ($photo->getId() !== $event->photo->getId()) {
                $photo->setMain(false);
            }
        }
        $this->photoRepository->save($event->photo);
    }
}
