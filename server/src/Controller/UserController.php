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
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="/api")
 */
class UserController extends BaseController
{
    const INCLUDE_ITEMS = 'items';
    const INCLUDE_BIDS = 'bids';
    const INCLUDE_AWARDED_ITEMS = 'awardedItems';

    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
    private $userRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $userService;

    /**
     * UserController constructor.
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
     * @return UserService
     */
    public function getUserService() : UserService {
        if (!($this->userService instanceof UserService)) {
            $this->userService = new UserService(
                $this->accessTokenRepository,
                $this->itemRepository,
                $this->userRoleDataGroupRepository,
                $this->bidRepository,
                $this->emailNotificationTemplateRepository,
                $this->userRepository,
                $this->emailQueueRepository,
                $this->configRepository
            );
        }
        return $this->userService;
    }

    /**
     * @param UserService $userService
     */
    public function setUserService(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * @api {get} http://localhost:8001/api/users/userDetails User Details - Get
     * @apiDescription Get User Details
     * @apiName getUserDetails
     * @apiGroup USER
     * @apiSubGroup User
     * @apiParam {String} accessToken - Access Token
     * @apiParam {String} [include] - Include Parameters as Comma Seperated values
     * <br />(Supported include parameters are, "items", "bids", "awardedItems")
     * @apiSampleRequest http://localhost:8001/api/users/userDetails
     * @apiSuccess {Json} Object Object containing user details
     * @apiSuccessExample Success-Response:
     *  {
     *    "id": 3,
     *    "username": "user1",
     *    "userRoleId": 2,
     *    "userRoleName": "Regular",
     *    "email": "user1@gmail.com",
     *    "firstName": "Mike",
     *    "lastName": "Smith",
     *    "items": [
     *      {
     *        "id": 1,
     *        "name": "Item 1",
     *        "description": "Description 1",
     *        "price": "1800.00",
     *        "bid": "1950.00",
     *        "closeDateTime": "2021-07-08 16:20",
     *        "isClosed": false,
     *        "isAwardNotified": false,
     *        "itemStatus": "In progress"
     *      },
     *      {
     *        "id": 2,
     *        "name": "Item 4",
     *        "description": "Description 4",
     *        "price": "400.00",
     *        "bid": "460.00",
     *        "closeDateTime": "2021-07-07 21:45",
     *        "isClosed": true,
     *        "isAwardNotified": true,
     *        "itemStatus": "Won"
     *      }
     *    ],
     *    "bids": [
     *      {
     *        "id": 2,
     *        "userId": 3,
     *        "username": "user1",
     *        "itemId": 1,
     *        "itemName": "Item 1",
     *        "itemStatus": "In progress",
     *        "itemCloseDateTime": "2021-07-08 16:20",
     *        "bid": "1950.00",
     *        "isAutoBid": false,
     *        "dateTime": "2021-07-07 21:12"
     *      },
     *      {
     *        "id": 5,
     *        "userId": 3,
     *        "username": "user1",
     *        "itemId": 2,
     *        "itemName": "Item 4",
     *        "itemStatus": "Won",
     *        "itemCloseDateTime": "2021-07-07 21:45",
     *        "bid": "460.00",
     *        "isAutoBid": false,
     *        "dateTime": "2021-07-07 21:37"
     *      }
     *    ],
     *    "awardedItems": [
     *      {
     *        "id": 2,
     *        "name": "Item 4",
     *        "description": "Description 4",
     *        "price": "400.00",
     *        "bid": "460.00",
     *        "closeDateTime": "2021-07-07 21:45",
     *        "isClosed": true,
     *        "isAwardNotified": true,
     *        "winningBidId": 5,
     *        "winningBid": "460.00",
     *        "winningBidIsAutoBid": false,
     *        "winningBidDateTime": "2021-07-07 21:37"
     *      }
     *    ]
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users/userDetails", name="getUserDetails", methods={"GET"})
     */
    public function getUserDetails(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $includeParams = $request->get('include') ? explode(',', $request->get('include')) : array();
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_USER_DETAILS, BaseService::PERMISSION_TYPE_CAN_READ);
        $user = $this->getUser($accessToken);
        return new JsonResponse($this->getUserService()->formatUserDetailsResponse($user, $includeParams), Response::HTTP_OK);
    }

    /**
     * @api {put} http://localhost:8001/api/users/userDetails User Details - Put
     * @apiDescription Update User Details
     * @apiName updateUserDetails
     * @apiGroup USER
     * @apiSubGroup User
     * @apiParam {Json} Object Object containing user details with access token
     * @apiSampleRequest http://localhost:8001/api/users/userDetails
     * @apiParamExample {Json} Parameter Object-Example:
     *  {
     *    "password":"admin1",
     *    "email":"admin1@gmail.com",
     *    "firstName":"John",
     *    "lastName":"Doe",
     *    "accessToken":"af874ho9s8dfush6"
     *  }
     * @apiSuccess {Json} Object Object containing user details
     * @apiSuccessExample Success-Response:
     *  {
     *    "id":1,
     *    "username":"admin1",
     *    "userRoleId":1,
     *    "userRoleName":"Admin",
     *    "email":"admin1@gmail.com",
     *    "firstName":"John",
     *    "lastName":"Doe"
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/users/userDetails", name="updateUserDetails", methods={"PUT"})
     */
    public function updateUserDetails(Request $request): JsonResponse
    {
        $this->validatePutRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_USER_DETAILS, BaseService::PERMISSION_TYPE_CAN_UPDATE);
        $user = $this->getUserService()->updateUserDetails($params);
        return new JsonResponse($this->getUserService()->formatUserDetailsResponse($user, array()), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $includeValues = array(self::INCLUDE_ITEMS, self::INCLUDE_BIDS, self::INCLUDE_AWARDED_ITEMS);
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('include', v::stringType()
                ->notEmpty()
                ->noWhitespace()
                ->callback(function ($include) use ($includeValues) {
                    $includeParams  = explode(',', $include);
                    return v::arrayVal()->each(v::in($includeValues))->validate($includeParams);
                }), false)
        );
        $this->validate($validator, $request->query->all());
    }

    /**
     * @param Request $request
     */
    protected function validatePutRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('firstName', v::stringVal()->notEmpty(), true),
            v::key('lastName', v::stringVal()->notEmpty(), true),
            v::key('email', v::stringVal()->notEmpty()->email(), true),
            v::key('password', v::stringVal()->notEmpty(), false)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
