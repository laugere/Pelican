<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Entity\Notification;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    /**
     * @Route("/friend", name="friend")
     */
    public function index(FriendshipRepository $friendshipRepo): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();
        $friendships = $friendshipRepo->findAll();

        return $this->render('friend/index.html.twig', [
            'users' => $users,
            'friendships' => $friendships
        ]);
    }

    /**
     * @Route("/friend/{userid}/add", name="friend_add")
     */
    public function add($userid, ObjectManager $objectManager, UserRepository $userRepo, FriendshipRepository $friendshipRepo): Response
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
            if ($friendShipUser->getValidate()) {
                $objectManager->remove($friendShipUser);
                $objectManager->flush();
            } else {
                $notification = new Notification();
                $notification->setuser($second_user);
                $notification->setTitle("Validation d'amitié avec ".$first_user->getPseudo());
                $notification->setDescription("Description de validation d'amitié");
                $notification->setLink("/friend");
                $objectManager->persist($notification);
                $objectManager->flush();

                $friendShipUser->setValidate(true);
                $objectManager->persist($friendShipUser);
                $objectManager->flush();
            }
        } else {
            $notification = new Notification();
            $notification->setuser($second_user);
            $notification->setTitle("Demande d'amitié avec ".$first_user->getPseudo());
            $notification->setDescription("Description de demande d'amitié");
            $notification->setLink("/friend");
            $objectManager->persist($notification);
            $objectManager->flush();

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
