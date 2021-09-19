<?php

declare(strict_types=1);

namespace App\Modules\Calendar\Value;

class CalendarOutput
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function toString(): string
    {
        return $this->content;
    }

}