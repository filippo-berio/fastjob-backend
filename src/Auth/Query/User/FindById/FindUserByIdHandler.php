<?php

namespace App\Auth\Query\User\FindById;

use App\Auth\Entity\User;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindUserByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): mixed
    {
        /** @var FindUserById $query */
        return $this->em->getRepository(User::class)->find($query->id);
    }
}
