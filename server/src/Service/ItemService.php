<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\AccessTokenRepository;
use App\Repository\ItemRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ItemService
 * @package App\Service
 */
class ItemService extends BaseService
{
    private $itemRepository;

    /**
     * ItemService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository
    ) {
        parent::__construct($accessTokenRepository);
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getItems(array $params) : array
    {
        return $this->itemRepository->findByParams($params);
    }

    /**
     * @param int $id
     * @return Item
     */
    public function getItem(int $id): Item
    {
        $item = $this->itemRepository->findOneBy(array('id' => $id));
        if ($item instanceof Item) {
            return $item;
        } else {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }
    }

    /**
     * @param array $params
     * @return Item
     */
    public function saveItem(array $params) : Item {
        $item = new Item();
        $item->setName($params['name']);
        $item->setDescription($params['description']);
        $item->setPrice($params['price']);
        $item->setBid($params['bid']);
        $item->setCloseDateTime(DateTime::createFromFormat('Y-m-d H:i', $params['closeDateTime']));
        return $this->itemRepository->saveItem($item);
    }

    /**+
     * @param array $params
     * @param int $id
     * @return Item
     */
    public function updateItem(array $params, int $id) : Item {
        $item = $this->getItem($id);
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
    public function deleteItem(int $id) : void {
        $item = $this->getItem($id);
        $this->itemRepository->removeItem($item);
    }

    /**
     * @param array $items
     * @return array
     */
    public function formatItemsResponse(array $items) : array
    {
        $itemsArr = array();
        foreach ($items as $item) {
            if ($item instanceof Item) {
                $itemsArr[] = $this->formatItemResponse($item);
            }
        }
        return $itemsArr;
    }

    /**
     * @param Item $item
     * @return array
     */
    public function formatItemResponse(Item $item) : array
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
}