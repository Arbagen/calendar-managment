<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Service;

use App\Entity\Subscriber;

interface SubscriberFactory
{
    public function create(): Subscriber;
}