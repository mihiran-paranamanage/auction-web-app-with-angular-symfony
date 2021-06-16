<?php

namespace App\Repository;

use App\Entity\UserRoleDataGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserRoleDataGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRoleDataGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRoleDataGroup[]    findAll()
 * @method UserRoleDataGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoleDataGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRoleDataGroup::class);
    }

    // /**
    //  * @return UserRoleDataGroup[] Returns an array of UserRoleDataGroup objects
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
    public function findOneBySomeField($value): ?UserRoleDataGroup
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
