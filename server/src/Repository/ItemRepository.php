<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    private $manager;

    /**
     * ItemRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Item::class);
        $this->manager = $manager;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function findByParams(array $params)
    {
        $q = $this->createQueryBuilder('u');
        if (isset($params['filter']['name'])) {
            $q->andWhere('u.name LIKE :name')->setParameter('name', '%'.$params['filter']['name'].'%');
        }
        if (isset($params['filter']['description'])) {
            $q->andWhere('u.description LIKE :description')->setParameter('description', '%'.$params['filter']['description'].'%');
        }
        if (isset($params['limit'])) {
            $q->setMaxResults($params['limit']);
        }
        if (isset($params['offset'])) {
            $q->setFirstResult($params['offset']);
        }
        if (isset($params['sortField']) && isset($params['sortOrder'])) {
            $q->orderBy('u.'.$params['sortField'], $params['sortOrder']);
        }
        return $q->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function findItemsByUser(User $user) {
        $q = $this->createQueryBuilder('u')
            ->select('u')
            ->distinct()
            ->leftJoin('u.bids', 'b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user);
        return $q->getQuery()->getResult();
    }

    /**
     * @param Item $item
     * @return Item
     */
    public function saveItem(Item $item): Item
    {
        $this->manager->persist($item);
        $this->manager->flush();
        return $item;
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item) : void
    {
        $this->manager->remove($item);
        $this->manager->flush();
    }
}
