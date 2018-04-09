<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Query;
use DateTime;
use BackendBundle\Entity\Team;
use BackendBundle\Entity\Game;
use BackendBundle\Entity\GameState;
use BackendBundle\Entity\User;

class NewGameController extends Controller {

    public function newAction() {
        return $this->render('@Frontend/game/new.html.twig');
    }

    public function createAction(Request $request) {
        $data = array();
        $data['state'] = 'ok';

        $maxPoints = $request->get('maxpoints');
        $team1DataType = $request->get('team1datatype');
        $team1Data = $request->get('team1data');
        $team2DataType = $request->get('team2datatype');
        $team2Data = $request->get('team2data');

        $em = $this->getDoctrine()->getManager();

        $game = new Game();
        $game->setCreatedAt(new DateTime());

        if (empty($maxPoints)) {
            $maxPoints = 15;
        }
        $game->setWinningPoints($maxPoints);

        $this->addTeam($game, $team1DataType, $team1Data);
        $this->addTeam($game, $team2DataType, $team2Data);
        
//        echo 'Teams: '.count($game->getTeams());
        
        if (count($game->getTeams()) == 2) {
            /* @var $gameState GameState */
            $gameState = $em->getRepository('BackendBundle:GameState')->findOneBy(array('text' => 'Läuft'));
            $game->setGameState($gameState);

            $em->persist($game);
            $em->flush();
            $data['gameid'] = $game->getId();
            $data['gameurl'] = $this->generateUrl('frontend_play_game', array('gameid' => $game->getId()));
        } else {
            $data['state'] = 'error';
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

    public function searchUserAction(Request $request) {
        $data = array();
        $data['status'] = 'ok';

        $searchText = $request->get('searchtext');
        $data['searchtext'] = $searchText;

        if (!empty($searchText) && strlen($searchText) >= 0) {
            $em = $this->getDoctrine()->getManager();
            /* @var $query Query */
            $query = $em->createQuery('SELECT u.id, u.name FROM BackendBundle:User u WHERE u.name LIKE :name');
            $query->setParameter('name', '%' . $searchText . '%');
            $users = $query->getResult();
            $data['content'] = $this->renderView('@Frontend/game/user_result.html.twig', array(
                'users' => $users
            ));
        } else {
            $data['status'] = 'error';
            $data['errormsg'] = 'Suchtext zu kurz';
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    public function searchTeamAction(Request $request) {
        $data = array();
        $data['status'] = 'ok';

        $searchText = $request->get('searchtext');
        $data['searchtext'] = $searchText;

        if (!empty($searchText) && strlen($searchText) >= 0) {
            $em = $this->getDoctrine()->getManager();
            /* @var $query Query */
            $query = $em->createQuery('SELECT t FROM BackendBundle:Team t WHERE t.name LIKE :name');
            $query->setParameter('name', '%' . $searchText . '%');
            $teams = $query->getResult();
            $data['content'] = $this->renderView('@Frontend/game/team_result.html.twig', array(
                'teams' => $teams
            ));
        } else {
            $data['status'] = 'error';
            $data['errormsg'] = 'Suchtext zu kurz';
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    private function addTeam(&$game, $teamType, $teamData) {
        if ($teamType === 'users') {
            $this->addNewTeam($game, $teamData);
        } else {
            $this->addExistingTeam($game, $teamData);
        }
    }

    /* @var $game Game */

    private function addExistingTeam(&$game, $teamID) {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('BackendBundle:Team')->findOneBy(array('id' => $teamID));
        $game->addTeam($team);
    }

    /* @var $game Game */

    private function addNewTeam(&$game, $teamData) {
        $arrUserIDs = explode(',', $teamData);
        $em = $this->getDoctrine()->getManager();

        /* @var $team Team */
        $team = new Team();
        $team->setCreatedAt(new DateTime());
        $tmpTeamName = '';
        foreach ($arrUserIDs as $userid) {
            /* @var $user User */
            $user = $em->getRepository('BackendBundle:User')->findOneBy(array('id' => $userid));
            if ($user != null) {
                $tmpTeamName .= $user->getName() . ' & ';
                $team->addUser($user);
            }
        }

        $teamName = substr($tmpTeamName, 0, -3);
        $team->setName($teamName);
        $game->addTeam($team);
    }

}
