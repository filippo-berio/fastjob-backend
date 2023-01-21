<?php

namespace App\Location\Command\Address\Save;

use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use App\Location\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;

class SaveAddressHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param SaveAddress $command
     * @return Address
     */
    public function handle(CommandInterface $command): Address
    {
        $this->entityManager->persist($command->address);
        $this->entityManager->flush();
        return $command->address;
    }
}
