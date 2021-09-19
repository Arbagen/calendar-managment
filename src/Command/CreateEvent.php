<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Event;
use App\Modules\Placeholders\Value\SubscriberNamePlaceholder;
use App\Repository\EventRepository;
use App\Repository\SegmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEvent extends Command
{
    protected static $defaultName = 'app:create-events';

    private EventRepository $eventRepository;
    private EntityManagerInterface $entityManager;
    private SegmentRepository $segmentRepository;

    public function __construct(EventRepository $repository, SegmentRepository $segmentRepository, EntityManagerInterface $entityManager)
    {
        $this->eventRepository = $repository;
        $this->entityManager = $entityManager;
        $this->segmentRepository = $segmentRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption('with-name', 'name');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $withNamePlaceholder = $input->getOption('with-name');
        $segments = $this->segmentRepository->findAll();

        $count = 15;
        $counter = 0;
        while($counter < $count) {
            $counter++;

            $text = sprintf('We have same%s interesting for You', $counter);
            $dateTime = new \DateTimeImmutable(sprintf('-%s hour', rand(1, 24)));

            if ($withNamePlaceholder) {
                $text = 'Hello, {subscriberName}. ' . $text;
            }

            shuffle($segments);
            $randomSegments = array_chunk($segments, 2)[0];

            $event = new Event(
                $dateTime,
                $text,
                'https://www.facebook.com',
                new ArrayCollection($randomSegments)
            );

            if ($withNamePlaceholder) {
                $event->addTextPlaceholder(new SubscriberNamePlaceholder());
            }

            $this->eventRepository->add($event);

        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}