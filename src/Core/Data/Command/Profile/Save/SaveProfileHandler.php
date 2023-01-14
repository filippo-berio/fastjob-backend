<?php

namespace App\Core\Data\Command\Profile\Save;

use App\Core\Entity\Profile;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveProfileHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): Profile
    {
        /** @var SaveProfile $command */
        $this->em->persist($command->profile);
        $this->em->flush();
        return $command->profile;
    }
}
