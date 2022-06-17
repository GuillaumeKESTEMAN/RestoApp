<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class CommonRepository extends ServiceEntityRepository
{
    public function persist(object $object): void
    {
        $this->getEntityManager()->persist($object);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<Object>|null
     */
    public function getPreviousEntitiesById(int $idReference, int $nbrMaxResults = 1): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id < :id')
            ->orderBy('p.id', 'DESC')
            ->setParameter('id', $idReference)
            ->setMaxResults($nbrMaxResults);

        $query = $qb->getQuery();
        return $query->execute();
    }

    /**
     * @return array<Object>|null
     */
    public function getNextEntitiesById(int $idReference, int $nbrMaxResults = 1): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id > :id')
            ->orderBy('p.id', 'ASC')
            ->setParameter('id', $idReference)
            ->setMaxResults($nbrMaxResults);

        $query = $qb->getQuery();
        return $query->execute();
    }

    /**
     * @return array<Object>|null
     */
    public function getLastEntitiesById(int $nbrMaxResults = 1): ?array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($nbrMaxResults);

        $query = $qb->getQuery();
        return $query->execute();
    }
}