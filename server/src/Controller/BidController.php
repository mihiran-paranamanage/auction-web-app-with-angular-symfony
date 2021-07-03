<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRoleDataGroupRepository;
use App\Service\BaseService;
use App\Service\BidService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class BidController
 * @package App\Controller
 * @Route(path="/api")
 */
class BidController extends BaseController
{
    private $userRoleDataGroupRepository;
    private $accessTokenRepository;
    private $bidRepository;
    private $itemRepository;
    private $bidService;

    /**
     * BidController constructor.
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
     * @return BidService
     */
    public function getBidService() : BidService {
        if (!($this->bidService instanceof BidService)) {
            $this->bidService = new BidService(
                $this->accessTokenRepository,
                $this->bidRepository,
                $this->itemRepository,
                $this->userRoleDataGroupRepository
            );
        }
        return $this->bidService;
    }

    /**
     * @param BidService $bidService
     */
    public function setBidService(BidService $bidService) {
        $this->bidService = $bidService;
    }

    /**
     * @api {get} http://localhost:8001/api/bids Bids - Get
     * @apiDescription Get Bids
     * @apiName getBids
     * @apiGroup BID
     * @apiSubGroup Bid
     * @apiParam {String} accessToken - Access Token
     * @apiParam {String} [filter[itemId]] - Item Id
     * @apiSampleRequest http://localhost:8001/api/bids
     * @apiSuccess {Json} Object Object containing bids data
     * @apiSuccessExample Success-Response:
     *  [
     *    {
     *      "id":1,
     *      "userId":1,
     *      "itemId":1,
     *      "bid":"1500.00",
     *      "isAutoBid":false,
     *      "dateTime":"2021-06-20 22:50",
     *      "user":{
     *        "userId":1,
     *        "username":"admin1"
     *      }
     *    }
     *  ]
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/bids", name="getBids", methods={"GET"})
     */
    public function getBids(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_BID, BaseService::PERMISSION_TYPE_CAN_READ);
        $this->checkAuthorization($accessToken, BaseService::DATA_GROUP_BID_HISTORY, BaseService::PERMISSION_TYPE_CAN_READ);
        $params = array(
            'filter' => $request->get('filter')
        );
        $bids = $this->getBidService()->getBids($params);
        return new JsonResponse($this->getBidService()->formatBidsResponse($bids), Response::HTTP_OK);
    }

    /**
     * @api {post} http://localhost:8001/api/bids/:id Bid - Post
     * @apiDescription Save Bid
     * @apiName saveBid
     * @apiGroup BID
     * @apiSubGroup Bid
     * @apiParam {Json} Object Object containing bid data with access token
     * @apiSampleRequest http://localhost:8001/api/bids/1
     * @apiParamExample {Json} Parameter Object-Example:
     *  {
     *    "itemId":1,
     *    "bid":"1800.00",
     *    "isAutoBid":true,
     *    "accessToken":"df874ho9s8dfush9"
     *  }
     * @apiSuccess {Json} Object Object containing bid data
     * @apiSuccessExample Success-Response:
     *  [
     *    {
     *      "id":1,
     *      "userId":3,
     *      "itemId":1,
     *      "bid":"1800.00",
     *      "isAutoBid":true,
     *      "dateTime":"2021-06-20 22:50",
     *      "user":{
     *        "userId":3,
     *        "username":"user1"
     *      }
     *    }
     *  ]
     * @apiError (400) BadRequest Bad Request
     * @apiError (401) Unauthorized Unauthorized
     * @apiError (404) NotFound Not Found
     */
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \WebSocket\BadOpcodeException
     * @Route("/bids", name="saveBid", methods={"POST"})
     */
    public function saveBid(Request $request): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken'], BaseService::DATA_GROUP_BID, BaseService::PERMISSION_TYPE_CAN_CREATE);
        $bid = $this->getBidService()->saveBid($params);
        $this->getEventPublisher()->publishToWS($params['itemId'], 'Bid Saved');
        return new JsonResponse($this->getBidService()->formatBidResponse($bid), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     */
    protected function validateGetRequest(Request $request) : void
    {
        $filterValidator = v::keySet(
            v::key('itemId', v::intVal()->positive(), false)
        );
        $validator = v::keySet(
            v::key('accessToken', v::stringVal()->notEmpty(), true),
            v::key('filter', $filterValidator, false)
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
            v::key('itemId', v::intVal()->positive(), true),
            v::key('bid', v::anyOf(v::intVal()->positive(), v::decimal(2)), true),
            v::key('isAutoBid', v::boolVal(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
