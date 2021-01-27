<?php

namespace App\Controller;

use App\Repository\IsInRepository;

use App\Entity\Community;
use App\Entity\IsIn;
use App\Form\CommunityType;
use App\Repository\CommunityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
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
    public function index(CommunityRepository $community, IsInRepository $isIn): Response
    {
        $user = $this->getUser();
        $isInCommunitys = $isIn->findCommunityGoTo($user->getId());

        $communitys = $community->findRecent();

        return $this->render('community/index.html.twig', [
            'controller_name' => 'Communautés',
            'communitys' => $communitys,
            'isInCommunitys' => $isInCommunitys
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

            return $this->redirectToRoute('community', [], 301);
        }

        return $this->render('community/create.html.twig', [
            'controller_name' => 'Créer une communauté',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/community/{communityId}/goto", name="community_goto")
     */
    public function goToCommunity($communityId, ObjectManager $objectManager, IsInRepository $isInRepo): Response
    {
        $user = $this->getuser();

        if(!$user) return $this->json([
            'code' => 403,
        ], 403);

        if(!$isInRepo->userIsIn($user->getId(), $communityId)) {
            $isIn = new IsIn();
            $isIn->setIdCommunity($communityId);
            $isIn->setIdUser($user->getId());
            $objectManager->persist($isIn);
            $objectManager->flush();
        }
        else {
            $isInRepo->deleteIsIn($user->getId(), $communityId);
        }
        
        return $this->json(['code' => 200], 200);
    }
}
