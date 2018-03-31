<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GamesController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('BackendBundle:Game')->findAll();

        return $this->render('@Frontend/games/index.html.twig', array(
                    'games' => $games
        ));
    }

}
