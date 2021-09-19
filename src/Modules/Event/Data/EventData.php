<?php

declare(strict_types=1);

namespace App\Modules\Event\Data;

class EventData
{
    public string $id;
    public \DateTimeInterface $dateTime;
    public string $link;
    public string $title;

    public function __construct(string $id, \DateTimeInterface $dateTime, string $link, string $title)
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->link = $link;
        $this->title = $title;
    }
}