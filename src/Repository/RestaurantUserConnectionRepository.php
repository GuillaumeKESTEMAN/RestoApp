<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RestaurantUserConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RestaurantUserConnection>
 *
 * @method RestaurantUserConnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantUserConnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantUserConnection[]    findAll()
 * @method RestaurantUserConnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantUserConnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantUserConnection::class);
    }

    public function add(RestaurantUserConnection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RestaurantUserConnection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
