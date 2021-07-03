<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param Item $item
     * @return int|mixed|string
     */
    public function findUsersByItem(Item $item) {
        $q = $this->createQueryBuilder('u')
            ->select('u')
            ->distinct()
            ->leftJoin('u.bids', 'b')
            ->andWhere('b.item = :item')
            ->setParameter('item', $item);
        return $q->getQuery()->getResult();
    }
}
