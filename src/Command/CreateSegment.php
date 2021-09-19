<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Segment;
use App\Repository\SegmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSegment extends Command
{
    protected static $defaultName = 'app:create-segments';
    private SegmentRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(SegmentRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $segments = [
            new Segment(\App\Modules\Subscriber\Service\SimpleSubscriberFactory::DEFAULT_SEGMENT_NAME),
            new Segment('first segment'),
            new Segment('second segment'),
            new Segment('third segment'),
        ];

        foreach ($segments as $segment) {
            $this->repository->add($segment);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}