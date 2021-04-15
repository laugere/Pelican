<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Participation;
use App\Form\CommentType;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Asset\Packages;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(EventRepository $eventRepo, Request $request): Response
    {
        if ($request->query->get('search') != null) {
            $events = $eventRepo->findByLike($request->query->get('search'));
        } else {
            $events = $eventRepo->findRecent();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'menu' => 'event'
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

            $notificationService->sendFriendNotification($objectManager, $user, $user->getPseudo() . " a créé un évenement", "description de création d'évenement", "event");

            return $this->redirectToRoute('event', [], 301);
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
            'menu' => 'event'
        ]);
    }

    /**
     * @Route("/event/{eventId}/view", name="event_view")
     */
    public function view($eventId, EventRepository $eventRepo, Request $request, ObjectManager $objectManager, NotificationService $notificationService): Response
    {
        $event = $eventRepo->findOneById($eventId);
        $user = $this->getuser();
        $comment = new Comment();
        $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setDate($date);
            $comment->setDateCreation($date);
            $comment->setDateModification($date);
            $comment->setuser($user);
            $comment->setEvent($event);

            $objectManager->persist($comment);
            $objectManager->flush();

            //return $this->json(['code' => 200], 200);
        }

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'menu' => 'event',
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/event/comment/{commentId}/remove", name="event_comment_remove")
     */
    public function commentRemove($commentId, EventRepository $eventRepo, Request $request, ObjectManager $objectManager, CommentRepository $commentRepo): Response
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $comment = $commentRepo->findOneById($commentId);
        $user = $this->getUser();

        if (!$user or $comment->getUser() != $user) {
            return $this->json([
                'code' => 403,
            ], 403);
        } else {
            $comment->setDateSuppression($datetime);

            $objectManager->persist($comment);
            $objectManager->flush();

            return $this->json(['code' => 200], 200);
        }
    }

    /**
     * @Route("/event/{eventId}/user/view", name="event_user_view")
     */
    public function userView($eventId, EventRepository $eventRepo): Response
    {
        $event = $eventRepo->findOneById($eventId);
        $participations = $event->getParticipations();
        $user = $this->getUser();
        $users = new ArrayCollection();
        $friendships = $user->getFriendship();

        foreach ($participations as $participation) {
            $users->add($participation->getUser());
        }

        return $this->render('friend/index.html.twig', [
            'users' => $users,
            'friendships' => $friendships,
            'menu' => 'community'
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
            } else {
                $imageFile = new File('./uploads/images/events/' . $event->getImage());
                $form->setData(array("imageFile" => $imageFile));
            }

            return $this->render('event/modify.html.twig', [
                'event' => $event,
                'form' => $form->createView(),
                'image' => $event->getImage(),
                'menu' => 'event'
            ]);
        } else {
            return $this->index($eventRepo, $request);
        }
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
            $nbAfter = $event->getNbParticipant() + 1;
            if ($event->getNbParticipant() <= $nbAfter) {
                $participation = new Participation();
                $participation->setDate(new \DateTime());
                $participation->setUser($user);
                $participation->setEvent($event);
                $objectManager->persist($participation);
                $objectManager->flush();
            } else {
                return $this->json(['code' => 400], 200);
            }
        }

        return $this->json(['code' => 200], 200);
    }

    /**
     * @Route("/event/{eventId}/getComments", name="event_getComments")
     */
    public function getEventComs($eventId, EventRepository $eventRepo, Request $request): Response
    {
        $event = $eventRepo->findOneById($eventId);

        $coms = $event->getComments();

        return $this->json(['code' => 200, 'comments' => $coms], 200);
    }

}
