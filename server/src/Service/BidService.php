<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class BidService
 * @package App\Service
 */
class BidService extends BaseService
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $itemService;

    /**
     * @return ItemService
     */
    public function getItemService() : ItemService {
        if (!($this->itemService instanceof ItemService)) {
            $this->itemService = new ItemService(
                $this->accessTokenRepository,
                $this->itemRepository,
                $this->userRoleDataGroupRepository,
                $this->bidRepository,
                $this->userRepository,
                $this->emailNotificationTemplateRepository,
                $this->emailQueueRepository,
                $this->configRepository
            );
        }
        return $this->itemService;
    }

    /**
     * @param ItemService $itemService
     */
    public function setItemService(ItemService $itemService) {
        $this->itemService = $itemService;
    }

    /**
     * BidService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param BidRepository $bidRepository
     * @param ItemRepository $itemRepository
     * @param UserRepository $userRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        BidRepository $bidRepository,
        ItemRepository $itemRepository,
        UserRepository $userRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository,
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository
    ) {
        parent::__construct(
            $accessTokenRepository,
            $userRoleDataGroupRepository,
            $emailNotificationTemplateRepository,
            $this->emailQueueRepository = $emailQueueRepository,
            $this->configRepository = $configRepository
        );
        $this->accessTokenRepository = $accessTokenRepository;
        $this->bidRepository = $bidRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @param array $params
     * @param User $user
     * @return array
     */
    public function getBids(array $params, User $user) : array
    {
        return $this->bidRepository->findByParams($params, $user);
    }

    /**
     * @param array $params
     * @return Bid
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function saveBid(array $params) : Bid {
        $user = $this->getUser($params['accessToken']);
        $item = $this->getItemService()->getItem($params['itemId']);
        $highestBid = $this->bidRepository->getHighestBidOfItem($item);
        $currentDateTime = DateTime::createFromFormat('Y-m-d H:i', date("Y-m-d H:i"));
        if ($item->getIsClosed() || $item->getCloseDateTime() <= $currentDateTime) {
            throw new BadRequestHttpException('Bid is closed');
        }
        if ($params['bid'] <= $item->getBid()) {
            throw new BadRequestHttpException('Bid should be higher than the item bid');
        }
        if ($highestBid instanceof Bid && $user->getId() == $highestBid->getUser()->getId()) {
            throw new BadRequestHttpException('Already have the highest bid for the item');
        }
        $bid = new Bid();
        $bid->setUser($user);
        $bid->setItem($item);
        $bid->setBid($params['bid']);
        $bid->setIsAutoBid(!!$params['isAutoBid']);
        $bid->setDateTime($currentDateTime);
        return $this->bidRepository->saveBid($bid);
    }

    /**
     * @param array $bids
     * @return array
     */
    public function formatBidsResponse(array $bids) : array
    {
        $bidsArr = array();
        foreach ($bids as $bid) {
            if ($bid instanceof Bid) {
                $bidsArr[] = $this->formatBidResponse($bid);
            }
        }
        return $bidsArr;
    }

    /**
     * @param Bid $bid
     * @return array
     */
    public function formatBidResponse(Bid $bid) : array
    {
        return array(
            'id' => $bid->getId(),
            'userId' => $bid->getUser()->getId(),
            'username' => $bid->getUser()->getUsername(),
            'itemId' => $bid->getItem()->getId(),
            'itemName' => $bid->getItem()->getName(),
            'bid' => $bid->getBid(),
            'isAutoBid' => $bid->getIsAutoBid(),
            'dateTime' => $bid->getDateTime()->format('Y-m-d H:i')
        );
    }

    /**
     * @param Bid $bid
     * @param bool $isAutoBid
     */
    public function pushNewBidNotificationToEmailQueue(Bid $bid, bool $isAutoBid) : void
    {
        $itemName = $bid->getItem()->getName();
        $bidOwnerFirstName = $bid->getUser()->getFirstName();
        $bidOwnerLastName = $bid->getUser()->getLastName();
        $users = $this->userRepository->findUsersByItem($bid->getItem());
        foreach ($users as $user) {
            if ($user instanceof User && ($user->getId() != $bid->getUser()->getId())) {
                $params = array(
                    '#recipientFirstName#' => $user->getFirstName(),
                    '#recipientLastName#' => $user->getLastName(),
                    '#itemName#' => $itemName,
                    '#bidOwnerFirstName#' => $bidOwnerFirstName,
                    '#bidOwnerLastName#' => $bidOwnerLastName,
                    '#bid#' => $bid->getBid(),
                    '#isAutoBid#' => $isAutoBid ? 'Yes' : 'No',
                    '#dateTime#' => $bid->getDateTime()->format('Y-m-d H:i')
                );
                $this->pushNotificationToEmailQueue($user, BaseService::EMAIL_NOTIFICATION_ON_NEW_BID, $params);
            }
        }
    }
}
