<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\ItemBillTemplateRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\BaseService;
use App\Service\ItemBillService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class ItemBillController
 * @package App\Controller
 * @Route(path="/api")
 */
class ItemBillController extends BaseController
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $itemRepository;
    private $bidRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $itemBillTemplateRepository;
    private $configRepository;
    private $itemBillService;
    private $snappy;

    /**
     * ItemBillController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     * @param UserRepository $userRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     * @param ItemBillTemplateRepository $itemBillTemplateRepository
     * @param Pdf $snappy
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
        ItemBillTemplateRepository $itemBillTemplateRepository,
        Pdf $snappy
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
        $this->snappy = $snappy;
    }

    /**
     * @return ItemBillService
     */
    public function getItemBillService() : ItemBillService {
        if (!($this->itemBillService instanceof ItemBillService)) {
            $this->itemBillService = new ItemBillService(
                $this->accessTokenRepository,
                $this->itemRepository,
                $this->userRoleDataGroupRepository,
                $this->bidRepository,
                $this->userRepository,
                $this->emailNotificationTemplateRepository,
                $this->emailQueueRepository,
                $this->configRepository,
                $this->itemBillTemplateRepository
            );
        }
        return $this->itemBillService;
    }

    /**
     * @param ItemBillService $itemBillService
     */
    public function setItemBillService(ItemBillService $itemBillService) {
        $this->itemBillService = $itemBillService;
    }

    /**
     * @param Request $request
     * @return PdfResponse
     * @Route("/items/downloadBill", name="downloadItemBill", methods={"GET"})
     */
    public function downloadItemBill(Request $request): PdfResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $itemId = $request->get('itemId');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_ITEM_BILL, BaseService::PERMISSION_TYPE_CAN_READ);
        $user = $this->getUser($accessToken);
        $itemBill = $this->getItemBillService()->getItemBill($user, $itemId);
        return new PdfResponse($this->snappy->getOutputFromHtml($itemBill['itemBillHtml']), $itemBill['fileName']);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('itemId', v::intVal()->positive(), true)
        );
        $this->validate($validator, $request->query->all());
    }
}
