<?php

namespace App\DataFixtures\Core;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\ProfilePhoto;
use App\DataFixtures\Core\Stubs\EventDispatcherStub;
use Doctrine\Persistence\ObjectManager;

class ProfilePhotoFixtures extends \App\DataFixtures\BaseFixtures implements \Doctrine\Common\DataFixtures\DependentFixtureInterface
{
    const FILE_PATH = '/app/tests/Stubs/Files/ProfilePhoto/profile-photo.';
    const TEST_PATH = '/app/var/cache/test/profile-photo';
    const ACTUAL_PATH = '/app/var/storage/profile-photo';

    const PROFILE_5_PHOTO_1 = 1;
    const PROFILE_5_PHOTO_2 = 2;

    const PROFILE_5_PHOTOS = [
        self::PROFILE_5_PHOTO_1,
        self::PROFILE_5_PHOTO_2,
    ];

    protected function getEntity(): string
    {
        return ProfilePhoto::class;
    }

    public function getDependencies()
    {
        return [
            ProfileFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $profile5 = $this->getReference(ProfileFixtures::PROFILE_5, Profile::class);

        $photo2 = new ProfilePhoto($profile5, 'profile-photo2.png');
        $photo2->setEventDispatcher(new EventDispatcherStub());
        $photo2->setMain(true);

        $this->save([
            new ProfilePhoto($profile5, 'profile-photo1.jpg'),
            $photo2,
        ], $manager);

        copy(self::FILE_PATH . 'jpg', self::TEST_PATH . '1.jpg');
        copy(self::FILE_PATH . 'jpg', self::ACTUAL_PATH . '1.jpg');
        copy(self::FILE_PATH . 'png', self::TEST_PATH . '2.png');
        copy(self::FILE_PATH . 'png', self::ACTUAL_PATH . '2.png');
    }
}
