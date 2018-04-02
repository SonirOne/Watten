<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BackendBundle\Entity\Points;

class GamesController extends Controller {

    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository('BackendBundle:Game')->findAll();

        return $this->render('@Frontend/games/index.html.twig', array(
                    'games' => $games
        ));
    }

    public function resultAction($gameid) {
        $em = $this->getDoctrine()->getManager();

        /* @var $game Game */
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameid));
        if ($game == null) {
            throw new NotFoundHttpException('Spiel konnte nicht gefunden werden');
        }

        $teams = $game->getTeams();
        $team1id = $teams[0]->getID();
        $team2id = $teams[1]->getID();

        $sumPoints = array();
        $sumPoints[$team1id] = 0;
        $sumPoints[$team2id] = 0;

        $arrPoints = $em->getRepository('BackendBundle:Points')->findBy(array('game' => $gameid));
        /* @var $point Points */
        foreach ($arrPoints as $point) {
            $teamID = $point->getTeam()->getId();
            $points = $point->getPoints();
            $sumPoints[$teamID] += $points;
        }

        return $this->render('@Frontend/games/result.html.twig', array(
                    'game' => $game,
                    'team1id' => $team1id,
                    'team2id' => $team2id,
                    'points' => $arrPoints,
                    'sumPoints' => $sumPoints
        ));
    }

}
