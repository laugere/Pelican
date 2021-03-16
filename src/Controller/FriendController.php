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

class FriendController extends AbstractController
{
    /**
     * @Route("/friend", name="friend")
     */
    public function index(FriendshipRepository $friendshipRepo, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if($request->query->get('search') != null) {
            $users = $users = $em->getRepository('App:User')->findByLike($request->query->get('search'));
        } else {
            $users = $em->getRepository('App:User')->findAll();
        }
        $friendships = $friendshipRepo->findAll();

        return $this->render('friend/index.html.twig', [
            'users' => $users,
            'friendships' => $friendships
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
                $notificationService->sendNotification($objectManager, $second_user, "Demande d'amitié avec ".$first_user->getPseudo()." est confirmée", "", "friend");

                $friendShipUser->setValidate(true);
                $objectManager->persist($friendShipUser);
                $objectManager->flush();
            }
        } else {
            $notificationService->sendNotification($objectManager, $second_user, "Demande d'amitié avec ".$first_user->getPseudo()." reçue", "", "friend");

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
