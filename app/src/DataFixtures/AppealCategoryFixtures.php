<?php

namespace App\DataFixtures;

use App\Entity\Appeal\AppealCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppealCategoryFixtures extends Fixture
{
    const CATEGORY = [
        'Обращение в поддержку',
        'Претензии по качеству',
        'Претензии по количеству, пересорту и коммерческим возвратам',
    ];

    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY as $categoryFixture) {
            if ($manager->getRepository('\\App\\Entity\\Appeal\\AppealCategory')->findBy(['name' => $categoryFixture])) {
                continue;
            }

            $category = new AppealCategory();
            $category->setName($categoryFixture);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
