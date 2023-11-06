<?php

namespace App\DataFixtures;

use App\Entity\Appeal\AppealStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppealStatusFixtures extends Fixture
{
    const STATUS = [
        'Новое обращение',
        'Ответ службы поддержки',
        'Ответ клиента',
        'Закрыто',
    ];

    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::STATUS as $statusFixture) {
            if ($manager->getRepository('\\App\\Entity\\Appeal\\AppealStatus')->findBy(['name' => $statusFixture])) {
                continue;
            }

            $status = new AppealStatus();
            $status->setName($statusFixture);

            $manager->persist($status);
        }

        $manager->flush();
    }
}
