<?php

namespace App\Controller;

use App\Repository\IsInRepository;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(EventRepository $event, IsInRepository $isIn): Response
    {
        $user = $this->getUser();
        $communitys = $isIn->findRecent($user->getId());
        $events = $event->findRecent();

        return $this->render('event/index.html.twig', [
            'controller_name' => 'Événements',
            'events' => $events,
            'communitys' => $communitys
        ]);
    }

    /**
     * @Route("/event/create", name="event_create")
     */
    public function create(Event $event = null, Request $request, ObjectManager $objectManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setDateCreation(new \DateTime());
            $event->setDateModification(new \DateTime());

            $objectManager->persist($event);
            $objectManager->flush();

            return $this->redirectToRoute('event', [], 301);
        }

        return $this->render('event/create.html.twig', [
            'controller_name' => 'Organiser un événement',
            'form' => $form->createView()
        ]);
    }
}
