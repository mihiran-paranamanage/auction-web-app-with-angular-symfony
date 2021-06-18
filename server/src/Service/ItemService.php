<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\AccessTokenRepository;
use App\Repository\BidRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRoleDataGroupRepository;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ItemService
 * @package App\Service
 */
class ItemService extends BaseService
{
    private $userRoleDataGroupRepository;
    private $itemRepository;
    private $bidRepository;

    /**
     * ItemService constructor.
     * @param AccessTokenRepository $accessTokenRepository
     * @param ItemRepository $itemRepository
     * @param UserRoleDataGroupRepository $userRoleDataGroupRepository
     * @param BidRepository $bidRepository
     */
    public function __construct(
        AccessTokenRepository $accessTokenRepository,
        ItemRepository $itemRepository,
        UserRoleDataGroupRepository $userRoleDataGroupRepository,
        BidRepository $bidRepository
    ) {
        parent::__construct($accessTokenRepository, $userRoleDataGroupRepository);
        $this->itemRepository = $itemRepository;
        $this->userRoleDataGroupRepository = $userRoleDataGroupRepository;
        $this->bidRepository = $bidRepository;
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
     * @throws Exception
     */
    public function deleteItem(int $id) : void {
//        $this->manager->getConnection()->beginTransaction();
        try {
            $item = $this->getItem($id);
            $this->bidRepository->removeByItemId($id);
            $this->itemRepository->removeItem($item);
//            $this->manager->getConnection()->commit();
        } catch (Exception $e) {
//            $this->manager->getConnection()->rollBack();
            throw $e;
        }
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
            'closeDateTime' => $item->getCloseDateTime()->format('Y-m-d H:i')
        );
    }
}
