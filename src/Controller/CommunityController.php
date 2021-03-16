<?php

namespace App\Controller;

use App\Entity\Community;
use App\Entity\IsInCommunity;
use App\Entity\Participation;

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
    public function index(CommunityRepository $communityRepo, Request $request): Response
    {
        if($request->query->get('search') != null) {
            $communitys = $communityRepo->findByLike($request->query->get('search'));
        } else {
            $communitys = $communityRepo->findRecent();
        }

        return $this->render('community/index.html.twig', [
            'communitys' => $communitys,
            'menu' => 'community'
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
            'form' => $form->createView(),
            'menu' => 'community'
        ]);
    }

    /**
     * @Route("/community/{communityId}/goto", name="community_goto")
     */
    public function goToCommunity($communityId, ObjectManager $objectManager, CommunityRepository $communityRepo): Response
    {
        $user = $this->getuser();

        if (!$user) return $this->json([
            'code' => 403,
        ], 403);

        $community = $communityRepo->findById($communityId);
        $participation = null;
        $trouve = false;

        foreach ($community->getIsincommunity() as $participant) {
            if ($participant->getUser() == $user) {
                $trouve = true;
                $participation = $participant;
            }
        }

        if ($trouve) {
            $objectManager->remove($participation);
            $objectManager->flush();
        } else {
            $participation = new IsInCommunity();
            $participation->setDate(new \DateTime());
            $participation->setUser($user);
            $participation->setCommunity($community);
            $objectManager->persist($participation);
            $objectManager->flush();
        }

        return $this->json(['code' => 200], 200);
    }
}
