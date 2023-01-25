<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByIdHandler implements QueryHandlerInterface
{
    use FillUserTrait;

    public function __construct(
        private EntityManagerInterface $em,
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param FindProfileById $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        /** @var Profile $profile */
        $profile = $this->em->getRepository(Profile::class)->find($query->id);
        return $profile ? $this->fillUser($profile, $this->queryBus) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileById::class;
    }
}
