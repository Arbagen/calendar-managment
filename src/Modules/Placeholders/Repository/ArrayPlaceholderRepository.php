<?php

declare(strict_types=1);

namespace App\Modules\Placeholders\Repository;

use App\Modules\Placeholders\Value\SubscriberNamePlaceholder;

class ArrayPlaceholderRepository implements PlaceholderRepository
{
    public function findAll(): array
    {
        return [
            new SubscriberNamePlaceholder()
        ];
    }
}