<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\ItemBillTemplate;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\ItemBillTemplateRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class ItemBillService
 * @package App\Service
 */
class ItemBillService extends BaseService
{
    private $accessTokenRepository;
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $itemRepository;
    private $bidRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $itemBillTemplateRepository;
    private $itemService;

    /**
     * ItemBillService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     * @param UserRepository $userRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     * @param ItemBillTemplateRepository $itemBillTemplateRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        BidRepository $bidRepository,
        UserRepository $userRepository,
        EmailNotificationTemplateRepository $emailNotificationTemplateRepository,
        EmailQueueRepository $emailQueueRepository,
        ConfigRepository $configRepository,
        ItemBillTemplateRepository $itemBillTemplateRepository
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
        $this->userRepository = $userRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
        $this->itemBillTemplateRepository = $itemBillTemplateRepository;
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
     * @param int $itemId
     * @return array
     */
    public function getItemBill(User $user, int $itemId) : array
    {
        $item = $this->getItemService()->getItem($itemId);
        $awardedUser = $item->getAwardedUser();
        if ($awardedUser instanceof User && $awardedUser->getId() == $user->getId()) {
            $itemBillTemplate = $this->itemBillTemplateRepository->findOneBy(array('name' => BaseService::ITEM_BILL_ITEM_AWARDED_BILL));
            if ($itemBillTemplate instanceof ItemBillTemplate) {
                return array(
                    'itemBillHtml' => $this->getItemBillHtml($itemBillTemplate, $item),
                    'fileName' => 'Bill of the item ' . $item->getName() . ' - ' . $user->getFirstName() . ' ' . $user->getLastName() . '.pdf'
                );
            } else {
                throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            }
        } else {
            throw new UnauthorizedHttpException(Response::$statusTexts[Response::HTTP_UNAUTHORIZED]);
        }
    }

    /**
     * @param ItemBillTemplate $itemBillTemplate
     * @param Item $item
     * @return string
     */
    public function getItemBillHtml(ItemBillTemplate $itemBillTemplate, Item $item) : string
    {
        $itemBillTemplate = $itemBillTemplate->getTemplate();
        $highestBid = $this->bidRepository->getHighestBidOfItem($item);
        $params = array(
            '#itemName#' => $item->getName(),
            '#itemOwnerFirstName#' => $item->getAwardedUser()->getFirstName(),
            '#itemOwnerLastName#' => $item->getAwardedUser()->getLastName(),
            '#winningBid#' => $highestBid->getBid(),
            '#dateTime#' => $highestBid->getDateTime()->format('Y-m-d H:i')
        );
        return $this->replaceStringWithParams($itemBillTemplate, $params);
    }
}
