<?php

namespace App\Repository;

use App\Entity\DataGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataGroup[]    findAll()
 * @method DataGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataGroup::class);
    }

    // /**
    //  * @return DataGroup[] Returns an array of DataGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataGroup
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
