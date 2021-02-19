<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\EventRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
use App\Service\NotificationService;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $notifications = $user->getNotifications();

        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications
        ]);
    }

    /**
     * @Route("/notification/{idNotification}/seen", name="notification_seen")
     */
    public function seen($idNotification, ObjectManager $objectManager, NotificationService $notificationService, NotificationRepository $notificationRepo): Response
    {
        $notification = $notificationRepo->findOneById($idNotification);
        $notificationService->setNotificationSeen($objectManager, $notification);

        return $this->redirectToRoute($notification->getLink(), [], 301);
    }
}
