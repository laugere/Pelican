<?php

namespace App\Repository;

use App\Entity\GoToEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GoToEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoToEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoToEvent[]    findAll()
 * @method GoToEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoToEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoToEvent::class);
    }

    // /**
    //  * @return GoToEvent[] Returns an array of GoToEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoToEvent
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
