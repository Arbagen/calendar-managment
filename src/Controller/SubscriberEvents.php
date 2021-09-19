<?php

declare(strict_types=1);

namespace App\Controller;

use App\Modules\Calendar\Service\iCalendarGenerator;
use App\Repository\SubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberEvents extends AbstractController
{
    /**
     * @Route("/my_calendar/{id}", name="subscriber.calendar")
     */
    public function index(string $id, SubscriberRepository $repository, iCalendarGenerator $calendarGenerator): Response
    {
        $subscriber = $repository->find($id);

        if (null === $subscriber) {
            return new Response('Subscriber not found', Response::HTTP_NOT_FOUND);
        }

        return new Response(
            $calendarGenerator->generateForSubscriber($subscriber)->toString(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'text/calendar',
                'Content-Disposition' => 'attachment; filename="events.ics"',
                'charset' => 'utf-8',
            ]
        );
    }

}