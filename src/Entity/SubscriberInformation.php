<?php

declare(strict_types=1);

namespace App\Entity;

use App\Modules\Placeholders\Value\Placeholder;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Uuid;
use App\Repository\SubscriberInformationRepository;

/**
 * @ORM\Entity(repositoryClass=SubscriberInformationRepository::class)
 */
class SubscriberInformation
{
    const NAME = 'name';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $value;

    /**
     * @ManyToOne(targetEntity="Subscriber", inversedBy="information")
     * @JoinColumn(name="subscriber_id", referencedColumnName="id")
     */
    private Subscriber $subscriber;

    private function __construct(Subscriber $subscriber, string $key, string $value)
    {
        $this->id = Uuid::uuid6()->toString();
        $this->subscriber = $subscriber;
        $this->key = $key;
        $this->value = $value;
    }

    public static function addName(Subscriber $subscriber, string $name): self
    {
        return new self($subscriber, self::NAME, $name);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isSatisfiedForPlaceholder(Placeholder $placeholder): bool
    {
        return $this->key === $placeholder->subscriberInformationKey();
    }

    public static function createEmptyForPlaceholder(Subscriber $subscriber, Placeholder $placeholder): self
    {
        return new self($subscriber, $placeholder->subscriberInformationKey(), '');
    }
}