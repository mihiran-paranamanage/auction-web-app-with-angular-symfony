<?php

namespace App\Repository;

use App\Entity\ItemBillTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemBillTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemBillTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemBillTemplate[]    findAll()
 * @method ItemBillTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemBillTemplateRepository extends ServiceEntityRepository
{
    /**
     * ItemBillTemplateRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBillTemplate::class);
    }
}
