<?php

namespace App\Repository;

use App\Entity\EmailNotificationTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailNotificationTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailNotificationTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailNotificationTemplate[]    findAll()
 * @method EmailNotificationTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailNotificationTemplateRepository extends ServiceEntityRepository
{
    /**
     * EmailNotificationTemplateRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailNotificationTemplate::class);
    }
}
