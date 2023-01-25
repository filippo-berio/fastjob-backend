<?php

namespace App\Core\Infrastructure\Command\Profile;

use App\Core\Domain\Command\Profile\SaveProfile;
use App\Core\Domain\Entity\Profile;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveProfileHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param SaveProfile $command
     * @return Profile
     */
    public function handle(CommandInterface $command): Profile
    {
        $this->em->persist($command->profile);
        $this->em->flush();
        return $command->profile;
    }

    public function getCommandClass(): string
    {
        return SaveProfile::class;
    }
}
