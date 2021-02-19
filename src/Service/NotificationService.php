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

    public function sendFriendNotification(ObjectManager $objectManager, User $user, $title, $description, $link)
    {
        foreach ($user->getFriendship() as $friendship) {
            if ($friendship->getValidate()) {
                if ($friendship->getSecond_user() == $user) {
                    $this->sendNotification($objectManager, $friendship->getFirst_user(), $title, $description, $link);
                    $objectManager->clear();
                }
                if ($friendship->getFirst_user() == $user) {
                    $this->sendNotification($objectManager, $friendship->getSecond_user(), $title, $description, $link);
                    $objectManager->clear();
                }
            }
        }
    }
}
