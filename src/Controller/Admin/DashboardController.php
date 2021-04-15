<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Community;
use App\Entity\Friendship;
use App\Entity\IsInCommunity;
use App\Entity\Participation;
use App\Entity\User;
use App\Entity\Settings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('Pelican')
        ;
    }


    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Objets'),
            MenuItem::linkToCrud('Evenements', 'fa fa-calendar-alt', Event::class),
            MenuItem::linkToCrud('Communautés', 'fa fa-campground', Community::class),
            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Settings', 'fa fa-cog', Settings::class),
            MenuItem::section('Relation'),
            MenuItem::linkToCrud('Amitiés', 'fa fa-user-friends', Friendship::class),
            MenuItem::linkToCrud('Participations', 'fa fa-calendar-week', Participation::class),
            MenuItem::linkToCrud('Is in Community', 'fa fa-exchange-alt', IsInCommunity::class),
        ];
    }
}
