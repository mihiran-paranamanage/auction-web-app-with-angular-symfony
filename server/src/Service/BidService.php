<?php

namespace App\Service;

use App\Entity\Bid;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use DateTime;

/**
 * Class BidService
 * @package App\Service
 */
class BidService extends BaseService
{
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
    private $itemService;

    /**
     * @return ItemService
     */
    public function getItemService() : ItemService {
        if (!($this->itemService instanceof ItemService)) {
            $this->itemService = new ItemService($this->accessTokenRepository, $this->itemRepository);
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
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        BidRepository $bidRepository,
        ItemRepository $itemRepository
    ) {
        parent::__construct($accessTokenRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->bidRepository = $bidRepository;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getBids(array $params) : array
    {
        return $this->bidRepository->findByParams($params);
    }

    /**
     * @param array $params
     * @return Bid
     */
    public function saveBid(array $params) : Bid {
        $bid = new Bid();
        $user = $this->getUser($params['accessToken']);
        $bid->setUser($user);
        $item = $this->getItemService()->getItem($params['itemId']);
        $bid->setItem($item);
        $bid->setBid($params['bid']);
        $bid->setIsAutoBid(!!$params['isAutoBid']);
        $bid->setDateTime(DateTime::createFromFormat('Y-m-d H:i', date("Y-m-d H:i")));
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
            'itemId' => $bid->getItem()->getId(),
            'bid' => $bid->getBid(),
            'isAutoBid' => $bid->getIsAutoBid(),
            'dateTime' => $bid->getDateTime()
        );
    }
}