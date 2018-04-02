<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Statement;
use DateTime;
use BackendBundle\Entity\Game;
use BackendBundle\Entity\GameState;
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
        $teamID = $request->get('teamID');
        $addPoints = $request->get('points');
        $data['submittedPoints'] = $addPoints;

        $teamStats = $this->getTeamStats($gameID, $teamID);
        if (count($teamStats) > 0) {
            $addPoints = $this->checkAddPoints($gameID, $addPoints, $teamStats);
            $data['addedPoints'] = $addPoints;
        }

        $rowHtml = $this->savePointsToDB($gameID, $teamID, $addPoints);
        $data['html'] = $rowHtml;

        $data['gameState'] = $this->checkGameStateAfterAdd($gameID, $teamID);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    private function getTeamStats($gameID, $teamID) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        /* @var $statement Statement */
        $statement = $connection->prepare('SELECT team_id as teamID, sum(points) as points FROM watten.points WHERE game_id=:gameID AND team_id=:teamID');
        $statement->bindValue(':gameID', $gameID);
        $statement->bindValue(':teamID', $teamID);
        $statement->execute();
        $result = $statement->fetchAll();

        if (count($result) > 0) {
            return $result[0];
        }

        return array();
    }

    private function checkAddPoints($gameID, $addPoints, $teamStats) {
        $em = $this->getDoctrine()->getManager();
        /* @var $game Game */
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameID));
        $maxPoints = $game->getWinningPoints();
        $currentPoints = $teamStats['points'];
        if ($currentPoints + $addPoints > $maxPoints) {
            return $maxPoints - $currentPoints;
        }

        return $addPoints;
    }

    private function savePointsToDB($gameID, $teamID, $addPoints) {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameID));
        $team = $em->getRepository('BackendBundle:Team')->findOneBy(array('id' => $teamID));

        $points = new Points();
        $points->setGame($game);
        $points->setTeam($team);
        $points->setPoints($addPoints);
        $points->setCreatedAt(new \DateTime());
        $em->persist($points);
        $em->flush();

        $rowHtml = $this->renderView('@Frontend/play/pointEntry.html.twig', array(
            'point' => $points
        ));
        return $rowHtml;
    }

    private function checkGameStateAfterAdd($gameID, $teamID) {
        $data = array();

        $em = $this->getDoctrine()->getManager();
        /* @var $game Game */
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameID));
        $teamStats = $this->getTeamStats($gameID, $teamID);

        $winningPoints = $game->getWinningPoints();
        if (array_key_exists('points', $teamStats) && $teamStats['points'] >= $winningPoints) {
            /* @var $gameState GameState */
            $gameState = $em->getRepository('BackendBundle:GameState')->findOneBy(array('id' => 2));
            $game->setGameState($gameState);

            /* @var $gameWinner Team */
            $gameWinner = $em->getRepository('BackendBundle:Team')->findOneBy(array('id' => $teamID));
            $game->setGameWinner($gameWinner);
            $game->setFinishedAt(new DateTime());

            $em->persist($game);
            $em->flush();

            $data['winner'] = $gameWinner->getTeamname();
        }

        $data['state'] = $game->getGameState()->getId();
        return $data;
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

            $this->checkGameStateAfterRemove($gameID);
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($data);
        return $jsonResponse;
    }

    private function checkGameStateAfterRemove($gameID) {
        $em = $this->getDoctrine()->getManager();
        /* @var $game Game */
        $game = $em->getRepository('BackendBundle:Game')->findOneBy(array('id' => $gameID));
        $gameStats = $this->getGameStats($gameID);

        if (count($gameStats) == 2 && $game->getGameState()->getId() == 2) {
            $winningPoints = $game->getWinningPoints();

            if ($gameStats[0]['points'] < $winningPoints && $gameStats[1]['points'] < $winningPoints) {
                /* @var $gameState GameState */
                $gameState = $em->getRepository('BackendBundle:GameState')->findOneBy(array('id' => 1));
                $game->setGameState($gameState);
                $game->setGameWinner(null);
                $game->setFinishedAt(null);
                $em->persist($game);
                $em->flush();
            }
        }
    }

    private function getGameStats($gameID) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        /* @var $statement Statement */
        $statement = $connection->prepare('SELECT team_id as teamID, sum(points) as points FROM watten.points WHERE game_id=:gameID GROUP BY team_id');
        $statement->bindValue(':gameID', $gameID);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

}
