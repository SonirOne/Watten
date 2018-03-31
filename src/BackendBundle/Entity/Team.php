<?php

namespace BackendBundle\Entity;

/**
 * Group
 */
class Team {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $teamname;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * Constructor
     */
    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set groupname.
     *
     * @param string $teamname
     *
     * @return Group
     */
    public function setTeamname($teamname) {
        $this->teamname = $teamname;

        return $this;
    }

    /**
     * Get teamname.
     *
     * @return string
     */
    public function getTeamname() {
        return $this->teamname;
    }

    /**
     * Add user.
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\BackendBundle\Entity\User $user) {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(\BackendBundle\Entity\User $user) {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Team
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
