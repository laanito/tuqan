<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 23/09/2018
 * Time: 0:38
 */

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
        $this->password = $password;
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
     * Get the hashed password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Event called on login.
     *
     * @return boolean  false cancels the login
     */
    public function onLogin()
    {
        if (!$this->active) return false;

        $this->last_login = new \DateTime();
        $this->save();

        return true;
    }

    /**
     * Event called on logout.
     */
    public function onLogout()
    {
        $this->last_logout = new \DateTime();
        $this->save();
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
     *
     */
    public function save(){

    }
}