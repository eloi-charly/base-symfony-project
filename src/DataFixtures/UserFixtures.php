<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private const ADMIN = "ADMIN_USER";
    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("Admin")
            ->setEmail("admin@app.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setIsVerified(true)
            ->setApiToken("admin_token")
            ->setPassword($this->hasher->hashPassword($user, "admin"));
        $this->addReference(self::ADMIN, $user);


        $manager->persist($user);


        for ($i = 0; $i < 10; $i++) {
            $user = (new User())->setUsername("User-$i")
                ->setEmail("user$i@app.com")
                ->setRoles(["ROLE_USER"])
                ->setIsVerified(true)
                ->setApiToken("user$i@_token")
                ->setPassword($this->hasher->hashPassword($user, "0000"));
            $this->addReference("USER" . $i, $user);
            $users[] = $user;
            $manager->persist($user);
        }
        $manager->flush();
    }
}
