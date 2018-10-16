<?php

namespace Tuqan\Classes;

use Jasny\Auth\User as JUser;

/**
 * Class User
 */
class User implements JUser
{

    private $id;
    private $username;
    private $password;
    private $active;
    private $roles;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $active
     */
    public function __construct($id, $username, $password, $active)
    {
        $this->id =$id;
        $this->username = $username;
        $this->password = password_hash($password,PASSWORD_BCRYPT);
        $this->active = $active;
    }

    /**
     * Get the user ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the usermame
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Event called on login.
     *
     * @return boolean  false cancels the login
     */
    public function onLogin()
    {
        if (!$this->active) {
            return false;
        }

        $this->last_login = new \DateTime();
        $_SESSION['usuarioconectado']=true;
        $_SESSION['userid']=$this->getId();
        return true;
    }

    /**
     * Event called on logout.
     */
    public function onLogout()
    {
        $this->last_logout = new \DateTime();
        unset($_SESSION['usuarioconectado']);
    }

    /**
     * Get user's hashed password
     *
     * @return string
     */
    public function getHashedPassword()
    {
        return $this->password;
    }

    /**
     * Get the access groups of the user
     *
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles string
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}
