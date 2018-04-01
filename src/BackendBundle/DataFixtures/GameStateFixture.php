<?php

namespace BackendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use BackendBundle\Entity\GameState;

class GameStateFixture extends Fixture {

    public function load(ObjectManager $manager) {

        $stateRunning=new GameState();
        $stateRunning->setText('Läuft');
        $manager->persist($stateRunning);
        
        $stateFinished=new GameState();
        $stateFinished->setText('Beendet');
        $manager->persist($stateFinished);

        $manager->flush();
    }

}
