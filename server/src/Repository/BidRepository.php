<?php

namespace App\Repository;

use App\Entity\Bid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    private $manager;
    private $itemRepository;

    /**
     * BidRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ItemRepository $itemRepository
    ) {
        parent::__construct($registry, Bid::class);
        $this->manager = $manager;
        $this->itemRepository = $itemRepository;
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
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function saveBid(Bid $bid): Bid
    {
        $this->manager->getConnection()->beginTransaction();
        try {
            $item = $bid->getItem();
            $item->setBid($bid->getBid());
            $this->itemRepository->saveItem($item);
            $this->manager->persist($bid);
            $this->manager->flush();
            $this->manager->getConnection()->commit();
            return $bid;
        } catch (Exception $e) {
            $this->manager->getConnection()->rollBack();
            throw $e;
        }
    }
}
