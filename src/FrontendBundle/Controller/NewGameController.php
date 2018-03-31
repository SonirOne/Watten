<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use BackendBundle\Entity\Team;
use BackendBundle\Entity\Game;
use BackendBundle\Entity\User;

class NewGameController extends Controller {

    public function newAction() {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('BackendBundle:User')->findAll();

        return $this->render('@Frontend/game/new.html.twig', array(
                    'users' => $users
        ));
    }

    public function createAction(Request $request) {
        $data = array();
        $data['state'] = 'ok';

        $maxPoints = $request->get('maxPoints');
        $arrTeam1 = $request->get('team1');
        $arrTeam2 = $request->get('team2');

        if ($arrTeam1 == null || $arrTeam2 == null || count($arrTeam1) != 2 || count($arrTeam2) != 2) {
            $data['state'] = 'error';
            $data['errorMessage'] = 'Error with teams';
            $data['team1'] = $arrTeam1;
            $data['team2'] = $arrTeam2;
        } else {
            if (empty($maxPoints)) {
                $maxPoints = 15;
            }

            $em = $this->getDoctrine()->getManager();

            $game = new Game();
            $game->setCreatedAt(new DateTime());
            $game->setWinningPoints($maxPoints);
            $this->addTeam($game, 'Team1', $arrTeam1);
            $this->addTeam($game, 'Team2', $arrTeam2);
            $em->persist($game);
            $em->flush();

            $data['gameid'] = $game->getId();
            $data['gameurl'] = $this->generateUrl('frontend_play_game', array('gameid' => $game->getId()));
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    /* @var $game Game */

    private function addTeam(&$game, $groupName, $arrUserIDs) {
        $em = $this->getDoctrine()->getManager();

        /* @var $team Team */
        $team = new Team();
        $team->setTeamname($groupName);
        $team->setCreatedAt(new DateTime());
        foreach ($arrUserIDs as $index => $userid) {
            /* @var $user User */
            $user = $em->getRepository('BackendBundle:User')->findOneBy(array('id' => $userid));
            if ($user != null) {
                $team->addUser($user);
            }
        }

        $game->addTeam($team);
    }

}
