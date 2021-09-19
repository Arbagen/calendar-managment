<?php

namespace App\Modules\Calendar\Service;

use App\Entity\Subscriber;
use App\Modules\Calendar\Value\CalendarOutput;

interface iCalendarGenerator
{
    public function generateForSubscriber(Subscriber $subscriber): CalendarOutput;
}