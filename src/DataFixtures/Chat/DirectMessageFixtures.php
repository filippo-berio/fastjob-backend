<?php

namespace App\DataFixtures\Chat;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\DirectMessage;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DirectMessageFixtures extends BaseFixtures implements DependentFixtureInterface
{

    protected function getEntity(): string
    {
        return DirectMessage::class;
    }

    public function getDependencies()
    {
        return [
            DirectChatFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $chat1 = $this->getReference(DirectChatFixtures::CHAT_1, DirectChat::class);

        $this->save([
            new DirectMessage($chat1, 'Привет!', $chat1->getPersonA()),
            new DirectMessage($chat1, 'пошел нахуй', $chat1->getPersonB()),
        ], $manager);
    }
}
