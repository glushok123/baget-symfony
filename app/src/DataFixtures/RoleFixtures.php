<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    const MODULES = [
        [
            'name' => 'USER',
            'nameRu' => 'Пользователя'
        ],
        [
            'name' => 'ORGANIZATION_TYPE',
            'nameRu' => 'Типа организации'
        ],
        [
            'name' => 'ORGANIZATION',
            'nameRu' => 'Организации'
        ],
        [
            'name' => 'PRODUCT',
            'nameRu' => 'Товара'
        ],
        [
            'name' => 'CATEGORY',
            'nameRu' => 'Категории'
        ],
        [
            'name' => 'FILTER',
            'nameRu' => 'Фильтра'
        ],
        [
            'name' => 'PRICE',
            'nameRu' => 'Цены'
        ],
        [
            'name' => 'ORDER',
            'nameRu' => 'Заказа'
        ],
        [
            'name' => 'DELIVERY',
            'nameRu' => 'Доставок'
        ],
        [
            'name' => 'FEEDBACK',
            'nameRu' => 'Обратной связи'
        ],
        [
            'name' => 'NEWS',
            'nameRu' => 'Новости'
        ],
        [
            'name' => 'API_TOKEN',
            'nameRu' => 'токена API'
        ],
    ];

    const ACTIONS = [
        [
           'name' => 'READ',
           'nameRu' => 'Чтение'
        ],
        [
            'name' => 'EDIT',
            'nameRu' => 'Редактирование'
        ],
        [
            'name' => 'DELETE',
            'nameRu' => 'Удаление'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        if (empty($manager->getRepository('\\App\\Entity\\Role')->findBy(['name' => 'ROLE_USER']))) {
            $user = new Role();
            $user->setModule('MAIN');
            $user->setName('ROLE_USER');
            $user->setNameRu('Пользователь');
            $manager->persist($user);
        }

        if (empty($manager->getRepository('\\App\\Entity\\Role')->findBy(['name' => 'ROLE_ADMIN']))) {
            $admin = new Role();
            $admin->setModule('MAIN');
            $admin->setName('ROLE_ADMIN');
            $admin->setNameRu('Админ');
            $manager->persist($admin);
        }

        if (empty($manager->getRepository('\\App\\Entity\\Role')->findBy(['name' => 'ROLE_SUPER_ADMIN']))) {
            $superAdmin = new Role();
            $superAdmin->setModule('MAIN');
            $superAdmin->setName('ROLE_SUPER_ADMIN');
            $superAdmin->setNameRu('СуперАдмин');
            $manager->persist($superAdmin);
        }

        foreach (self::MODULES as $module) {
            foreach (self::ACTIONS as $action) {
                $name = 'ROLE_' . $module['name'] . '_' . $action['name'];
                if (!empty($manager->getRepository('\\App\\Entity\\Role')->findBy(['name' => $name]))) {
                    continue;
                }
                $role = new Role();
                $role->setModule($module['name']);
                $role->setName($name);
                $role->setNameRu($action['nameRu'] . ' ' . $module['nameRu']);
                $manager->persist($role);
            }
        }

        $manager->flush();
    }
}
