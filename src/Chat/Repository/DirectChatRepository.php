<?php

namespace App\Chat\Repository;

use App\Chat\Entity\DirectChat;
use App\Chat\Entity\PersonInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DirectChatRepository implements DirectChatRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function getForPerson(PersonInterface $person): array
    {
        $qb = $this->em->getRepository(DirectChat::class)->createQueryBuilder('dc');

        return $this
            ->joinPerson($qb, $person)
            ->getQuery()
            ->getResult();
    }

    public function getForCompanions(PersonInterface $personA, PersonInterface $personB): ?DirectChat
    {
        $qb = $this->em->getRepository(DirectChat::class)->createQueryBuilder('dc');

        $this->joinPerson($qb, $personA);
        $this->joinPerson($qb, $personB);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function find(int $id): ?DirectChat
    {
        return $this->em->getRepository(DirectChat::class)->find($id);
    }

    public function save(DirectChat $chat): DirectChat
    {
        $this->em->persist($chat);
        $this->em->flush();
        return $chat;
    }

    private function joinPerson(QueryBuilder $qb, PersonInterface $person): QueryBuilder
    {
        return $qb
            ->andWhere('identity(dc.personA) = :id or identity(dc.personB) = :id')
            ->setParameter('id', $person->getId());
    }
}
