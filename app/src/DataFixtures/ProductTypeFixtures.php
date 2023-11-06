<?php

namespace App\DataFixtures;

use App\Entity\Product\ProductType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductTypeFixtures extends Fixture
{
    const TYPE = [
        [
            "typeid" => "1",
            "divid" => "1",
            "name" => "Принтер"
        ],
        [
            "typeid" => "2",
            "divid" => "1",
            "name" => "МФУ (Многофункциональное устройство)"
        ],
        [
            "typeid" => "3",
            "divid" => "1",
            "name" => "Плоттер"
        ],
        [
            "typeid" => "4",
            "divid" => "1",
            "name" => "Факс"
        ],
        [
            "typeid" => "5",
            "divid" => "1",
            "name" => "КМА (Копировально-множительный аппарат)"
        ],
        [
            "typeid" => "6",
            "divid" => "1",
            "name" => "Цифровая типография"
        ],
        [
            "typeid" => "7",
            "divid" => "2",
            "name" => "Картриджи для лазерных печатающих устройств"
        ],
        [
            "typeid" => "8",
            "divid" => "2",
            "name" => "Драм-картриджи"
        ],
        [
            "typeid" => "9",
            "divid" => "2",
            "name" => "Блоки  проявки"
        ],
        [
            "typeid" => "10",
            "divid" => "2",
            "name" => "Блоки лент переноса изображения"
        ],
        [
            "typeid" => "11",
            "divid" => "2",
            "name" => "Блоки для технического обслуживания лазерных ПУ"
        ],
        [
            "typeid" => "12",
            "divid" => "2",
            "name" => "Термоузлы"
        ],
        [
            "typeid" => "13",
            "divid" => "2",
            "name" => "Ремкомплекты к устройствам"
        ],
        [
            "typeid" => "14",
            "divid" => "2",
            "name" => "Бункеры для отработанного тонера"
        ],
        [
            "typeid" => "15",
            "divid" => "2",
            "name" => "Картриджи для струйных печатающих устройств"
        ],
        [
            "typeid" => "16",
            "divid" => "2",
            "name" => "Чернила для устройств со встроенной СНПЧ"
        ],
        [
            "typeid" => "17",
            "divid" => "2",
            "name" => "Ёмкости для отработанных чернил"
        ],
        [
            "typeid" => "18",
            "divid" => "2",
            "name" => "Картриджи для матричных печатающих устройств"
        ],
        [
            "typeid" => "19",
            "divid" => "3",
            "name" => "Тонеры"
        ],
        [
            "typeid" => "20",
            "divid" => "3",
            "name" => "Девелоперы"
        ],
        [
            "typeid" => "21",
            "divid" => "3",
            "name" => "Чернила"
        ],
        [
            "typeid" => "22",
            "divid" => "4",
            "name" => "Комплектующие"
        ],
        [
            "typeid" => "23",
            "divid" => "4",
            "name" => "Комплектующие к блокам переноса изображения"
        ],
        [
            "typeid" => "24",
            "divid" => "4",
            "name" => "Комплектующие к термоузлам"
        ],
        [
            "typeid" => "25",
            "divid" => "4",
            "name" => "Ремкомплекты"
        ],
        [
            "typeid" => "26",
            "divid" => "4",
            "name" => "Фоторецепторы"
        ],
        [
            "typeid" => "27",
            "divid" => "4",
            "name" => "Валы"
        ],
        [
            "typeid" => "28",
            "divid" => "4",
            "name" => "Ракели"
        ],
        [
            "typeid" => "29",
            "divid" => "4",
            "name" => "Чипы"
        ],
        [
            "typeid" => "30",
            "divid" => "4",
            "name" => "ЗИП"
        ],
        [
            "typeid" => "31",
            "divid" => "5",
            "name" => "Станции очистки и ЗИП к ним"
        ],
        [
            "typeid" => "32",
            "divid" => "5",
            "name" => "Пылесосы и ЗИП к ним"
        ],
        [
            "typeid" => "33",
            "divid" => "5",
            "name" => "Инструмент"
        ],
        [
            "typeid" => "34",
            "divid" => "5",
            "name" => "Чистящие средства"
        ],
        [
            "typeid" => "35",
            "divid" => "5",
            "name" => "Масла и смазки"
        ],
        [
            "typeid" => "36",
            "divid" => "5",
            "name" => "Упаковочные материалы"
        ]
    ];
    public function __construct(
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::TYPE as $categoryFixture) {
            if ($manager->getRepository('\\App\\Entity\\Product\\ProductType')->findBy(['name' => $categoryFixture['name']])) {
                continue;
            }

            $type = new ProductType();
            $type->setName($categoryFixture['name']);
            $type->setExternalId($categoryFixture['typeid']);
            $type->setCategory($manager->getRepository('\\App\\Entity\\Product\\ProductCategory')->findOneBy(['externalId' => $categoryFixture['divid']]));
            $type->setCreatedAt(new DateTimeImmutable());

            $manager->persist($type);
        }


        $manager->flush();
    }
}
