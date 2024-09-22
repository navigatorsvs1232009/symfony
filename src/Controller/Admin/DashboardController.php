<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/admin')]
class DashboardController extends AbstractController
{
    #[Route(path: '/dashboard', name: 'admin_dashboard_show')]
    public function dashboard(): Response
    {
        return $this->render('admin/pages/dashboard.html.twig');
    }
}
