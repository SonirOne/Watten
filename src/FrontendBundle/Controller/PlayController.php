<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use BackendBundle\Entity\Game;
use BackendBundle\Entity\Team;
use BackendBundle\Entity\Points;

class PlayController extends Controller {

    public function indexAction($gameid) {
        $em = $this->getDoctrine()->getManager();

        /* @var $game Game */
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameid));
        if ($game == null) {
            throw new NotFoundHttpException('Spiel konnte nicht gefunden werden');
        }

        $teams = $game->getTeams();
        $team1id = $teams[0]->getID();
        $team2id = $teams[1]->getID();

        $points = $em->getRepository('BackendBundle:Points')->findBy(array('game' => $gameid));

        return $this->render('@Frontend/play/index.html.twig', array(
                    'game' => $game,
                    'team1id' => $team1id,
                    'team2id' => $team2id,
                    'points' => $points
        ));
    }

    public function addPointsAction($gameid, Request $request) {
        $data = array();
        $data['state'] = 'ok';

        $gameID = $request->get('gameID');
        $groupNr = $request->get('groupNr');
        $teamID = $request->get('teamID');
        $points = $request->get('points');

        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameID));
        $team = $em->getRepository('BackendBundle:Team')->findOneBy(array('id' => $teamID));

        $addPoints = new Points();
        $addPoints->setGame($game);
        $addPoints->setTeam($team);
        $addPoints->setPoints($points);
        $addPoints->setCreatedAt(new \DateTime());
        $em->persist($addPoints);
        $em->flush();

        $rowHtml = $this->renderView('@Frontend/play/pointEntry.html.twig', array(
            'point' => $addPoints
        ));

        $data['html'] = $rowHtml;

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    public function removePointsAction($gameid, Request $request) {
        $data = array();
        $data['state'] = 'ok';

        $gameID = $request->get('gameID');
        $entryID = $request->get('entryID');

        $em = $this->getDoctrine()->getManager();
        $pointEntry = $em->getRepository('BackendBundle:Points')->findOneBy(array('id' => $entryID));
        if ($pointEntry != null) {
            $em->remove($pointEntry);
            $em->flush();
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

}
