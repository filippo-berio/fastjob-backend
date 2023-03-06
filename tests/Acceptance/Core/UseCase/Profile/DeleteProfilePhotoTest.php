<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\Photo\DeleteProfilePhotoUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class DeleteProfilePhotoTest extends AcceptanceTest
{
    public function testDeletePhoto()
    {
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(DeleteProfilePhotoUseCase::class);
        $photos = $useCase->delete($profile, ProfilePhotoFixtures::PROFILE_5_PHOTO_1);
        $this->assertCount(count(ProfilePhotoFixtures::PROFILE_5_PHOTOS) - 1, $photos);
        $this->assertTrue($photos[0]->isMain());
    }

    public function testDeleteMainPhoto()
    {
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(DeleteProfilePhotoUseCase::class);
        $photos = $useCase->delete($profile, ProfilePhotoFixtures::PROFILE_5_PHOTO_2);
        $this->assertCount(count(ProfilePhotoFixtures::PROFILE_5_PHOTOS) - 1, $photos);
        $this->assertTrue($photos[0]->isMain());
    }
}
