<?php

namespace App\DataFixtures;

use App\Entity\RoleGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleGroupFixtures extends Fixture
{
    const NAME = [
        'SUPER_ADMIN' => '*',
        'OWNER' => [
            'ROLE_USER_READ',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            'ROLE_ORGANIZATION_READ',
            'ROLE_ORGANIZATION_EDIT',
            'ROLE_ORGANIZATION_DELETE',
            'ROLE_ORGANIZATION_TYPE_READ',
        ],
        'EMPLOYEE' => [
            'ROLE_USER_READ',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            'ROLE_ORGANIZATION_READ',
            'ROLE_ORGANIZATION_TYPE_READ',
        ],
        'PROVIDER' => [
            'ROLE_USER_READ',
            'ROLE_USER_EDIT',
            'ROLE_USER_DELETE',
            'ROLE_ORGANIZATION_READ',
            'ROLE_ORGANIZATION_TYPE_READ',
            'ROLE_PRODUCT_READ',
            'ROLE_PRODUCT_EDIT',
            'ROLE_PRODUCT_DELETE',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAME as $key => $value) {
                $name = 'GROUP_' . $key;

                if (!empty($manager->getRepository('\\App\\Entity\\RoleGroup')->findBy(['name' => $name]))) {
                    if ($value == '*') {
                        foreach($manager->getRepository('\\App\\Entity\\Role')->findAll() as $role) {
                            $roleGroup = $manager->getRepository('\\App\\Entity\\RoleGroup')->findOneBy(['name' => $name]);
                            $roleGroup->addRole($role);
                            $manager->persist($roleGroup);
                        }
                    }else{
                        foreach($value as $nameRole) {
                            $roleGroup = $manager->getRepository('\\App\\Entity\\RoleGroup')->findOneBy(['name' => $name]);
                            $role = $manager->getRepository('\\App\\Entity\\Role')->findOneBy(['name' => $nameRole]);
                            $roleGroup->addRole($role);
                            $manager->persist($roleGroup);
                        }
                    }

                    continue;
                }

                $roleGroup = new RoleGroup();
                $roleGroup->setName($name);

                if ($value == '*') {
                    foreach($manager->getRepository('\\App\\Entity\\Role')->findAll() as $role) {
                        $roleGroup->addRole($role);
                    }
                }else{
                    foreach($value as $nameRole) {
                        $role = $manager->getRepository('\\App\\Entity\\Role')->findOneBy(['name' => $nameRole]);
                        $roleGroup->addRole($role);
                    }
                }

                $manager->persist($roleGroup);
        }

        $manager->flush();
    }
}
