<?php

namespace App\Admin\Controller;

use App\Core\Infrastructure\Entity\Category;
use App\Core\Infrastructure\Entity\Task;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('FastJob');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Главная', 'fa fa-home');
        yield MenuItem::linkToCrud('Категории', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Задачи', 'fas fa-list', Task::class);
    }
}
