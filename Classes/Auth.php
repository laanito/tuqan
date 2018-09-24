<?php
/**
 *
 *  Auth management class
 *
 */

namespace Tuqan\Classes;

use Jasny\Auth as JAuth;
use Tuqan\Classes\User as User;
use Jasny\Auth\Sessions;

class Auth extends JAuth
{

    /**
     * Fetch a user by ID
     *
     * @param int $id
     * @return User
     */
    public function fetchUserById($id)
    {
        $dbName = $_SESSION['db'];
        $dbUser = $_SESSION['login'];
        $dbPass = $_SESSION['pass'];

        $oBaseDatos = new Manejador_Base_Datos($dbUser, $dbPass, $dbName);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area', 'password', 'activo'));
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array('(id=\'' . $id . '\')'));
        $oBaseDatos->consulta();

        $aIterador = $oBaseDatos->coger_Fila();
        $oBaseDatos->desconexion();

        $user = new User($aIterador[0], $aIterador[1], $aIterador[4], $aIterador[5]);

        return $user;
    }

    /**
     * Fetch a user by username
     *
     * @param string $username
     * @return User
     */
    public function fetchUserByUsername($username)
    {
        $dbName = $_SESSION['db'];
        $dbUser = $_SESSION['login'];
        $dbPass = $_SESSION['pass'];

        $oBaseDatos = new Manejador_Base_Datos($dbUser, $dbPass, $dbName);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area', 'password', 'activo'));
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array('(login=\'' . $username . '\')'));
        $oBaseDatos->consulta();

        $aIterador = $oBaseDatos->coger_Fila();
        $oBaseDatos->desconexion();

        $user = new User($aIterador[0], $aIterador[1], $aIterador[4], $aIterador[5]);

        return $user;
    }

    public function persistCurrentUser()
    {
        // TODO: Implement persistCurrentUser() method.
    }

    /**
     * Get current authenticated user id
     *
     * @return mixed
     */
    protected function getCurrentUserId()
    {
        // TODO: Implement getCurrentUserId() method.
    }
}
