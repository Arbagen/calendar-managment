<?php

declare(strict_types=1);

namespace App\Modules\Placeholders\Value;

use App\Entity\SubscriberInformation;

/**
 * Replace subscriber name
 */
class SubscriberNamePlaceholder implements Placeholder
{
    public function textPlaceholder(): string
    {
        return '{subscriberName}';
    }

    public function subscriberInformationKey(): string
    {
        return SubscriberInformation::NAME;
    }
}