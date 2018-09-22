<?php
namespace Tuqan\Util;

use \Jasny\Auth;
use \Jasny\Auth\Sessions;
use Tuqan\Classes\Manejador_Base_Datos;


class TuqanAuth extends Auth
{
    use Sessions;

    private $oBaseDatos;

    public function __construct()
    {
        $this->oBaseDatos = new Manejador_Base_Datos(
        $_SESSION['login'],
        $_SESSION['pass'],
        $_SESSION['db']
        );
    }

    /**
     * Fetch a user by ID
     *
     * @param int $id
     * @return TuqanUser
     */
    public function fetchUserById($id)
    {
        $this->oBaseDatos->iniciar_Consulta('SELECT');
        $this->oBaseDatos->construir_Campos(array('login_name', 'login_pass'));
        $this->oBaseDatos->construir_Tablas(array('qnova_acl'));
        $this->oBaseDatos->construir_Where(array("id=$id"));
        $this->oBaseDatos->consulta();
        if ($aIteradorInterno = $this->oBaseDatos->coger_Fila()) {
            $User = new TuqanUser();
            $User->id=$id;
            $User->username=$aIteradorInterno[0];
            $User->password=$aIteradorInterno[1];
            return $User;
        }
        return null;
    }

    /**
     * Fetch a user by username
     *
     * @param string $username
     * @return TuqanUser
     */
    public function fetchUserByUsername($username)
    {
        $this->oBaseDatos->iniciar_Consulta('SELECT');
        $this->oBaseDatos->construir_Campos(array('id', 'login_pass'));
        $this->oBaseDatos->construir_Tablas(array('qnova_acl'));
        $this->oBaseDatos->construir_Where(array("login_name='$username'"));
        $this->oBaseDatos->consulta();
        if ($aIteradorInterno = $this->oBaseDatos->coger_Fila()) {
            $User = new TuqanUser();
            $User->id=$aIteradorInterno[0];
            $User->username=$username;
            $User->password=$aIteradorInterno[1];
            return $User;
        }
        return null;
    }

    /**
     * Hash a password
     *
     * @param string $password
     * @return string
     */
    public function hashPassword($password)
    {
        if (!is_string($password) || $password === '') {
            throw new \InvalidArgumentException("Password should be a (non-empty) string");
        }

        return md5($password);
    }
}