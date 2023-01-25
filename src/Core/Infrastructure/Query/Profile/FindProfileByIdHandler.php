<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByIdHandler implements QueryHandlerInterface
{

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindProfileById $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        return $this->em->getRepository(Profile::class)->find($query->id);
    }

    public function getQueryClass(): string
    {
        return FindProfileById::class;
    }
}
