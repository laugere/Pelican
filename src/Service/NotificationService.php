<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class NotificationService
{
    public function sendNotification(ObjectManager $objectManager, User $user, $title, $description, $link)
    {
        $notification = new Notification();
        $notification->setuser($user);
        $notification->setTitle($title);
        $notification->setDescription($description);
        $notification->setLink($link);
        $objectManager->persist($notification);
        $objectManager->flush();
    }
}
