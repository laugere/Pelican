<?php

namespace App\Controller;

use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    /**
     * @Route("/settings", name="settings")
     */
    public function index(Request $request, ObjectManager $objectManager): Response
    {
        $user = $this->getUser();
        $settings = $user->getSettings();
        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectManager->persist($settings);
            $objectManager->flush();

            $request->getSession()->set('_locale', $settings->getLanguage());
            $request->getSession()->set('_theme', $settings->getDarkMode());

            return $this->redirectToRoute('settings', [], 301);
        }

        return $this->render('settings/index.html.twig', [
            'activeController' => 'Settings',
            'form' => $form->createView(),
            'menu' => 'settings'
        ]);
    }
}
