<?php

namespace App\Repository;

use App\Entity\Community;
use App\Entity\IsIn;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findCommunityGoTo($userId)
    {
        return $this->createQueryBuilder('i')
            ->select('c.id', 'c.name')
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

    public function userIsIn($userId, $communityId): bool
    {
        $qb = $this->createQueryBuilder('i')
            ->andWhere('i.idUser = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('i.idCommunity = :communityId')
            ->setParameter('communityId', $communityId)
            ->getQuery()
            ->getOneOrNullResult();

        if ($qb != null) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteIsIn($userId, $communityId)
    {
        $qb =  $this->createQueryBuilder('i')
            ->delete(IsIn::class, 'i')
            ->andWhere('i.idUser = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('i.idCommunity = :communityId')
            ->setParameter('communityId', $communityId)
            ->getQuery()
            ->execute();
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
