<?php

declare(strict_types=1);

namespace App\Modules\Calendar\Service;

use App\Entity\Subscriber;
use App\Modules\Calendar\Value\CalendarOutput;
use App\Modules\Subscriber\Service\SubscriberEvents;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class SpatieGenerator implements iCalendarGenerator
{
    private SubscriberEvents $subscriberEvents;

    public function __construct(SubscriberEvents $subscriberEvents)
    {
        $this->subscriberEvents = $subscriberEvents;
    }

    public function generateForSubscriber(Subscriber $subscriber): CalendarOutput
    {
        /** @var \App\Entity\Event[] $events */
        $events = $this->subscriberEvents->get($subscriber);

        $calendar = Calendar::create('Events')->productIdentifier($subscriber->toString());

        foreach ($events as $event) {
            $eventData = $event->prepareDataForSubscriber($subscriber);

            $iCalendarEvent = Event::create($eventData->title)
                ->url($eventData->link)
                ->uniqueIdentifier($eventData->id)
                ->startsAt($eventData->dateTime, true)
                ->endsAt($eventData->dateTime, false);

            $calendar->event($iCalendarEvent);
        }

        return new CalendarOutput($calendar->get());
    }
}