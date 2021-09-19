<?php

declare(strict_types=1);

namespace App\Modules\Placeholders\Repository;

use App\Modules\Placeholders\Value\Placeholder;

interface PlaceholderRepository
{
    /**
     * List of allowed placeholder to choose on event creating
     * @return Placeholder[]
     */
    public function findAll(): array;
}