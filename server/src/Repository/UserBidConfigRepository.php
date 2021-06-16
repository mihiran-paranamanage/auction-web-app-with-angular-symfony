<?php

namespace App\Repository;

use App\Entity\UserBidConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBidConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBidConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBidConfig[]    findAll()
 * @method UserBidConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBidConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBidConfig::class);
    }

    // /**
    //  * @return UserBidConfig[] Returns an array of UserBidConfig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserBidConfig
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
