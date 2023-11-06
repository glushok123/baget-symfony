<?php

namespace App\Controller\Admin;

use App\Entity\Appeal\Appeal;
use App\Entity\Appeal\AppealCategory;
use App\Entity\Appeal\AppealMessage;
use App\Entity\Appeal\AppealStatus;
use App\Entity\Article;
use App\Entity\Organization\Organization;
use App\Entity\Product\Product;
use App\Entity\Product\ProductBrand;
use App\Entity\Product\ProductCategory;
use App\Entity\Product\ProductModel;
use App\Entity\Product\ProductType;
use App\Entity\RoleGroup;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/', name: 'admin', host: '%admin_url%')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(RoleGroupCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App')
            ->disableDarkMode();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Организации', 'fas fa-list', Organization::class);
        yield MenuItem::linkToCrud('Роли', 'fas fa-list', RoleGroup::class);
        yield MenuItem::linkToCrud('Новости', 'fas fa-list', Article::class);

        yield MenuItem::subMenu('Управление каталогом', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Товары', 'fas fa-list', Product::class),
            MenuItem::linkToCrud('Категории', 'fas fa-list', ProductCategory::class),
            MenuItem::linkToCrud('Бренды', 'fas fa-list', ProductBrand::class),
            MenuItem::linkToCrud('Модели', 'fas fa-list', ProductModel::class),
            MenuItem::linkToCrud('Типы', 'fas fa-list', ProductType::class),
        ]);

        yield MenuItem::subMenu('Обращения/претензии', 'fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Обращения', 'fas fa-list', Appeal::class),
            MenuItem::linkToCrud('Категории', 'fas fa-list', AppealCategory::class),
            MenuItem::linkToCrud('Статусы', 'fas fa-list', AppealStatus::class),
            MenuItem::linkToCrud('Сообщения', 'fas fa-list', AppealMessage::class),
        ]);
    }
}
