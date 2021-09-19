<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $event): void
    {
        $this->_em->persist($event);
    }

    public function findEventsBySegments(Collection $segments)
    {
        $q = $this->createQueryBuilder('v')
            ->select('v')
            ->andWhere('v.workingHours IN (:workingHours)')
            ->setParameter('workingHours', $segments);
        ;

        $query = $this->_em->createQuery(
            'SELECT u FROM User u WHERE u.gender IN (SELECT IDENTITY(agl.gender) FROM Site s JOIN s.activeGenderList agl WHERE s.id = ?1)'
        );
    }
}