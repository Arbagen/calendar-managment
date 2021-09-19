<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Service;

use App\Entity\Subscriber;
use Doctrine\Common\Collections\Collection;

interface SubscriberEvents
{
    public function get(Subscriber $subscriber): Collection;
}