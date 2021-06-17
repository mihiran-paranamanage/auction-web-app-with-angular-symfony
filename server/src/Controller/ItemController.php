<?php

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\ItemRepository;
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
    private $accessTokenRepository;
    private $itemRepository;
    private $itemService;

    /***
     * ItemController constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository
    ) {
        parent::__construct($accessTokenRepository);
        $this->accessTokenRepository = $accessTokenRepository;
        $this->itemRepository = $itemRepository;
    }

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
     * @param Request $request
     * @return JsonResponse
     * @Route("/items", name="getItems", methods={"GET"})
     */
    public function getItems(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $accessToken = $request->get('accessToken');
        $this->checkAuthorization($accessToken);
        $params = array(
            'filter' => $request->get('filter'),
            'limit' => $request->get('limit'),
            'offset' => $request->get('offset'),
            'sortField' => $request->get('sortField'),
            'sortOrder' => $request->get('sortOrder')
        );
        $items = $this->getItemService()->getItems($params);
        return new JsonResponse($this->getItemService()->formatItemsResponse($items), Response::HTTP_OK);
    }

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
        $this->checkAuthorization($accessToken);
        $item = $this->getItemService()->getItem($id);
        return new JsonResponse($this->getItemService()->formatItemResponse($item), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/items", name="saveItem", methods={"POST"})
     */
    public function saveItem(Request $request): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken']);
        $item = $this->getItemService()->saveItem($params);
        return new JsonResponse($this->getItemService()->formatItemResponse($item), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @Route("/items/{id}", name="updateItem", methods={"PUT"})
     */
    public function updateItem(Request $request, int $id): JsonResponse
    {
        $this->validatePostRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken']);
        $item = $this->getItemService()->updateItem($params, $id);
        return new JsonResponse($this->getItemService()->formatItemResponse($item), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @Route("/items/{id}", name="deleteItem", methods={"DELETE"})
     */
    public function deleteItem(Request $request, int $id): JsonResponse
    {
        $this->validateDeleteRequest($request);
        $params = json_decode($request->getContent(), true);
        $this->checkAuthorization($params['accessToken']);
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
            v::key('description', v::stringVal(), false),
            v::key('price', v::intVal()->positive(), true),
            v::key('bid', v::intVal()->not(v::negative()), true),
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
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}