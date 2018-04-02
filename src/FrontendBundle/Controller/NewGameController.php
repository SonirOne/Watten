<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DateTime;
use BackendBundle\Entity\Team;
use BackendBundle\Entity\Game;
use BackendBundle\Entity\GameState;
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
            $this->addTeam($game, $arrTeam1);
            $this->addTeam($game, $arrTeam2);

            /* @var $gameState GameState */
            $gameState = $em->getRepository('BackendBundle:GameState')->findOneBy(array('text' => 'Läuft'));
            $game->setGameState($gameState);

            $em->persist($game);
            $em->flush();

            $data['gameid'] = $game->getId();
            $data['gameurl'] = $this->generateUrl('frontend_play_game', array('gameid' => $game->getId()));
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    public function revancheAction($gameid) {
        $em = $this->getDoctrine()->getManager();

        /* @var $previousGame Game */
        $previousGame = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameid));
        if ($previousGame == null) {
            throw new NotFoundHttpException('Spiel konnte nicht gefunden werden');
        }

        $newGameState = $em->getRepository('BackendBundle:GameState')->findOneBy(array('id' => 1));

        /* @var $newGame Game */
        $newGame = new Game();
        $newGame->setGameState($newGameState);
        $newGame->setWinningPoints($previousGame->getWinningPoints());
        $newGame->setCreatedAt(new DateTime());

        $teams = $previousGame->getTeams();
        foreach ($teams as $team) {
            $newGame->addTeam($team);
        }

        $em->persist($newGame);
        $em->flush();

        $newGameID = $newGame->getId();

        return $this->redirectToRoute('frontend_play_game', array('gameid' => $newGameID));
    }

    /* @var $game Game */

    private function addTeam(&$game, $arrUserIDs) {
        $em = $this->getDoctrine()->getManager();

        /* @var $team Team */
        $team = new Team();
        $team->setCreatedAt(new DateTime());
        $tmpTeamName = '';
        foreach ($arrUserIDs as $index => $userid) {
            /* @var $user User */
            $user = $em->getRepository('BackendBundle:User')->findOneBy(array('id' => $userid));
            if ($user != null) {
                $tmpTeamName .= $user->getUsername() . ' & ';
                $team->addUser($user);
            }
        }

        $teamName = substr($tmpTeamName, 0, -3);
        $team->setTeamname($teamName);
        $game->addTeam($team);
    }

}
