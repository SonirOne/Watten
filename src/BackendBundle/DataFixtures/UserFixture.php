<?php

namespace BackendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use BackendBundle\Entity\User;

class UserFixture extends Fixture {

    public function load(ObjectManager $manager) {

        $user = new User();
        $user->setName('Max');
        $manager->persist($user);

        $user = new User();
        $user->setName('Anna');
        $manager->persist($user);

        $user = new User();
        $user->setName('Moritz');
        $manager->persist($user);

        $user = new User();
        $user->setName('Susanne');
        $manager->persist($user);

        $user = new User();
        $user->setName('Alexander');
        $manager->persist($user);

        $manager->flush();
    }

}
