<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\BaseService;
use App\Service\ItemService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class ItemController
 * @package App\Controller
 * @Route(path="/api")
 */
class ItemController extends BaseController
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
     * ItemController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     * @param UserRepository $userRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        BidRepository $bidRepository,
        UserRepository $userRepository,
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
        $this->itemRepository = $itemRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->bidRepository = $bidRepository;
        $this->userRepository = $userRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
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
     * @api {get} http://localhost:8001/api/items Items - Get
     * @apiDescription Get Items
     * @apiName getItems
     * @apiGroup ITEM
     * @apiSubGroup Item
     * @apiParam {String} accessToken - Access Token
     * @apiParam {String} [filter[name]] - Item Name
     * @apiParam {String} [filter[description]] - Item Description
     * @apiParam {Number} [limit] - Limit
     * @apiParam {Number} [offset] - Offset
     * @apiParam {String} [sortField] - Sort Field
     * @apiParam {String} [sortOrder] - Sort Order
     * @apiSampleRequest http://localhost:8001/api/items
     * @apiSuccess {Json} Object Object containing items data
     * @apiSuccessExample Success-Response:
     *  [
     *    {
     *      "id":1,
     *      "name":"Item 1",
     *      "description":"Description 1",
     *      "price":"1500.00",
     *      "bid":"1800.00",
     *      "closeDateTime":"2021-06-21 12:15",
     *      "isAutoBidEnabled":false
     *    }
     *  ]
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/items", name="getItems", methods={"GET"})
     */
    public function getItems(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_ITEM, BaseService::PERMISSION_TYPE_CAN_READ);
        $params = array(
            'filter' => $request->get('filter'),
            'limit' => $request->get('limit'),
            'offset' => $request->get('offset'),
            'sortField' => $request->get('sortField'),
            'sortOrder' => $request->get('sortOrder')
        );
        $items = $this->getItemService()->getItems($params);
        return new JsonResponse($this->getItemService()->formatItemsResponse($items, $accessToken), Response::HTTP_OK);
    }

    /**
     * @api {get} http://localhost:8001/api/items/:id Item - Get
     * @apiDescription Get Item
     * @apiName getItem
     * @apiGroup ITEM
     * @apiSubGroup Item
     * @apiParam {String} accessToken - Access Token
     * @apiSampleRequest http://localhost:8001/api/items/1
     * @apiSuccess {Json} Object Object containing item data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id":1,
     *    "name":"Item 1",
     *    "description":"Description 1",
     *    "price":"1500.00",
     *    "bid":"1800.00",
     *    "closeDateTime":"2021-06-21 12:15",
     *    "isAutoBidEnabled":false
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @Route("/items/{id}", name="getItem", methods={"GET"})
     */
    public function getItem(Request $request, int $id): JsonResponse
    {
        $this->validateGetResourceRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_ITEM, BaseService::PERMISSION_TYPE_CAN_READ);
        $item = $this->getItemService()->getItem($id);
        $this->getItemService()->checkStatusAndAwardItem($item);
        return new JsonResponse($this->getItemService()->formatItemResponse($item, $accessToken), Response::HTTP_OK);
    }

    /**
     * @api {post} http://localhost:8001/api/items Item - Post
     * @apiDescription Save Item
     * @apiName saveItem
     * @apiGroup ITEM
     * @apiSubGroup Item
     * @apiParam {Json} Object Object containing item data with access token
     * @apiSampleRequest http://localhost:8001/api/items
     * @apiParamExample {Json} Parameter Object-Example:
     *  {
     *    "name":"Item 1",
     *    "description":"Description 1",
     *    "price":"1500",
     *    "bid":"1800",
     *    "closeDateTime":"2021-06-20 16:20",
     *    "accessToken":"af874ho9s8dfush6"
     *  }
     * @apiSuccess {Json} Object Object containing item data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id":1,
     *    "name":"Item 1",
     *    "description":"Description 1",
     *    "price":"1500",
     *    "bid":"1800",
     *    "closeDateTime":"2021-06-20 16:20",
     *    "isAutoBidEnabled":false
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/items", name="saveItem", methods={"POST"})
     */
    public function saveItem(Request $request): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_ITEM, BaseService::PERMISSION_TYPE_CAN_CREATE);
        $item = $this->getItemService()->saveItem($params);
        return new JsonResponse($this->getItemService()->formatItemResponse($item, $params['accessToken']), Response::HTTP_CREATED);
    }

    /**
     * @api {put} http://localhost:8001/api/items/:id Item - Put
     * @apiDescription Update Item
     * @apiName updateItem
     * @apiGroup ITEM
     * @apiSubGroup Item
     * @apiParam {Json} Object Object containing item data with access token
     * @apiSampleRequest http://localhost:8001/api/items/1
     * @apiParamExample {Json} Parameter Object-Example:
     *  {
     *    "name":"Item 1",
     *    "description":"Description 1",
     *    "price":"1500",
     *    "bid":"1800",
     *    "closeDateTime":"2021-06-20 16:20",
     *    "accessToken":"af874ho9s8dfush6"
     *  }
     * @apiSuccess {Json} Object Object containing item data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id":1,
     *    "name":"Item 1",
     *    "description":"Description 1",
     *    "price":"1500",
     *    "bid":"1800",
     *    "closeDateTime":"2021-06-20 16:20",
     *    "isAutoBidEnabled":false
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \WebSocket\BadOpcodeException
     * @Route("/items/{id}", name="updateItem", methods={"PUT"})
     */
    public function updateItem(Request $request, int $id): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_ITEM, BaseService::PERMISSION_TYPE_CAN_UPDATE);
        $item = $this->getItemService()->updateItem($params, $id);
        $this->getEventPublisher()->publishToWS($id, "Item {$id} Updated");
        $this->getItemService()->checkStatusAndAwardItem($item);
        return new JsonResponse($this->getItemService()->formatItemResponse($item, $params['accessToken']), Response::HTTP_OK);
    }

    /**
     * @api {delete} http://localhost:8001/api/items/:id Item - Delete
     * @apiDescription Delete Item
     * @apiName deleteItem
     * @apiGroup ITEM
     * @apiSubGroup Item
     * @apiParam {String} accessToken - Access Token
     * @apiSampleRequest http://localhost:8001/api/items/1
     * @apiSuccess (204) NoContent
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/items/{id}", name="deleteItem", methods={"DELETE"})
     */
    public function deleteItem(Request $request, int $id): JsonResponse
    {
        $this->validateDeleteRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_ITEM, BaseService::PERMISSION_TYPE_CAN_DELETE);
        $this->getItemService()->deleteItem($id);
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $filterValidator = v::keySet(
            v::key('name', v::stringVal()->notEmpty(), false),
            v::key('description', v::stringVal()->notEmpty(), false)
        );
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('filter', $filterValidator, false),
            v::key('limit', v::intVal()->positive(), false),
            v::key('offset', v::intVal()->not(v::negative()), false),
            v::key('sortField', v::in(array('price')), false),
            v::key('sortOrder', v::in(array('ASC','DESC')), false)
        );
        $this->validate($validator, $request->query->all());
    }

    /**
     * @param Request $request
     */
    protected function validateGetResourceRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true)
        );
        $this->validate($validator, $request->query->all());
    }

    /**
     * @param Request $request
     */
    protected function validatePostRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('name', v::stringVal()->notEmpty(), true),
            v::key('description', v::stringVal(), true),
            v::key('price', v::anyOf(v::intVal()->positive(), v::decimal(2)), true),
            v::key('bid', v::anyOf(v::intVal()->positive(), v::decimal(2)), true),
            v::key('closeDateTime', v::dateTime('Y-m-d H:i'), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }

    /**
     * @param Request $request
     */
    protected function validateDeleteRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true)
        );
        $this->validate($validator, $request->query->all());
    }
}
