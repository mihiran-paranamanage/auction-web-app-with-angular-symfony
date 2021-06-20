<?php

namespace App\Utility;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\UserBidConfig;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use App\Repository\UserBidConfigRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class AutoBidManager
 * @package App\Utility
 */
class AutoBidManager
{
    private $userRepository;
    private $manager;
    private $userBidConfigRepository;
    private $itemRepository;
    private $bidRepository;

    /**
     * AutoBidManager constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param UserBidConfigRepository $userBidConfigRepository
     * @param ItemRepository $itemRepository
     * @param BidRepository $bidRepository
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        UserBidConfigRepository $userBidConfigRepository,
        ItemRepository $itemRepository,
        BidRepository $bidRepository
    ) {
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->userBidConfigRepository = $userBidConfigRepository;
        $this->itemRepository = $itemRepository;
        $this->bidRepository = $bidRepository;
    }

    /**
     * @param Bid $bid
     * @throws Exception
     */
    public function autoBid(Bid $bid) {
        $autoBid = null;
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $userBidConfig = $user->getUserBidConfigs()->first();
            if ($userBidConfig instanceof UserBidConfig) {
                $maxBidAmount = $userBidConfig->getMaxBidAmount();
                $currentBidAmount = $userBidConfig->getCurrentBidAmount();
            } else {
                $maxBidAmount = 0;
                $currentBidAmount = 0;
            }
            if ($this->canPerformAutoBid($user, $bid, $currentBidAmount, $maxBidAmount)) {
                $autoBid = new Bid();
                $autoBid->setUser($user);
                $autoBid->setItem($bid->getItem());
                $autoBid->setBid($bid->getBid() + 1);
                $autoBid->setIsAutoBid(true);
                $autoBid->setDateTime(DateTime::createFromFormat('Y-m-d H:i', date("Y-m-d H:i")));
                break;
            }
        }
        if ($autoBid instanceof Bid) {
            $this->saveBid($autoBid);
        }
    }

    /**
     * @param User $user
     * @param Bid $bid
     * @param int $currentBidAmount
     * @param int $maxBidAmount
     * @return bool
     */
    protected function canPerformAutoBid(User $user, Bid $bid, int $currentBidAmount, int $maxBidAmount): bool {
        $latestBid = $this->bidRepository->getLatestBidByUserAndItem($user, $bid->getItem());
        $isAutoBidEnabled = $latestBid instanceof Bid && $latestBid->getIsAutoBid();
        return ($user->getId() != $bid->getUser()->getId()) && $isAutoBidEnabled && ($currentBidAmount < $maxBidAmount);
    }

    /**
     * @param Bid $bid
     * @return Bid
     * @throws Exception
     */
    protected function saveBid(Bid $bid): Bid
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
            }
            $currentBidAmount = $userBidConfig->getCurrentBidAmount();
            $userBidConfig->setCurrentBidAmount($currentBidAmount + 1);
            $this->userBidConfigRepository->saveUserBidConfig($userBidConfig);

            $this->manager->persist($bid);
            $this->manager->flush();

            $this->autoBid($bid);
//            $this->manager->getConnection()->commit();
            return $bid;
        } catch (Exception $e) {
//            $this->manager->getConnection()->rollBack();
            throw $e;
        }
    }
}
