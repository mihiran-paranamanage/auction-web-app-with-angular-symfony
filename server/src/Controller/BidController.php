<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRoleDataGroupRepository;
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
     * @param Request $request
     * @return JsonResponse
     * @Route("/bids", name="getBids", methods={"GET"})
     */
    public function getBids(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken);
        $params = array(
            'filter' => $request->get('filter')
        );
        $bids = $this->getBidService()->getBids($params);
        return new JsonResponse($this->getBidService()->formatBidsResponse($bids), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     * @Route("/bids", name="saveBid", methods={"POST"})
     */
    public function saveBid(Request $request): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken']);
        $bid = $this->getBidService()->saveBid($params);
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
            v::key('bid', v::intVal()->positive(), true),
            v::key('isAutoBid', v::boolVal(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
