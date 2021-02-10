<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function index(Request $request, ObjectManager $objectManager, SettingsRepository $settingsRepo): Response
    {
        $user = $this->getuser();
        $settings = $settingsRepo->getSettings($user->getId());
        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager->persist($settings);
            $objectManager->flush();

            return $this->redirectToRoute('settings', [], 301);
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
