<?php

declare(strict_types=1);

namespace App\Controller;

use App\Modules\Subscriber\Service\SubscriberFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Subscribe extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(Request $request): Response
    {
        if ($id = $this->getSubscriberId($request)) {
            return new RedirectResponse($this->generateUrl("already_subscribed", ['id' => $id]));
        }

        return $this->render('subscribe.html.twig', ['linkUrl' => $this->generateUrl('do_subscribe')]);
    }

    /**
     * @Route("/subscribe", name="do_subscribe")
     */
    public function subscribe(Request $request, SubscriberFactory $subscriberFactory, EntityManagerInterface $entityManager): Response
    {
        if ($id = $this->getSubscriberId($request)) {
            return new RedirectResponse($this->generateUrl("already_subscribed", ['id' => $id]));
        }

        $subscriber = $subscriberFactory->create();
        $subscriberLink = $this->generateUrl("subscriber.calendar", ['id' => $subscriber->toString()]);

        $entityManager->flush();

        $response = new Response();
        $response->headers->setCookie(new Cookie('uuid', $subscriber->toString()));

        return $this->render(
            'subscribe.complete.html.twig',
            ['linkUrl' => $subscriberLink, 'id' => $subscriber->toString()],
            $response
        );
    }

    /**
     * @Route("/already_subscribed/{id}", name="already_subscribed")
     */
    public function alreadySubscribed(string $id): Response
    {
        $subscriberLink = $this->generateUrl("subscriber.calendar", ['id' => $id]);

        return $this->render(
            'subscribe.complete.html.twig',
            ['linkUrl' => $subscriberLink],
        );
    }

    private function getSubscriberId(Request $request): ?string
    {
        return $request->cookies->get('uuid');
    }

}