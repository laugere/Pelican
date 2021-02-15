<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\GoToEventRepository;
use App\Repository\IsInRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePage extends AbstractController
{
    public function index(IsInRepository $isInRepo, GoToEventRepository $goToEventRepo): Response
    {
        $user = $this->getUser();
        $communitys = $isInRepo->findCommunityGoTo($user->getId());
        $events = $goToEventRepo->findCommunityGoTo($user->getId());

        return $this->render('home/index.html.twig', [
            'activeController' => 'Home',
            'communitys' => $communitys,
            'events' => $events
        ]);
    }
}
