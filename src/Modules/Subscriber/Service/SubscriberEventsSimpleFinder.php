<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Service;

use App\Entity\Event;
use App\Entity\Subscriber;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SubscriberEventsSimpleFinder implements SubscriberEvents
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function get(Subscriber $subscriber): Collection
    {
        /** @var Event[] $allEvents */
        $allEvents = $this->eventRepository->findAll();
        $events = new ArrayCollection();

        foreach ($allEvents as $event) {
            if ($event->isSatisfiedForSubscriber($subscriber)) {
                $events->add($event);
            }
        }

        return $events;
    }
}