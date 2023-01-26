<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\CQRS\QueryInterface;

class FindProfileByIdHandler extends BaseProfileQueryHandler
{
    /**
     * @param FindProfileById $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        /** @var Profile $profile */
        $profile = $this->entityManager->getRepository(Profile::class)->find($query->id);
        return $profile ? $this->fillProfile($profile) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileById::class;
    }
}
