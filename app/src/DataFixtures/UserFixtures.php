<?php

namespace App\DataFixtures;

use App\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use libphonenumber\PhoneNumber;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        if ($manager->getRepository('\\App\\Entity\\User\\User')->findBy(['email' => 'admin@admin.ru'])) {
            return;
        }
        $user = new User();
        $user->setEmail('admin@admin.ru');
        $user->setPhone((new PhoneNumber())->setRawInput('+79991234567'));
        $user->setBirthday(new DateTimeImmutable());
        $user->setSex(true);
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setName('admin');
        $user->setPassword($this->hasher->hashPassword($user, '123456'));
        $user->setConfirmEmail(true);

        $manager->persist($user);
        $manager->flush();
    }
}
