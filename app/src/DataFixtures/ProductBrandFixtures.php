<?php

namespace App\DataFixtures;

use App\Entity\Product\ProductBrand;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductBrandFixtures extends Fixture
{
    const BRAND = [
        [
            "vendorid" => "1",
            "name" => "Avision"
        ],
        [
            "vendorid" => "2",
            "name" => "Bulat"
        ],
        [
            "vendorid" => "3",
            "name" => "Brother"
        ],
        [
            "vendorid" => "4",
            "name" => "Canon"
        ],
        [
            "vendorid" => "5",
            "name" => "Epson"
        ],
        [
            "vendorid" => "6",
            "name" => "HP"
        ],
        [
            "vendorid" => "7",
            "name" => "Konica Minolta"
        ],
        [
            "vendorid" => "8",
            "name" => "Kyocera"
        ],
        [
            "vendorid" => "9",
            "name" => "Lexmark"
        ],
        [
            "vendorid" => "10",
            "name" => "Oki"
        ],
        [
            "vendorid" => "11",
            "name" => "Panasonic"
        ],
        [
            "vendorid" => "12",
            "name" => "Pantum"
        ],
        [
            "vendorid" => "13",
            "name" => "Ricoh"
        ],
        [
            "vendorid" => "14",
            "name" => "Sharp"
        ],
        [
            "vendorid" => "15",
            "name" => "Samsung"
        ],
        [
            "vendorid" => "16",
            "name" => "Toshiba"
        ],
        [
            "vendorid" => "17",
            "name" => "Xerox"
        ],
        [
            "vendorid" => "18",
            "name" => "Seiko"
        ],
        [
            "vendorid" => "19",
            "name" => "Kip"
        ],
        [
            "vendorid" => "20",
            "name" => "Tonex"
        ]
    ];

    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::BRAND as $brandFixture) {
            if ($manager->getRepository('\\App\\Entity\\Product\\ProductBrand')->findBy(['name' => $brandFixture['name']])) {
                continue;
            }

            $brand = new ProductBrand();
            $brand->setName($brandFixture['name']);
            $brand->setExternalId($brandFixture['vendorid']);
            $brand->setCreatedAt(new DateTimeImmutable());

            $manager->persist($brand);
        }

        $manager->flush();
    }
}
