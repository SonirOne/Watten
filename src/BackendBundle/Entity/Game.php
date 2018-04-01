<?php

namespace BackendBundle\Entity;

/**
 * Game
 */
class Game {

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $winning_points;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime|null
     */
    private $finished_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $teams;

    /**
     * @var \BackendBundle\Entity\GameState
     */
    private $game_state;

    /**
     * @var \BackendBundle\Entity\Team
     */
    private $game_winner;

    /**
     * Constructor
     */
    public function __construct() {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set winningPoints.
     *
     * @param int $winningPoints
     *
     * @return Game
     */
    public function setWinningPoints($winningPoints) {
        $this->winning_points = $winningPoints;

        return $this;
    }

    /**
     * Get winningPoints.
     *
     * @return int
     */
    public function getWinningPoints() {
        return $this->winning_points;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Game
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Add team.
     *
     * @param \BackendBundle\Entity\Team $team
     *
     * @return Game
     */
    public function addTeam(\BackendBundle\Entity\Team $team) {
        $this->teams[] = $team;

        return $this;
    }

    /**
     * Remove team.
     *
     * @param \BackendBundle\Entity\Team $team
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTeam(\BackendBundle\Entity\Team $team) {
        return $this->teams->removeElement($team);
    }

    /**
     * Get teams.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams() {
        return $this->teams;
    }

    /**
     * Set gameState.
     *
     * @param \BackendBundle\Entity\GameState|null $gameState
     *
     * @return Game
     */
    public function setGameState(\BackendBundle\Entity\GameState $gameState = null) {
        $this->game_state = $gameState;

        return $this;
    }

    /**
     * Get gameState.
     *
     * @return \BackendBundle\Entity\GameState|null
     */
    public function getGameState() {
        return $this->game_state;
    }

    /**
     * Set gameWinner.
     *
     * @param \BackendBundle\Entity\Team|null $gameWinner
     *
     * @return Game
     */
    public function setGameWinner(\BackendBundle\Entity\Team $gameWinner = null) {
        $this->game_winner = $gameWinner;

        return $this;
    }

    /**
     * Get gameWinner.
     *
     * @return \BackendBundle\Entity\Team|null
     */
    public function getGameWinner() {
        return $this->game_winner;
    }

    /**
     * Set finishedAt.
     *
     * @param \DateTime|null $finishedAt
     *
     * @return Game
     */
    public function setFinishedAt($finishedAt = null) {
        $this->finished_at = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt.
     *
     * @return \DateTime|null
     */
    public function getFinishedAt() {
        return $this->finished_at;
    }

}
