<?php

namespace App\Core\Application\UseCase\Profile\Photo;

use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;

class GetProfilePhotosUseCase
{
    public function __construct(
        private ProfilePhotoStorageInterface $photoStorage,
    ) {
    }

    /**
     * @param Profile $profile
     * @return ProfilePhoto[]
     */
    public function get(Profile $profile): array
    {
        return $this->photoStorage->getForProfile($profile);
    }
}
