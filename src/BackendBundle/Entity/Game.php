<?php

namespace BackendBundle\Entity;

/**
 * Game
 */
class Game
{
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $teams;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set winningPoints.
     *
     * @param int $winningPoints
     *
     * @return Game
     */
    public function setWinningPoints($winningPoints)
    {
        $this->winning_points = $winningPoints;

        return $this;
    }

    /**
     * Get winningPoints.
     *
     * @return int
     */
    public function getWinningPoints()
    {
        return $this->winning_points;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Game
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Add team.
     *
     * @param \BackendBundle\Entity\Team $team
     *
     * @return Game
     */
    public function addTeam(\BackendBundle\Entity\Team $team)
    {
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
    public function removeTeam(\BackendBundle\Entity\Team $team)
    {
        return $this->teams->removeElement($team);
    }

    /**
     * Get teams.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
