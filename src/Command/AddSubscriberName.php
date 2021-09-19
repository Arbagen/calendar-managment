<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\SubscriberInformation;
use App\Repository\SubscriberInformationRepository;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddSubscriberName extends Command
{
    protected static $defaultName = 'app:subscriber:add-name';

    private EntityManagerInterface $entityManager;
    private SubscriberRepository $subscriberRepository;
    private SubscriberInformationRepository $subscriberInformationRepository;

    public function __construct(SubscriberRepository $subscriberRepository, SubscriberInformationRepository $subscriberInformationRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
        $this->subscriberRepository = $subscriberRepository;
        $this->subscriberInformationRepository = $subscriberInformationRepository;
    }

    protected function configure()
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Subscriber id')
            ->addArgument('name', InputArgument::REQUIRED, 'Subscriber name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $name = $input->getArgument('name');

        $subscriber = $this->subscriberRepository->find($id);

        if (null === $subscriber) {
            throw new \InvalidArgumentException('Subscriber not found ' . $id);
        }

        $name = SubscriberInformation::addName($subscriber, $name);
        $this->subscriberInformationRepository->add($name);

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}