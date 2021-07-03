<?php

namespace App\Repository;

use App\Entity\Bid;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\UserBidConfig;
use App\Utility\AutoBidManager;
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
    private $userRepository;
    private $autoBidManager;

    /**
     * BidRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     * @param ItemRepository $itemRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager,
        ItemRepository $itemRepository,
        UserBidConfigRepository $userBidConfigRepository,
        UserRepository $userRepository
    ) {
        parent::__construct($registry, Bid::class);
        $this->manager = $manager;
        $this->itemRepository = $itemRepository;
        $this->userBidConfigRepository = $userBidConfigRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return AutoBidManager
     */
    public function getAutoBidManager() : AutoBidManager {
        if (!($this->autoBidManager instanceof AutoBidManager)) {
            $this->autoBidManager = new AutoBidManager(
                $this->userRepository,
                $this->manager,
                $this->userBidConfigRepository,
                $this->itemRepository,
                $this
            );
        }
        return $this->autoBidManager;
    }

    /**
     * @param AutoBidManager $autoBidManager
     */
    public function setAutoBidManager(AutoBidManager $autoBidManager) {
        $this->autoBidManager = $autoBidManager;
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
//        $this->manager->getConnection()->beginTransaction();
        try {
            $item = $bid->getItem();
            $item->setBid($bid->getBid());
            $this->itemRepository->saveItem($item);

            $userBidConfig = $this->userBidConfigRepository->findOneBy(array('user' => $bid->getUser()));
            if (!($userBidConfig instanceof UserBidConfig)) {
                $userBidConfig = new UserBidConfig();
                $userBidConfig->setUser($bid->getUser());
                $userBidConfig->setMaxBidAmount(0);
                $userBidConfig->setCurrentBidAmount(0);
                $userBidConfig->setNotifyPercentage(100);
                $userBidConfig->setIsAutoBidEnabled(0);
            }
            $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);

            $this->manager->persist($bid);
            $this->manager->flush();

            $this->getAutoBidManager()->autoBid($bid);
//            $this->manager->getConnection()->commit();
            return $bid;
        } catch (Exception $e) {
//            $this->manager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $itemId
     * @return int|mixed|string
     */
    public function removeByItemId(int $itemId)
    {
        $q = $this->createQueryBuilder('u')
            ->delete('App:Bid', 'u')
            ->where('u.item = :itemId')
            ->setParameter('itemId', $itemId);
        return $q->getQuery()->execute();
    }

    /**
     * @param Item $item
     * @return int|mixed|string
     */
    public function getHighestBidOfItem(Item $item)
    {
        $q = $this->createQueryBuilder('u')
            ->andWhere('u.item = :item')
            ->setParameter('item', $item)
            ->orderBy('u.bid', 'DESC')
            ->setMaxResults(1);
        $result = $q->getQuery()->getResult();
        return count($result) > 0 ? $result[0] : null;
    }

    /**
     * @param User $user
     * @param Item $item
     * @return mixed|null
     */
    public function getLatestBidByUserAndItem(User $user, Item $item)
    {
        $q = $this->createQueryBuilder('u')
            ->andWhere('u.user = :user')
            ->setParameter('user', $user)
            ->andWhere('u.item = :item')
            ->setParameter('item', $item)
            ->orderBy('u.dateTime', 'DESC')
            ->setMaxResults(1);
        $result = $q->getQuery()->getResult();
        return count($result) > 0 ? $result[0] : null;
    }
}
