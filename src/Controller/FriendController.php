<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class FriendController extends AbstractController
{
    /**
     * @Route("/friend/{userId}/view", name="friend_view")
     */
    public function friendsView($userId, Request $request, UserRepository $userRepo): Response
    {
        $users = new ArrayCollection();
        $user = $userRepo->findOneById($userId);
        $friendships = $user->getFriendship();
        $em = $this->getDoctrine()->getManager();

        foreach ($friendships as $friendship) {
            if ($friendship->getValidate()) {
                if ($friendship->getFirst_user() != $user) {
                    $users->add($friendship->getFirst_user());
                } else {
                    $users->add($friendship->getSecond_user());
                }
            }
        }

        return $this->render('friend/index.html.twig', [
            'users' => $users,
            'friendships' => $friendships,
            'menu' => 'community'
        ]);
    }

    /**
     * @Route("/friend/{userid}/add", name="friend_add")
     */
    public function add($userid, ObjectManager $objectManager, UserRepository $userRepo, FriendshipRepository $friendshipRepo, NotificationService $notificationService): Response
    {
        $first_user = $this->getUser();
        $second_user = $userRepo->findOneById($userid);

        if (!$first_user) return $this->json([
            'code' => 403,
        ], 403);

        $friendShipUser = null;

        foreach ($friendshipRepo->findAll() as $friendship) {
            if ($friendship->getFirst_user() == $first_user && $friendship->getSecond_user() == $second_user || $friendship->getFirst_user() == $second_user && $friendship->getSecond_user() == $first_user) {
                $friendShipUser = $friendship;
            }
        }

        if ($friendShipUser != null) {
            if ($friendShipUser->getValidate() || $friendship->getFirst_user() == $first_user) {
                $objectManager->remove($friendShipUser);
                $objectManager->flush();
            } else {
                $notificationService->sendNotification($objectManager, $second_user, "Demande d'amitié avec " . $first_user->getPseudo() . " est confirmée", "", "friend");

                $friendShipUser->setValidate(true);
                $objectManager->persist($friendShipUser);
                $objectManager->flush();
            }
        } else {
            $notificationService->sendNotification($objectManager, $second_user, "Demande d'amitié avec " . $first_user->getPseudo() . " reçue", "", "friend");

            $friendShipUser = new Friendship();
            $friendShipUser->setFirst_user($first_user);
            $friendShipUser->setSecond_user($second_user);
            $friendShipUser->setValidate(false);
            $friendShipUser->setDate(new \DateTime());
            $objectManager->persist($friendShipUser);
            $objectManager->flush();
        }

        return $this->json(['code' => 200], 200);
    }
}
