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
        $eventStartDate = $request->query->get('eventStartDate');
        $eventEndDate = $request->query->get('eventEndDate');

        $userType = $request->query->get('userType');
        $eventType = $request->query->get('eventType');
        $communityType = $request->query->get('communityType');

        $search = $request->query->get('search');

        if ($userType) {
            $users = $userRepo->findByLike($search);
            $friendships = $friendshipRepo->findAll();
        } else {
            $users = null;
            $friendships = null;
        }

        if ($eventType) {
            $events = $eventRepo->findByLike($search, $eventStartDate, $eventEndDate);
        } else {
            $events = null;
        }

        if ($communityType) {
            $communitys = $communityRepo->findByLike($search);
        } else {
            $communitys = null;
        }

        return $this->render('search/index.html.twig', [
            'users' => $users,
            'events' => $events,
            'communitys' => $communitys,
            'friendships' => $friendships,
            'menu' => 'home'
        ]);
    }
}
