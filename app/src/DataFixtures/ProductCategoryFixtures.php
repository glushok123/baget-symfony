<?php

namespace App\DataFixtures;

use App\Entity\Product\ProductCategory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductCategoryFixtures extends Fixture
{
    const CATEGORY = [
        [
            "divid" => "1",
            "name" => "Печатающие устройства"
        ],
        [
            "divid" => "2",
            "name" => "Картриджи и блоки"
        ],
        [
            "divid" => "3",
            "name" => "Тонеры, девелоперы и чернила"
        ],
        [
            "divid" => "4",
            "name" => "Материалы для восстановления картриджей и блоков"
        ],
        [
            "divid" => "5",
            "name" => "Оборудование и принадлежности для сервиса"
        ]
    ];

    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORY as $categoryFixture) {
            if ($manager->getRepository('\\App\\Entity\\Product\\ProductCategory')->findBy(['name' => $categoryFixture['name']])) {
                continue;
            }

            $category = new ProductCategory();
            $category->setName($categoryFixture['name']);
            $category->setExternalId($categoryFixture['divid']);
            $category->setCreatedAt(new DateTimeImmutable());

            $manager->persist($category);
        }

        $manager->flush();
    }
}
