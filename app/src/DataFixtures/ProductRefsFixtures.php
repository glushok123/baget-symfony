<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductRefsFixtures extends Fixture
{
    const REFS = [
        [
            "id" => "90733",
            "child_id" => "75145"
        ],
        [
            "id" => "90821",
            "child_id" => "75145"
        ],
        [
            "id" => "90086",
            "child_id" => "90127"
        ],
        [
            "id" => "90086",
            "child_id" => "90136"
        ],
        [
            "id" => "90733",
            "child_id" => "90289"
        ],
        [
            "id" => "90086",
            "child_id" => "90733"
        ],
        [
            "id" => "90086",
            "child_id" => "90821"
        ],
        [
            "id" => "90127",
            "child_id" => "96112"
        ],
        [
            "id" => "90086",
            "child_id" => "97410"
        ],
        [
            "id" => "90086",
            "child_id" => "97968"
        ],
        [
            "id" => "90733",
            "child_id" => "99898"
        ],
        [
            "id" => "90821",
            "child_id" => "99898"
        ],
        [
            "id" => "90136",
            "child_id" => "101533"
        ],
        [
            "id" => "104505",
            "child_id" => "101533"
        ],
        [
            "id" => "90127",
            "child_id" => "101922"
        ],
        [
            "id" => "97410",
            "child_id" => "101922"
        ],
        [
            "id" => "90086",
            "child_id" => "103619"
        ],
        [
            "id" => "90086",
            "child_id" => "104505"
        ],
        [
            "id" => "101056",
            "child_id" => "104666"
        ]
    ];

    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::REFS as $refFixture) {

            if (
                empty($manager->getRepository('\\App\\Entity\\Product\\Product')->findOneBy(['externalId' => $refFixture['id']])) &&
                empty($manager->getRepository('\\App\\Entity\\Product\\Product')->findOneBy(['externalId' => $refFixture['child_id']]))
                )
            {
                continue;
            }

            $product = $manager->getRepository('\\App\\Entity\\Product\\Product')->findOneBy(['externalId' => $refFixture['id']]);
            $product->addChild($manager->getRepository('\\App\\Entity\\Product\\Product')->findOneBy(['externalId' => $refFixture['child_id']]));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
