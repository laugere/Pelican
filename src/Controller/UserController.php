<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\File;

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
    public function modifyUser(Request $request, ObjectManager $objectManager): Response
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->add('email', null, ['label' => false, 'attr' => array(
                'id' => 'inputEmail',
                'placeholder' => 'security.register.email'
            )])
            ->add('city', null, ['label' => false, 'attr' => array(
                'id' => 'inputCity',
                'placeholder' => 'security.register.city'
            )])
            ->add('pseudo', null, ['label' => false, 'attr' => array(
                'id' => 'inputPseudo',
                'placeholder' => 'security.register.pseudo'
            )])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'delete_label' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => false,
                'attr' => array(
                    'id' => 'inputImage',
                    'placeholder' => 'security.register.image',
                    'onchange' => 'loadFile(event)'
                )
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager->persist($user);
            $objectManager->flush();
        } else {
            $imageFile = new File('./uploads/images/events/' . $user->getImage());
            $form->setData(array("imageFile" => $imageFile));
        }

        return $this->render('user/modify.html.twig', [
            "form" => $form->createView(),
            "menu" => 'user',
        ]);
    }

    /**
     * @Route("/user/{userId}/event/view", name="user_event_view")
     */
    public function eventView($userId, UserRepository $userRepo)
    {
        $user = $userRepo->findOneById($userId);
        $events = $user->getEventsCreated();

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'menu' => 'event'
        ]);
    }
}
