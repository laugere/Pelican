<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            "user" => $user,
            "menu" => 'user'
        ]);
    }

    /**
     * @Route("/user/{userId}/view", name="user_view")
     */
    public function viewUser($userId, UserRepository $userRepo)
    {
        $user = $userRepo->findOneById($userId);

        return $this->render('user/index.html.twig', [
            "user" => $user,
            "menu" => 'user'
        ]);
    }

    /**
     * @Route("/user/modify", name="user_modify")
     */
    public function modifyUser(RegistrationFormType $registrationForm): Response {
        return $this->render('user/modify.html.twig', [
            "menu" => 'user',
        ]);
    }
}
