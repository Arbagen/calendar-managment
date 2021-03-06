<?php

declare(strict_types=1);

namespace App\Modules\Subscriber\Service;

use App\Entity\Subscriber;
use App\Repository\SegmentRepository;
use App\Repository\SubscriberRepository;

class SimpleSubscriberFactory implements SubscriberFactory
{
    const DEFAULT_SEGMENT_NAME =  'default';

    private SubscriberRepository $repository;
    private SegmentRepository $segmentRepository;

    public function __construct(SubscriberRepository $repository, SegmentRepository $segmentRepository)
    {
        $this->repository = $repository;
        $this->segmentRepository = $segmentRepository;
    }

    public function create(): Subscriber
    {
        $newSubscriber = new Subscriber();
        $this->repository->add($newSubscriber);

        $defaultSegment = $this->segmentRepository->findOneBy(['name' => self::DEFAULT_SEGMENT_NAME]);

        if (null === $defaultSegment) {
            throw new \DomainException('Default segment not found');
        }

        $newSubscriber->addSegment($defaultSegment);

        return $newSubscriber;
    }
}