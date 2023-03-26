<?php

namespace App\DataFixtures\Chat;

use App\Chat\Entity\DirectChat;
use App\Core\Infrastructure\Entity\Profile;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\Core\ProfileFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DirectChatFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const CHAT_1 = 1;

    protected function getEntity(): string
    {
        return DirectChat::class;
    }

    public function load(ObjectManager $manager)
    {
        $profile15 = $this->getReference(ProfileFixtures::PROFILE_15, Profile::class);
        $profile16 = $this->getReference(ProfileFixtures::PROFILE_16, Profile::class);

        $this->save([
            $chat1 = new DirectChat($profile15, $profile16)
        ], $manager);

        $this->addReference(self::CHAT_1, $chat1);
    }

    public function getDependencies()
    {
        return [
            ProfileFixtures::class
        ];
    }
}
