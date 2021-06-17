<?php

namespace App\Repository;

use App\Entity\UserBidConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBidConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBidConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBidConfig[]    findAll()
 * @method UserBidConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBidConfigRepository extends ServiceEntityRepository
{
    private $manager;

    /**
     * UserBidConfigRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, UserBidConfig::class);
        $this->manager = $manager;
    }

    /**
     * @param UserBidConfig $userBidConfig
     * @return UserBidConfig
     */
    public function saveUserBidConfig(UserBidConfig $userBidConfig): UserBidConfig
    {
        $this->manager->persist($userBidConfig);
        $this->manager->flush();
        return $userBidConfig;
    }
}
