<?php

namespace App\Auth\Query\User\FindById;

use App\Auth\Entity\User;
use App\Lib\CQRS\QueryHandlerInterface;
use App\Lib\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindUserByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindUserById $query
     * @return User|null
     */
    public function handle(QueryInterface $query): ?User
    {
        /** @var FindUserById $query */
        return $this->em->getRepository(User::class)->find($query->id);
    }

    public function getQueryClass(): string
    {
        return FindUserById::class;
    }
}
