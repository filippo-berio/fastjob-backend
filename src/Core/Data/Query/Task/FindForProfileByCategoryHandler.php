<?php

namespace App\Core\Data\Query\Task;

use App\Core\Entity\Category;
use App\Core\Entity\Task;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindForProfileByCategoryHandler implements QueryHandlerInterface
{
    use ProfileUnseenTaskHandlerTrait;

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param QueryInterface $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        /** @var FindForProfileByCategory $query */
        $qb = $this->em->getRepository(Task::class)->createQueryBuilder('t');
        $this->applyProfileUnseenTaskQuery($qb, $query->profile);
        return $qb
            ->innerJoin('t.categories', 'c')
            ->andWhere('c.id in (:id)')
            ->setParameter('id', array_map(
                fn(Category $category) => $category->getId(),
                $query->profile->getCategories()
            ))
            ->getQuery()
            ->getResult();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}
