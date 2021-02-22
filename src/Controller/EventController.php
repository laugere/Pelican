<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Event;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\NotificationService;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(EventRepository $eventRepo, Request $request): Response
    {
        if($request->query->get('search') != null) {
            $events = $eventRepo->findByLike($request->query->get('search'));
        } else {
            $events = $eventRepo->findRecent();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/event/create", name="event_create")
     */
    public function create(Event $event = null, Request $request, ObjectManager $objectManager, NotificationService $notificationService): Response
    {
        $user = $this->getuser();

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event->setDateCreation(new \DateTime());
            $event->setDateModification(new \DateTime());
            $event->setuser($user);

            $objectManager->persist($event);
            $objectManager->flush();

            $notificationService->sendFriendNotification($objectManager, $user, $user->getPseudo()." a créé un évenement", "description de création d'évenement", "event");

            return $this->redirectToRoute('event', [], 301);
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{eventId}/view", name="event_view")
     */
    public function view($eventId, EventRepository $eventRepo): Response
    {
        $event = $eventRepo->findOneById($eventId);
        return $this->render('event/view.html.twig', [
            'event' => $event,
            'isIn' => null
        ]);
    }

    /**
     * @Route("/event/{eventId}/modify", name="event_modify")
     */
    public function modify($eventId, Request $request, EventRepository $eventRepo, ObjectManager $objectManager): Response
    {
        $event = $eventRepo->findOneById($eventId);
        $user = $this->getuser();

        if ($event->getUser() == $user) {
            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $event->setDateModification(new \DateTime());
                $event->setUser($user);

                $objectManager->persist($event);
                $objectManager->flush();
            }
        }

        return $this->render('event/modify.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/{eventId}/delete", name="event_delete")
     */
    public function deleteEvent($eventId, EventRepository $eventRepo, ObjectManager $objectManager, Request $request): Response
    {
        $datetime = new \DateTime("now");
        $event = $eventRepo->findOneByid($eventId);
        $user = $this->getUser();

        if ($event->getUser() == $user) {
            $event->setDateSuppression($datetime);

            $objectManager->persist($event);
            $objectManager->flush();
        }

        return $this->index($eventRepo, $request);
    }

    /**
     * @Route("/event/{eventId}/goto", name="event_goto")
     */
    public function goToEvent($eventId, ObjectManager $objectManager, EventRepository $eventRepo): Response
    {
        $user = $this->getuser();

        if (!$user) return $this->json([
            'code' => 403,
        ], 403);

        $event = $eventRepo->findOneById($eventId);
        $participation = null;
        $trouve = false;

        foreach ($event->getParticipations() as $participant) {
            if ($participant->getUser() == $user) {
                $trouve = true;
                $participation = $participant;
            }
        }

        if ($trouve) {
            $objectManager->remove($participation);
            $objectManager->flush();
        } else {
            $participation = new Participation();
            $participation->setDate(new \DateTime());
            $participation->setUser($user);
            $participation->setEvent($event);
            $objectManager->persist($participation);
            $objectManager->flush();
        }

        return $this->json(['code' => 200], 200);
    }
}
