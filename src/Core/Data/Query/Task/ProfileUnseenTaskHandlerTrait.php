<?php

namespace App\Core\Data\Query\Task;

use App\Core\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

trait ProfileUnseenTaskHandlerTrait
{
    protected function applyProfileUnseenTaskQuery(QueryBuilder $qb, Profile $profile): QueryBuilder
    {
        $em = $this->getEntityManager();
        $ids = $em->getConnection()->prepare("
            select t.id from task t
            inner join task_response tr on tr.task_id = t.id
            where tr.responder_id = :profileId
        ")->executeQuery([
            'profileId' => $profile->getId()
        ])->fetchFirstColumn();
        return $qb
            ->andWhere('t.id not in (:excludeId)')
            ->setParameter('excludeId', $ids);
    }

    protected abstract function getEntityManager(): EntityManagerInterface;
}
