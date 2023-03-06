<?php

namespace App\Core\Application\UseCase\Profile\Photo;

use App\Core\Application\Exception\NotFoundException;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;
use App\Core\Domain\Repository\ProfilePhotoRepositoryInterface;

class MakeProfilePhotoMainUseCase
{
    public function __construct(
        private ProfilePhotoRepositoryInterface $photoRepository,
        private GetProfilePhotosUseCase $getProfilePhotosUseCase,
    ) {
    }

    /**
     * @param Profile $profile
     * @param int $profilePhotoId
     * @return ProfilePhoto[]
     */
    public function set(Profile $profile, int $profilePhotoId): array
    {
        $profilePhoto = $this->photoRepository->find($profile, $profilePhotoId);
        if (!$profilePhoto) {
            throw new NotFoundException('Фото не найдено');
        }

        $profilePhoto->setMain(true);

        return $this->getProfilePhotosUseCase->get($profile);
    }
}
