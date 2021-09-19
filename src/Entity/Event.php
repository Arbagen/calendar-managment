<?php

declare(strict_types=1);

namespace App\Entity;

use App\Modules\Event\Data\EventData;
use App\Modules\Placeholders\Value\Placeholder;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\SegmentRepository::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private \DateTimeImmutable $date;

    /**
     * @ORM\Column(type="text", length=755)
     */
    private string $text;

    /**
     * @ORM\Column(type="text")
     */
    private string $link;

    /**
     * @ManyToMany(targetEntity="Segment")
     * @JoinTable(name="event_segments",
     *      joinColumns={@JoinColumn(name="event_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="segment_id", referencedColumnName="id")}
     *      )
     */
    private Collection $segments;

    /**
     * @var Placeholder[]
     * @ORM\Column(type="array", length=755)
     */
    private array $placeholders = [];

    public function __construct(\DateTimeImmutable $date, string $text, string $link, Collection $segments)
    {
        if ($segments->isEmpty()) {
            throw new \DomainException('Event must have at least one segment');
        }

        $this->id = Uuid::uuid6()->toString();
        $this->date = $date;
        $this->text = $text;
        $this->link = $link;
        $this->segments = $segments;
    }

    public function isSatisfiedForSubscriber(Subscriber $subscriber): bool
    {
        return $subscriber->hasSegmentsOverlap($this->segments);
    }

    public function prepareDataForSubscriber(Subscriber $subscriber): EventData
    {
        return new EventData(
            $this->id,
            $this->date,
            $this->link,
            $this->hasPlaceholders() ? $this->replaceTextPlaceholdersForSubscriber($subscriber) : $this->text
        );
    }

    public function addTextPlaceholder(Placeholder $placeholder): void
    {
        if (false === strpos($this->text, $placeholder->textPlaceholder())) {
            throw new \DomainException('Text must contain placeholder ' . $placeholder->textPlaceholder());
        }

        $this->placeholders[] = $placeholder;
    }

    private function hasPlaceholders(): bool
    {
        return !empty($this->placeholders);
    }

    private function replaceTextPlaceholdersForSubscriber(Subscriber $subscriber): string
    {
        $text = $this->text;
        foreach ($this->placeholders as $placeholder) {
            $subscriberInformation = $subscriber->findPlaceholderData($placeholder);
            $text = str_replace($placeholder->textPlaceholder(), $subscriberInformation->value(), $text);
        }

        return $text;
    }
}