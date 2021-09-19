<?php

declare(strict_types=1);

namespace App\Entity;

use App\Modules\Placeholders\Value\Placeholder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\SubscriberRepository::class)
 */
class Subscriber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ManyToMany(targetEntity="Segment")
     * @JoinTable(name="subscriber_segments",
     *      joinColumns={@JoinColumn(name="subscriber_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="segment_id", referencedColumnName="id")}
     *      )
     */
    private Collection $segments;

    /**
     * @OneToMany(targetEntity="SubscriberInformation", mappedBy="subscriber")
     */
    private Collection $information;

    public function __construct()
    {
        $this->id = Uuid::uuid6()->toString();
        $this->segments = new ArrayCollection();
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function addSegment(Segment $segment): void
    {
        $this->segments->add($segment);
    }

    public function addInformation(SubscriberInformation $information): void
    {
        $this->information->add($information);
    }

    public function hasSegmentsOverlap(Collection $segments): bool
    {
        $segmentsOverlap = $this->segments->filter(
            fn(Segment $segment) => $segments->contains($segment) === true
        );

        return !$segmentsOverlap->isEmpty();
    }

    public function findPlaceholderData(Placeholder $placeholder): SubscriberInformation
    {
       $found = $this->information->filter(
           fn (SubscriberInformation $subscriberInformation) => $subscriberInformation->isSatisfiedForPlaceholder($placeholder)
       );

       return $found->isEmpty()
           ? SubscriberInformation::createEmptyForPlaceholder($this, $placeholder)
           : $found->first();
    }

}