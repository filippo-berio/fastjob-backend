<?php

namespace App\Core\Data\Query\Profile\FindProfileById;

use App\Core\Entity\Profile;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByIdHandler implements QueryHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?Profile
    {
        /** @var FindProfileById $query */
        return $this->em->getRepository(Profile::class)->find($query->id);
    }
}
