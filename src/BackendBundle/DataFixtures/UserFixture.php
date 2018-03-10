<?php

namespace BackendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use BackendBundle\Entity\User;

class UserFixture extends Fixture {

    public function load(ObjectManager $manager) {

        $user = new User();
        $user->setUsername('Max');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Anna');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Moritz');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Susanne');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('Alexander');
        $manager->persist($user);

        $manager->flush();
    }

}
