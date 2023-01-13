<?php

namespace App\Core\Data\Query\RefreshToken\FindByUser;

use App\Core\Entity\RefreshToken;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindRefreshTokenByUserHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?RefreshToken
    {
        /** @var FindRefreshTokenByUser $query */
        return $this->em->getRepository(RefreshToken::class)
            ->findOneBy([
                'user' => $query->user
            ]);
    }
}
