<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\EmailNotificationTemplateRepository;
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
                $this->emailNotificationTemplateRepository
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
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        BidRepository $bidRepository,
        ItemRepository $itemRepository,
        UserRepository $userRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository, $emailNotificationTemplateRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->bidRepository = $bidRepository;
        $this->itemRepository = $itemRepository;
        $this->userRepository = $userRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
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

    /**
     * @param Bid $bid
     * @param bool $isAutoBid
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmailNotificationOnNewBid(Bid $bid, bool $isAutoBid) : void {
        $itemName = $bid->getItem()->getName();
        $bidOwnerFirstName = $bid->getUser()->getFirstName();
        $bidOwnerLastName = $bid->getUser()->getLastName();
        $users = $this->userRepository->findUsersByItem($bid->getItem());
        foreach ($users as $user) {
            if ($user instanceof User && ($user->getId() != $bid->getUser()->getId())) {
                $bodyParams = array(
                    '#recipientFirstName#' => $user->getFirstName(),
                    '#recipientLastName#' => $user->getLastName(),
                    '#itemName#' => $itemName,
                    '#bidOwnerFirstName#' => $bidOwnerFirstName,
                    '#bidOwnerLastName#' => $bidOwnerLastName,
                    '#bid#' => $bid->getBid(),
                    '#isAutoBid#' => $isAutoBid ? 'Yes' : 'No',
                    '#dateTime#' => $bid->getDateTime()->format('Y-m-d H:i')
                );
                $this->sendEmailNotification($user, BaseService::EMAIL_NOTIFICATION_ON_NEW_BID, $bodyParams);
            }
        }
    }
}
