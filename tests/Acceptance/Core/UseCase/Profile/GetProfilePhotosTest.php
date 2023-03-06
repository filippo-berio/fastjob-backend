<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\Photo\GetProfilePhotosUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class GetProfilePhotosTest extends AcceptanceTest
{
    public function testProfileWithPhotos()
    {
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(GetProfilePhotosUseCase::class);
        $photos = $useCase->get($profile);
        $this->assertCount(count(ProfilePhotoFixtures::PROFILE_5_PHOTOS), $photos);
        foreach ($photos as $photo) {
            $this->assertContains($photo->getId(), ProfilePhotoFixtures::PROFILE_5_PHOTOS);
            $this->assertEquals($photo->getId() === ProfilePhotoFixtures::PROFILE_5_PHOTO_2, $photo->isMain());
        }
    }
}
