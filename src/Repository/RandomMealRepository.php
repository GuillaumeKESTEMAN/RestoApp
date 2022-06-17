<?php

namespace App\Repository;

use App\Entity\RandomMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RandomMeal>
 *
 * @method RandomMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method RandomMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method RandomMeal[]    findAll()
 * @method RandomMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RandomMealRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RandomMeal::class);
    }

    public function add(RandomMeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RandomMeal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
