<?php

namespace App\Repository;

use App\Entity\EmailQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailQueue[]    findAll()
 * @method EmailQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailQueueRepository extends ServiceEntityRepository
{
    private $manager;

    /**
     * EmailQueueRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, EmailQueue::class);
        $this->manager = $manager;
    }

    /**
     * @param EmailQueue $emailQueue
     * @return EmailQueue
     */
    public function pushEmailToQueue(EmailQueue $emailQueue): EmailQueue
    {
        $this->manager->persist($emailQueue);
        $this->manager->flush();
        return $emailQueue;
    }

    /**
     * @param EmailQueue $emailQueue
     */
    public function removeEmailFromQueue(EmailQueue $emailQueue) : void
    {
        $this->manager->remove($emailQueue);
        $this->manager->flush();
    }
}
