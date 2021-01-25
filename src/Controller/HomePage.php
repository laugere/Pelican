<?php

namespace App\Controller;

use App\Repository\IsInRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePage extends AbstractController
{
    public function index(IsInRepository $isIn): Response
    {
        $user = $this->getUser();
        $communitys = $isIn->findRecent($user->getId());

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Accueil',
            'communitys' => $communitys
        ]);
    }
}
