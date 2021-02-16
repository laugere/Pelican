<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Event;
use App\Entity\GoToEvent;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\GoToEventRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(EventRepository $event): Response
    {
        $events = $event->findRecent();

        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/event/create", name="event_create")
     */
    public function create(Event $event = null, Request $request, ObjectManager $objectManager): Response
    {
        $user = $this->getuser();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $event->setDateCreation(new \DateTime());
            $event->setDateModification(new \DateTime());
            $event->setIdCreator($user->getId());

            $objectManager->persist($event);
            $objectManager->flush();

            return $this->redirectToRoute('event', [], 301);
        }

        return $this->render('event/create.html.twig', [
            'controller_name' => 'Organiser un événement',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{eventId}/view", name="event_view")
     */
    public function view($eventId, EventRepository $eventRepo, GoToEventRepository $goToEventRepo): Response
    {
        $event = $eventRepo->findOneById($eventId);
        $user = $this->getuser();
        $isIn = $goToEventRepo->userGoToEvent($user->getId(), $eventId);
        return $this->render('event/view.html.twig', [
            'event' => $event,
            'isIn' => $isIn
        ]);
    }

    /**
     * @Route("/event/{eventId}/modify", name="event_modify")
     */
    public function modify($eventId, Request $request, EventRepository $eventRepo, ObjectManager $objectManager): Response
    {
        $event = $eventRepo->findOneById($eventId);
        $user = $this->getuser();

        if ($event->getIdCreator() == $user->getId()) {
            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $event->setDateModification(new \DateTime());
                $event->setIdCreator($user->getId());

                $objectManager->persist($event);
                $objectManager->flush();
            }
        }

        return $this->render('event/modify.html.twig', [
            'controller_name' => 'Modifier un événement',
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{eventId}/delete", name="event_delete")
     */
    public function deleteEvent($eventId, EventRepository $eventRepo, ObjectManager $objectManager): Response
    {
        $datetime = new \DateTime("now");
        $event = $eventRepo->findOneByid($eventId);
        $user = $this->getUser();

        if ($event->getIdCreator() == $user->getId()) {
            $event->setDateSuppression($datetime);

            $objectManager->persist($event);
            $objectManager->flush();
        }

        return $this->index($eventRepo);
    }

    /**
     * @Route("/event/{eventId}/goto", name="event_goto")
     */
    public function goToEvent($eventId, ObjectManager $objectManager, GoToEventRepository $goToEventRepo): Response
    {
        $user = $this->getuser();

        if (!$user) return $this->json([
            'code' => 403,
        ], 403);

        if (!$goToEventRepo->userGoToEvent($user->getId(), $eventId)) {
            $GoToEvent = new GoToEvent();
            $GoToEvent->setIdEvent($eventId);
            $GoToEvent->setIdUser($user->getId());
            $objectManager->persist($GoToEvent);
            $objectManager->flush();
        } else {
            $goToEventRepo->deleteGoToEvent($user->getId(), $eventId);
        }

        return $this->json(['code' => 200], 200);
    }
}
