<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePage extends AbstractController
{
    public function index(): Response
    {
        $number = random_int(0, 100);

        return $this->render('HomePage.twig', [
            'userName' => "test",
        ]);
    }
}
