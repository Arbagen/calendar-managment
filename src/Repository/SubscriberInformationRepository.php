<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SubscriberInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SubscriberInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriberInformation::class);
    }

    public function add(SubscriberInformation $subscriber): void
    {
        $this->_em->persist($subscriber);
    }
}