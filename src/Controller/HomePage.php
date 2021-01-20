<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePage extends AbstractController
{
    public function index(): Response
    {
        return $this->render('homePage.twig', ['page' => 0]);
    }
}
