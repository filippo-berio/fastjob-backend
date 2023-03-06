<?php

namespace App\Core\Application\UseCase\Profile\Photo;

use App\Core\Application\Exception\NotFoundException;
use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;
use App\Core\Domain\Repository\ProfilePhotoRepositoryInterface;

class DeleteProfilePhotoUseCase
{
    public function __construct(
        private ProfilePhotoStorageInterface $photoStorage,
        private ProfilePhotoRepositoryInterface $photoRepository,
        private GetProfilePhotosUseCase $getProfilePhotosUseCase,
    ) {
    }

    /**
     * @param Profile $profile
     * @param int $photoId
     * @return ProfilePhoto[]
     */
    public function delete(Profile $profile, int $photoId): array
    {
        $photo = $this->photoRepository->find($profile, $photoId);
        if (!$photo) {
            throw new NotFoundException();
        }

        $this->photoStorage->delete($photo);

        return $this->getProfilePhotosUseCase->get($profile);
    }
}
