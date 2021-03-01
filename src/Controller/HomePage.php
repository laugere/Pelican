<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController
{
    public function index(): Response
    {
        $datetime = new \DateTime("now");
        $user = $this->getUser();
        $events = new ArrayCollection();
        foreach ($user->getEvent() as $participation) {
            if ($datetime <= $participation->getEvent()->getDate()) {
                $events->add($participation->getEvent());
            }
        }
        $iterator = $events->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getdate() < $b->getdate()) ? -1 : 1;
        });
        $events = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('home/index.html.twig', [
            'events' => $events
        ]);
    }
}
