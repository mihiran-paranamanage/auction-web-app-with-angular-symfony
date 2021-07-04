<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\Item;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRoleDataGroupRepository;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ItemService
 * @package App\Service
 */
class ItemService extends BaseService
{
    const ITEM_STATUS_IN_PROGRESS = 'In progress';
    const ITEM_STATUS_WON = 'Won';
    const ITEM_STATUS_LOST = 'Lost';

    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $itemRepository;
    private $bidRepository;

    /**
     * ItemService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        BidRepository $bidRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository, $emailNotificationTemplateRepository);
        $this->itemRepository = $itemRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->bidRepository = $bidRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getItems(array $params) : array
    {
        return $this->itemRepository->findByParams($params);
    }

    /**
     * @param int $id
     * @return Item
     */
    public function getItem(int $id): Item
    {
        $item = $this->itemRepository->findOneBy(array('id' => $id));
        if ($item instanceof Item) {
            return $item;
        } else {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @param array $params
     * @return Item
     */
    public function saveItem(array $params) : Item {
        $item = new Item();
        $item->setName($params['name']);
        $item->setDescription($params['description']);
        $item->setPrice($params['price']);
        $item->setBid($params['bid']);
        $item->setCloseDateTime(DateTime::createFromFormat('Y-m-d H:i', $params['closeDateTime']));
        $item->setIsClosed(0);
        $item->setAwardedUser(null);
        $item->setIsAwardNotified(0);
        return $this->itemRepository->saveItem($item);
    }

    /**+
     * @param array $params
     * @param int $id
     * @return Item
     */
    public function updateItem(array $params, int $id) : Item {
        $item = $this->getItem($id);
        $item->setName($params['name']);
        $item->setDescription($params['description']);
        $item->setPrice($params['price']);
        $item->setBid($params['bid']);
        $item->setCloseDateTime(DateTime::createFromFormat('Y-m-d H:i', $params['closeDateTime']));
        return $this->itemRepository->saveItem($item);
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteItem(int $id) : void {
//        $this->manager->getConnection()->beginTransaction();
        try {
            $item = $this->getItem($id);
            $this->bidRepository->removeByItemId($id);
            $this->itemRepository->removeItem($item);
//            $this->manager->getConnection()->commit();
        } catch (Exception $e) {
//            $this->manager->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param array $items
     * @param string $accessToken
     * @return array
     */
    public function formatItemsResponse(array $items, string $accessToken) : array
    {
        $itemsArr = array();
        foreach ($items as $item) {
            if ($item instanceof Item) {
                $itemsArr[] = $this->formatItemResponse($item, $accessToken);
            }
        }
        return $itemsArr;
    }

    /**
     * @param Item $item
     * @param string $accessToken
     * @return array
     */
    public function formatItemResponse(Item $item, string $accessToken) : array
    {
        $latestBid = $this->bidRepository->getLatestBidByUserAndItem($this->getUser($accessToken), $item);
        return array(
            'id' => $item->getId(),
            'name' => $item->getName(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'bid' => $item->getBid(),
            'closeDateTime' => $item->getCloseDateTime()->format('Y-m-d H:i'),
            'isAutoBidEnabled' => $latestBid instanceof Bid ? $latestBid->getIsAutoBid() : false
        );
    }

    /**
     * @param Item $item
     * @param User $user
     * @return string
     */
    public function getItemStatus(Item $item, User $user) : string
    {
        if ($item->getIsClosed()) {
            return ($item->getAwardedUser()->getId() == $user->getId()) ? self::ITEM_STATUS_WON : self::ITEM_STATUS_LOST;
        } else {
            return self::ITEM_STATUS_IN_PROGRESS;
        }
    }

    /**
     * Awarding task for all the items
     */
    public function awardItemsIfClosed() : void
    {
        $items = $this->itemRepository->findAll();
        foreach ($items as $item) {
            $this->awardItemIfClosed($item);
        }
    }

    /**
     * Awarding task for the items
     * @param Item $item
     */
    public function awardItemIfClosed(Item $item) : void
    {
        $currentDateTime = DateTime::createFromFormat('Y-m-d H:i', date("Y-m-d H:i"));
        if (!$item->getIsClosed() && $item->getCloseDateTime() < $currentDateTime) {
            $item->setIsClosed(true);
            $highestBid = $this->bidRepository->getHighestBidOfItem($item);
            if ($highestBid instanceof Bid) {
                $item->setAwardedUser($highestBid->getUser());
                $item->setIsAwardNotified(true);
                // Todo notify
            } else {
                $item->setAwardedUser(null);
                $item->setIsAwardNotified(false);
            }
            $this->itemRepository->saveItem($item);
        }
    }
}
