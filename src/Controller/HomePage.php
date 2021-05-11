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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;

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
    public function search(UserRepository $userRepo, EventRepository $eventRepo, CommunityRepository $communityRepo, FriendshipRepository $friendshipRepo, Request $request, LoggerInterface $logger): Response
    {
        $eventStartDate = $request->query->get('eventStartDate');
        $eventEndDate = $request->query->get('eventEndDate');

        $userType = $request->query->get('userType');
        $eventType = $request->query->get('eventType');
        $communityType = $request->query->get('communityType');

        $search = $request->query->get('search');
        $tags = $request->query->get('eventTags');

        if ($userType) {
            if (empty($search)) {
                $users = $userRepo->findAll();
                $friendships = $friendshipRepo->findAll();
            } else {
                $users = $userRepo->findByLike($search);
                $friendships = $friendshipRepo->findAll();
            }
        } else {
            $users = null;
            $friendships = null;
        }

        if ($eventType) {
            if (empty($search)) {
                $events = new ArrayCollection($eventRepo->findRecent());
            } else {
                $events = new ArrayCollection($eventRepo->findByLike($search, $eventStartDate, $eventEndDate));
            }
        } else {
            $events = null;
        }

        if ($communityType) {
            if (empty($search)) {
                $communitys = new ArrayCollection($communityRepo->findRecent());
            } else {
                $communitys = new ArrayCollection($communityRepo->findByLike($search));
            }
        } else {
            $communitys = null;
        }

        if (!empty($tags)) {
            $tagsSearch = explode("%2", $request->query->get('eventTags'));
            $trouve = false;
            foreach ($events as $event) {
                $trouve = false;
                foreach ($event->getTags() as $tag) {
                    if (in_array($tag->getName(), $tagsSearch)) {
                        $trouve = true;
                    }
                }
                if(!$trouve) {
                    $events->removeElement($event);
                }
            }
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
