<?php

namespace App\DataFixtures\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Purger\PurgerInterface;
use Doctrine\ORM\EntityManagerInterface;

class PurgerFactory implements \Doctrine\Bundle\FixturesBundle\Purger\PurgerFactory
{
    public function createForEntityManager(
        ?string                $emName,
        EntityManagerInterface $em,
        array                  $excluded = [],
        bool                   $purgeWithTruncate = false
    ): PurgerInterface {
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        foreach ($metadatas as $metadata) {
            if (!$metadata->isMappedSuperclass) {
                $tbl = $metadata->getQuotedTableName($em->getConnection()->getDatabasePlatform());

                $em->getConnection()->executeStatement('alter sequence if exists ' . $tbl . '_id_seq restart with 1;');
            }
        }
        return new ORMPurger($em, $excluded);
    }
}
