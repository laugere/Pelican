<?php

namespace App\EventSubscriber;

use App\Entity\Settings;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    // Langue par défaut
    private $defaultLocale;
    private $defaultTheme;

    public function __construct($defaultLocale = 'fr_FR', $defaultTheme = true)
    {
        $this->defaultLocale = $defaultLocale;
        $this->defaultTheme = $defaultTheme;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $locale = $request->getSession()->get('_locale');

        if ($locale == null) {
            $request->getSession()->set('_locale', $this->defaultLocale);
        }

        if ($locale != null) {
            $request->setLocale($locale);
        } else {
            $request->setLocale($this->defaultLocale);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // On doit définir une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
