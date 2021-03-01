<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

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

    public function findRecentByDate()
    {
        $datetime = new \DateTime("now");

        return $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_suppression > :dateEventSupp')
            ->orWhere('e.date_suppression is NULL')
            ->setParameter('date', $datetime)
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(20)
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

    public function findRecent()
    {
        $datetime = new \DateTime("now");

        return $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.date_suppression > :dateEventSupp')
            ->orWhere('e.date_suppression is NULL')
            ->setParameter('date', $datetime)
            ->orderBy('e.id', 'DESC')
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

    public function findByLike($name)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
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
