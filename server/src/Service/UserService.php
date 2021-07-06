<?php

namespace App\Service;

use App\Controller\UserController;
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

/**
 * Class UserService
 * @package App\Service
 */
class UserService extends BaseService
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $itemRepository;
    private $bidRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $itemService;

    /**
     * UserService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param UserRepository $userRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        BidRepository $bidRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository,
        UserRepository $userRepository,
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
        $this->itemRepository = $itemRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->bidRepository = $bidRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
        $this->userRepository = $userRepository;
    }

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
     * @param User $user
     * @param array $includeParams
     * @return array
     */
    public function formatUserDetailsResponse(User $user, array $includeParams) : array
    {
        $userDetails = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'userRoleId' => $user->getUserRole()->getId(),
            'userRoleName' => $user->getUserRole()->getName(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        );
        if (in_array(UserController::INCLUDE_BIDS, $includeParams)) {
            $userDetails['bids'] = $this->getUserBids($user);
        }
        if (in_array(UserController::INCLUDE_AWARDED_ITEMS, $includeParams)) {
            $userDetails['awardedItems'] = $this->getUserAwardedItems($user);
        }
        return $userDetails;
    }

    /**
     * @param User $user
     * @return array
     */
    protected function getUserBids(User $user) : array
    {
        $userBids = array();
        $bids = $user->getBids();
        foreach ($bids as $bid) {
            $userBid = array();
            $userBid['id'] = $bid->getId();
            $userBid['userId'] = $bid->getUser()->getId();
            $userBid['username'] = $bid->getUser()->getUsername();
            $userBid['itemId'] =$bid->getItem()->getId();
            $userBid['itemName'] = $bid->getItem()->getName();
            $userBid['itemStatus'] = $this->getItemService()->getItemStatus($bid->getItem(), $user);
            $userBid['itemCloseDateTime'] = $bid->getItem()->getCloseDateTime()->format('Y-m-d H:i');
            $userBid['bid'] = $bid->getBid();
            $userBid['isAutoBid'] = $bid->getIsAutoBid();
            $userBid['dateTime'] = $bid->getDateTime()->format('Y-m-d H:i');
            $userBids[] = $userBid;
        }
        return $userBids;
    }

    /**
     * @param User $user
     * @return array
     */
    protected function getUserAwardedItems(User $user) : array
    {
        $awardedItems = array();
        $items = $user->getItems();
        foreach ($items as $item) {
            $awardedItem = array();
            $awardedItem['id'] = $item->getId();
            $awardedItem['name'] = $item->getName();
            $awardedItem['description'] = $item->getDescription();
            $awardedItem['price'] = $item->getPrice();
            $awardedItem['bid'] = $item->getBid();
            $awardedItem['closeDateTime'] = $item->getCloseDateTime()->format('Y-m-d H:i');
            $awardedItem['isClosed'] = $item->getIsClosed();
            $awardedItem['isAwardNotified'] = $item->getIsAwardNotified();
            $highestBid = $this->bidRepository->getHighestBidOfItem($item);
            if ($highestBid instanceof Bid) {
                $awardedItem['winningBidId'] = $highestBid->getId();
                $awardedItem['winningBid'] = $highestBid->getBid();
                $awardedItem['winningBidIsAutoBid'] = $highestBid->getIsAutoBid();
                $awardedItem['winningBidDateTime'] = $highestBid->getDateTime()->format('Y-m-d H:i');
            }
            $awardedItems[] = $awardedItem;
        }
        return $awardedItems;
    }

    /**
     * @param array $params
     * @return User
     */
    public function updateUserDetails(array $params) : User {
        $user = $this->getUser($params['accessToken']);
        $user->setFirstName($params['firstName']);
        $user->setLastName($params['lastName']);
        $user->setEmail($params['email']);
        if (isset($params['password'])) {
            $user->setPassword(md5($params['password']));
        }
        return $this->userRepository->saveUser($user);
    }
}
