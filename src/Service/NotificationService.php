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
        $notification->setSeen(false);
        $objectManager->persist($notification);
        $objectManager->flush();
    }

    public function sendFriendNotification(ObjectManager $objectManager, User $user, $title, $description, $link)
    {
        foreach ($user->getFriendship() as $friendship) {
            if ($friendship->getValidate()) {
                if ($friendship->getSecond_user() == $user) {
                    var_dump($friendship->getFirst_user()->getId());
                    $this->sendNotification($objectManager, $friendship->getFirst_user(), $title, $description, $link);
                } elseif ($friendship->getFirst_user() == $user) {
                    var_dump($friendship->getSecond_user()->getId());
                    $this->sendNotification($objectManager, $friendship->getSecond_user(), $title, $description, $link);
                }
            }
        }
    }

    public function setNotificationSeen(ObjectManager $objectManager, Notification $notification)
    {
        $notification->setSeen(true);
        $objectManager->persist($notification);
        $objectManager->flush();
    }
}
