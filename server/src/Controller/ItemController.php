<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

/**
 * Class ItemController
 * @package App\Controller
 * @Route(path="/api")
 */
class ItemController extends BaseController
{
    private $itemRepository;

    /**
     * ItemController constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        ItemRepository $itemRepository
    ) {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/items", name="getItems", methods={"GET"})
     */
    public function getItems(Request $request): JsonResponse
    {
        $this->validateGetRequest($request);
        $params = array(
            'filter' => $request->get('filter'),
            'limit' => $request->get('limit'),
            'offset' => $request->get('offset'),
            'sortField' => $request->get('sortField'),
            'sortOrder' => $request->get('sortOrder')
        );
        $items = $this->getItemsByParams($params);
        return new JsonResponse($this->formatGetResponse($items), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @Route("/items/{id}", name="getItem", methods={"GET"})
     */
    public function getItem(int $id): JsonResponse
    {
        $item = $this->getItemById($id);
        return new JsonResponse($this->formatItemResponse($item), Response::HTTP_OK);
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
        $item = $this->saveNewItem($params);
        return new JsonResponse($this->formatItemResponse($item), Response::HTTP_CREATED);
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
        $item = $this->updateExistingItem($params, $id);
        return new JsonResponse($this->formatItemResponse($item), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @Route("/items/{id}", name="deleteItem", methods={"DELETE"})
     */
    public function deleteItem(Request $request, int $id): JsonResponse
    {
        $this->deleteItemById($id);
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param array $params
     * @return array
     */
    protected function getItemsByParams(array $params) : array
    {
        return $this->itemRepository->findByParams($params);
    }

    /**
     * @param int $id
     * @return Item
     */
    protected function getItemById(int $id): Item
    {
        $item = $this->itemRepository->findOneBy(array('id' => $id));
        if ($item instanceof Item) {
            return $item;
        } else {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
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
    protected function validatePostRequest(Request $request) : void
    {
        $validator = v::keySet(
            v::key('name', v::stringVal()->notEmpty(), true),
            v::key('description', v::stringVal(), false),
            v::key('price', v::intVal()->positive(), true),
            v::key('bid', v::intVal()->positive(), true),
            v::key('closeDateTime', v::dateTime('Y-m-d H:i'), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }

    /**
     * @param array $items
     * @return array
     */
    protected function formatGetResponse(array $items) : array
    {
        $itemsArr = array();
        foreach ($items as $item) {
            if ($item instanceof Item) {
                $itemArr = array();
                $itemArr['id'] = $item->getId();
                $itemArr['name'] = $item->getName();
                $itemArr['description'] = $item->getDescription();
                $itemArr['price'] = $item->getPrice();
                $itemArr['bid'] = $item->getBid();
                $itemArr['closeDateTime'] = $item->getCloseDateTime();
                $itemsArr[] = $itemArr;
            }
        }
        return $itemsArr;
    }

    /**
     * @param Item $item
     * @return array
     */
    protected function formatItemResponse(Item $item) : array
    {
        return array(
            'id' => $item->getId(),
            'name' => $item->getName(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
            'bid' => $item->getBid(),
            'closeDateTime' => $item->getCloseDateTime()
        );
    }

    /**
     * @param array $params
     * @return Item
     */
    protected function saveNewItem(array $params) : Item {
        $item = new Item();
        $item->setName($params['name']);
        $item->setDescription($params['description']);
        $item->setPrice($params['price']);
        $item->setBid($params['bid']);
        $item->setCloseDateTime(DateTime::createFromFormat('Y-m-d H:i', $params['closeDateTime']));
        return $this->itemRepository->saveItem($item);
    }

    /**
     * @param array $params
     * @param int $id
     * @return Item
     */
    protected function updateExistingItem(array $params, int $id) : Item {
        $item = $this->getItemById($id);
        $item->setName($params['name']);
        $item->setDescription($params['description']);
        $item->setPrice($params['price']);
        $item->setBid($params['bid']);
        $item->setCloseDateTime(DateTime::createFromFormat('Y-m-d H:i', $params['closeDateTime']));
        return $this->itemRepository->saveItem($item);
    }

    /**
     * @param int $id
     */
    protected function deleteItemById(int $id) : void {
        $item = $this->getItemById($id);
        $this->itemRepository->removeItem($item);
    }
}