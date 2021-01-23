<?php

namespace App\Controller;

use App\Entity\Community;
use App\Form\CommunityType;
use App\Repository\CommunityRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommunityController extends AbstractController
{
    /**
     * @Route("/community", name="community")
     */
    public function index(CommunityRepository $community): Response
    {
        $communitys = $community->findRecent();

        return $this->render('community/index.html.twig', [
            'controller_name' => 'Communautés',
            'communitys' => $communitys
        ]);
    }

    /**
     * @Route("/community/create", name="community_create")
     */
    public function create(Community $community = null, Request $request, ObjectManager $objectManager): Response
    {
        $community = new Community();
        $form = $this->createForm(CommunityType::class, $community);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $community->setDateCreation(new \DateTime());
            $community->setDateModification(new \DateTime());

            $objectManager->persist($community);
            $objectManager->flush();
        }

        return $this->render('community/create.html.twig', [
            'controller_name' => 'Créer une communauté',
            'form' => $form->createView()
        ]);
    }
}
