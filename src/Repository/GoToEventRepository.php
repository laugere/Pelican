<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\GoToEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

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

    public function findCommunityGoTo($userId)
    {
        return $this->createQueryBuilder('g')
            ->select('e')
            ->innerJoin(
                Event::class,    // Entity
                'e',               // Alias
                Join::WITH,        // Join type
                'g.idEvent = e.id' // Join columns
            )
            ->where('g.idUser = :id')
            ->setParameter('id', $userId)
            ->orderBy('e.date_creation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function userGoToEvent($userId, $eventId): bool
    {
        $qb = $this->createQueryBuilder('g')
            ->andWhere('g.idUser = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('g.idEvent = :idEvent')
            ->setParameter('idEvent', $eventId)
            ->getQuery()
            ->getOneOrNullResult();

        if ($qb != null) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteGoToEvent($userId, $eventId)
    {
        $qb =  $this->createQueryBuilder('g')
            ->delete(GoToEvent::class, 'g')
            ->andWhere('g.idUser = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('g.idEvent = :idEvent')
            ->setParameter('idEvent', $eventId)
            ->getQuery()
            ->execute();
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
