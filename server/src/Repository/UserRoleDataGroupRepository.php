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
    /**
     * UserRoleDataGroupRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRoleDataGroup::class);
    }
}
