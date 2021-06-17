<?php

namespace App\Repository;

use App\Entity\Bid;
use App\Entity\UserBidConfig;
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
    private $userBidConfigRepository;

    /**
     * BidRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     * @param ItemRepository $itemRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ItemRepository $itemRepository,
        UserBidConfigRepository $userBidConfigRepository
    ) {
        parent::__construct($registry, Bid::class);
        $this->manager = $manager;
        $this->itemRepository = $itemRepository;
        $this->userBidConfigRepository = $userBidConfigRepository;
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

            $userBidConfig = $this->userBidConfigRepository->findOneBy(array('user' => $bid->getUser()));
            if (!($userBidConfig instanceof UserBidConfig)) {
                $userBidConfig = new UserBidConfig();
                $userBidConfig->setUser($bid->getUser());
                $userBidConfig->setMaxBidAmount($bid->getBid());
            }
            $userBidConfig->setIsAutoBidEnabled($bid->getIsAutoBid());
            $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);

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
