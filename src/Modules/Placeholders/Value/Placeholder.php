<?php

declare(strict_types=1);

namespace App\Modules\Placeholders\Value;

use App\Entity\Event;

interface Placeholder
{
    public function textPlaceholder(): string;
    public function subscriberInformationKey(): string;
}