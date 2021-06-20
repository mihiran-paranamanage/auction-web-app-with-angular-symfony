<?php

namespace App\Service;

use App\Entity\Bid;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRoleDataGroupRepository;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class BidService
 * @package App\Service
 */
class BidService extends BaseService
{
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
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
                $this->bidRepository
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
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        BidRepository $bidRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->bidRepository = $bidRepository;
        $this->itemRepository = $itemRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
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
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function saveBid(array $params) : Bid {
        $user = $this->getUser($params['accessToken']);
        $item = $this->getItemService()->getItem($params['itemId']);
        $highestBid = $this->bidRepository->getHighestBidOfItem($item);
        $currentDateTime = DateTime::createFromFormat('Y-m-d H:i', date("Y-m-d H:i"));
        if ($item->getCloseDateTime() < $currentDateTime) {
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
            'itemId' => $bid->getItem()->getId(),
            'bid' => $bid->getBid(),
            'isAutoBid' => $bid->getIsAutoBid(),
            'dateTime' => $bid->getDateTime()->format('Y-m-d H:i'),
            'user' => array(
                'userId' => $bid->getUser()->getId(),
                'username' => $bid->getUser()->getUsername(),
            )
        );
    }
}
