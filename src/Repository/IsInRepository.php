<?php

namespace App\Repository;

use App\Entity\Community;
use App\Entity\IsIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method IsIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method IsIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method IsIn[]    findAll()
 * @method IsIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IsInRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IsIn::class);
    }

    public function findRecent($userId)
    {
        return $this->createQueryBuilder('i')
            ->select('c.name')
            ->innerJoin(
                Community::class,    // Entity
                'c',               // Alias
                Join::WITH,        // Join type
                'i.idCommunity = c.id' // Join columns
            )
            ->where('i.idUser = :id')
            ->setParameter('id', $userId)
            ->orderBy('c.date_creation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return IsIn[] Returns an array of IsIn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IsIn
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
