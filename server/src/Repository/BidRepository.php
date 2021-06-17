<?php

namespace App\Repository;

use App\Entity\Bid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    private $manager;

    /**
     * BidRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Bid::class);
        $this->manager = $manager;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function findByParams(array $params)
    {
        $q = $this->createQueryBuilder('u');
        if (isset($params['filter']['itemId'])) {
            $q->andWhere('u.item = :itemId')->setParameter('itemId', $params['filter']['itemId']);
        }
        return $q->getQuery()->getResult();
    }

    /**
     * @param Bid $bid
     * @return Bid
     */
    public function saveBid(Bid $bid): Bid
    {
        $this->manager->persist($bid);
        $this->manager->flush();
        return $bid;
    }
}
