<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePage extends AbstractController
{
    public function index(): Response
    {
        $user = $this->getUser();
        $events = $user->getEvent();

        return $this->render('home/index.html.twig', [
            'events' => $events
        ]);
    }
}
