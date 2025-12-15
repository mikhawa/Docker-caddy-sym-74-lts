<?php

// src/DataFixtures/UserFixture.php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur admin
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setEmail('michaeljpitz@gmail.com');
        $adminUser->setUniqID(uniqid('uq_',true));
        $adminUser->setStatus(1); // statut actif
        $adminUser->setDateInscription(new \DateTimeImmutable());
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'admin1234');
        $adminUser->setPassword($hashedPassword);
        $manager->persist($adminUser);
        $manager->flush();
    }
}