<?php

namespace App\Core\Infrastructure\Service;

use App\Core\Domain\Contract\ProfilePhotoStorageInterface;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;
use App\Core\Infrastructure\Entity\ProfilePhoto as InfrastructureProfilePhoto;
use App\Core\Infrastructure\Repository\ProfilePhotoRepository;
use App\Storage\Service\StorageInterface;

class ProfilePhotoStorage implements ProfilePhotoStorageInterface
{
    public function __construct(
        private StorageInterface $storage,
        private ProfilePhotoRepository $photoRepository,
    ) {
    }

    public function getForProfile(Profile $profile): array
    {
        return $this->photoRepository->findForProfile($profile);
    }

    public function store(Profile $profile, string $file): ProfilePhoto
    {
        $existing = $this->getForProfile($profile);
        $path = $this->buildPath($profile);
        $this->storage->storeFile($path, $file);
        $photo = new InfrastructureProfilePhoto($profile, $path, empty($existing));
        $this->photoRepository->save($photo);
        return $photo;
    }

    public function delete(ProfilePhoto $profilePhoto)
    {
        if ($profilePhoto->isMain()) {
            $photos = $this->getForProfile($profilePhoto->getProfile());
            foreach ($photos as $otherPhoto) {
                if ($otherPhoto->getId() !== $profilePhoto->getId()) {
                    $otherPhoto->setMain(true);
                    break;
                }
            }
        }

        $this->photoRepository->delete($profilePhoto);
        $this->storage->deleteFile($profilePhoto->getPath());
    }

    private function buildPath(Profile $profile): string
    {
        return 'profile_photos/' . $profile->getId() . '/' . uniqid();
    }
}
