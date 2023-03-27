<?php

namespace App\Core\Application\UseCase\Profile\Photo;

use App\Core\Domain\Contract\ImageNsfwFilterInterface;
use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;
use App\Core\Domain\Exception\Photo\ImageNsfwException;

class UploadProfilePhotoUseCase
{
    public function __construct(
        private ProfilePhotoStorageInterface $photoStorage,
        private ImageNsfwFilterInterface $imageNsfwFilter,
    ) {
    }

    public function upload(Profile $profile, string $file): ProfilePhoto
    {
        if ($this->imageNsfwFilter->isImageNsfw($file)) {
            throw new ImageNsfwException();
        }
        return $this->photoStorage->store($profile, $file);
    }
}
