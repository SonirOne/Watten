<?php

namespace BackendBundle\Entity;

/**
 * User
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;


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
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setName($username)
    {
        $this->name = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
