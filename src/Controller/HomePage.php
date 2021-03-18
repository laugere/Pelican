<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\CommunityRepository;
use App\Repository\EventRepository;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController
{
    public function index(): Response
    {
        $user = $this->getUser();
        $events = new ArrayCollection();
        foreach ($user->getEvent() as $participation) {
            $events->add($participation->getEvent());
        }
        $iterator = $events->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getdate() < $b->getdate()) ? -1 : 1;
        });
        $events = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('home/index.html.twig', [
            'events' => $events,
            'menu' => 'home'
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(UserRepository $userRepo, EventRepository $eventRepo, CommunityRepository $communityRepo, FriendshipRepository $friendshipRepo, Request $request): Response
    {
        $users = $userRepo->findByLike($request->query->get('search'));
        $events = $eventRepo->findByLike($request->query->get('search'));
        $communitys = $communityRepo->findByLike($request->query->get('search'));
        $friendships = $friendshipRepo->findAll();

        return $this->render('search/index.html.twig', [
            'users' => $users,
            'events' => $events,
            'communitys' => $communitys,
            'friendships' => $friendships,
            'menu' => 'home'
        ]);
    }
}
