<?php

namespace App\Core\Application\UseCase\Profile\Photo;

use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;

class UploadProfilePhotoUseCase
{
    public function __construct(
        private ProfilePhotoStorageInterface $photoStorage
    ) {
    }

    public function upload(Profile $profile, string $file, string $extension): ProfilePhoto
    {
        return $this->photoStorage->store($profile, $file);
    }
}
