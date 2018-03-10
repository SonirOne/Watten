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
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameid));
        if ($game == null) {
            throw new NotFoundHttpException('Spiel konnte nicht gefunden werden');
        }

        return $this->render('@Frontend/play/index.html.twig', array(
                    'game' => $game
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
            'groupNr' => $groupNr,
            'points' => $points
        ));

        $data['html'] = $rowHtml;

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    public function removePointsAction($gameid, Request $request) {
        
    }

}
