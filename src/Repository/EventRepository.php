<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findRecent()
    {
        $datetime = new \DateTime("now");

        return $this->createQueryBuilder('e')
        ->select('e', 'u.')
            ->andWhere('e.date_suppression > :date')
            ->orWhere('e.date_suppression is NULL')
            ->setParameter('date', $datetime)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
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
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPopular()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nb_participant', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function findOneById($id): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
