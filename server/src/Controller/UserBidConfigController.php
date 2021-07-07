<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\ConfigRepository;
use App\Repository\EmailNotificationTemplateRepository;
use App\Repository\EmailQueueRepository;
use App\Repository\UserBidConfigRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\BaseService;
use App\Service\UserBidConfigService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class UserBidConfigController
 * @package App\Controller
 * @Route(path="/api")
 */
class UserBidConfigController extends BaseController
{
    private $emailNotificationTemplateRepository;
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $userBidConfigRepository;
    private $emailQueueRepository;
    private $configRepository;
    private $userBidConfigService;

    /**
     * UserBidConfigController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param UserBidConfigRepository $userBidConfigRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param EmailNotificationTemplateRepository $emailNotificationTemplateRepository
     * @param EmailQueueRepository $emailQueueRepository
     * @param ConfigRepository $configRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        UserBidConfigRepository $userBidConfigRepository,
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
        $this->userBidConfigRepository = $userBidConfigRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->emailNotificationTemplateRepository = $emailNotificationTemplateRepository;
    }

    /**
     * @return UserBidConfigService
     */
    public function getUserBidConfigService() : UserBidConfigService {
        if (!($this->userBidConfigService instanceof UserBidConfigService)) {
            $this->userBidConfigService = new UserBidConfigService(
                $this->accessTokenRepository,
                $this->userBidConfigRepository,
                $this->userRoleDataGroupRepository,
                $this->emailNotificationTemplateRepository,
                $this->emailQueueRepository,
                $this->configRepository
            );
        }
        return $this->userBidConfigService;
    }

    /**
     * @param UserBidConfigService $userBidConfigService
     */
    public function setUserBidConfigService(UserBidConfigService $userBidConfigService) {
        $this->userBidConfigService = $userBidConfigService;
    }

    /**
     * @api {get} http://localhost:8001/api/autoBidConfig Auto Bid Config - Get
     * @apiDescription Get Auto Bid Config
     * @apiName getAutoBidConfig
     * @apiGroup BID
     * @apiSubGroup Auto Bid Config
     * @apiParam {String} accessToken - Access Token
     * @apiSampleRequest http://localhost:8001/api/autoBidConfig
     * @apiSuccess {Json} Object Object containing auto bid config data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id": 1,
     *    "userId": 1,
     *    "userName": "admin1",
     *    "maxBidAmount": "2500.00",
     *    "currentBidAmount": "1250.00",
     *    "notifyPercentage": 90,
     *    "isAutoBidEnabled": true,
     *    "isMaxBidExceedNotified": false
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/autoBidConfig", name="getUserBidConfig", methods={"GET"})
     */
    public function getUserBidConfig(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_CONFIGURE_AUTO_BID, BaseService::PERMISSION_TYPE_CAN_READ);
        $userBidConfig = $this->getUserBidConfigService()->getUserBidConfig($accessToken);
        $userBidConfigResponse = $this->getUserBidConfigService()->formatUserBidConfigResponse($userBidConfig);
        $this->getUserBidConfigService()->checkMaxAutoBidAmountStatus($userBidConfig);
        return new JsonResponse($userBidConfigResponse, Response::HTTP_OK);
    }

    /**
     * @api {put} http://localhost:8001/api/autoBidConfig Auto Bid Config - Put
     * @apiDescription Update Auto Bid Config
     * @apiName updateAutoBidConfig
     * @apiGroup BID
     * @apiSubGroup Auto Bid Config
     * @apiParam {Json} Object Object containing auto bid config data with access token
     * @apiSampleRequest http://localhost:8001/api/autoBidConfig
     * @apiParamExample {Json} Parameter Object-Example:
     *  {
     *    "maxBidAmount":"2500.00",
     *    "notifyPercentage":"90",
     *    "isAutoBidEnabled":true,
     *    "accessToken":"af874ho9s8dfush6"
     *  }
     * @apiSuccess {Json} Object Object containing auto bid config data
     * @apiSuccessExample Success-Response:
     *  {
     *    "id":1,
     *    "userId":1,
     *    "userName":"admin1",
     *    "maxBidAmount":"2500.00",
     *    "currentBidAmount":"1250.00",
     *    "notifyPercentage":90,
     *    "isAutoBidEnabled":true,
     *    "isMaxBidExceedNotified":false
     *  }
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/autoBidConfig", name="saveUserBidConfig", methods={"PUT"})
     */
    public function saveUserBidConfig(Request $request): JsonResponse
    {
        $this->validatePutRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_CONFIGURE_AUTO_BID, BaseService::PERMISSION_TYPE_CAN_UPDATE);
        $userBidConfig = $this->getUserBidConfigService()->saveUserBidConfig($params);
        $userBidConfigResponse = $this->getUserBidConfigService()->formatUserBidConfigResponse($userBidConfig);
        $this->getUserBidConfigService()->checkMaxAutoBidAmountStatus($userBidConfig);
        return new JsonResponse($userBidConfigResponse, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true)
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
            v::key('maxBidAmount', v::anyOf(v::intVal()->positive(), v::decimal(2)), true),
            v::key('notifyPercentage', v::intVal()->between(0, 100), true),
            v::key('isAutoBidEnabled', v::boolVal(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
