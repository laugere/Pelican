<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{
    /**
     * @Route("/friend", name="friend")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findAll();

        return $this->render('friend/index.html.twig', [
            'users' => $users
        ]);
    }
}
