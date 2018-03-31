<?php

namespace BackendBundle\Entity;

/**
 * Points
 */
class Points {

    /**
     * @var int
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Game
     */
    private $game;

    /**
     * @var \BackendBundle\Entity\Team
     */
    private $team;

    /**
     * @var int
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set game.
     *
     * @param \BackendBundle\Entity\Game|null $game
     *
     * @return Points
     */
    public function setGame(\BackendBundle\Entity\Game $game = null) {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game.
     *
     * @return \BackendBundle\Entity\Game|null
     */
    public function getGame() {
        return $this->game;
    }

    /**
     * Set team.
     *
     * @param \BackendBundle\Entity\Team|null $team
     *
     * @return Points
     */
    public function setTeam(\BackendBundle\Entity\Team $team = null) {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team.
     *
     * @return \BackendBundle\Entity\Team|null
     */
    public function getTeam() {
        return $this->team;
    }

    /**
     * Set points.
     *
     * @param int $points
     *
     * @return Points
     */
    public function setPoints($points) {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Points
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

}
