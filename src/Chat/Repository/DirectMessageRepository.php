<?php

namespace App\Chat\Repository;

use App\Chat\Entity\DirectMessage;
use Doctrine\ORM\EntityManagerInterface;

class DirectMessageRepository implements DirectMessageRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(DirectMessage $message): DirectMessage
    {
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $message;
    }
}
