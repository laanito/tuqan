<?php
/**
 *
 *  Auth management class
 *
 */

namespace Tuqan\Classes;

use Jasny\Auth as JAuth;
use Tuqan\Classes\Config as Config;
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
        $sLoginEmp = $_SESSION['login'];
        $sDbEmp = $_SESSION['db'];

        $config = new Config();

        //Creamos el encriptador, las claves de acceso a la DB van encriptadas
        $css =& new \encriptador();
        $clave = 'encriptame';
        $pass = (string)$css->decrypt(trim($config->sPassEtc), $clave);

        $oBaseDatos = new Manejador_Base_Datos( $sLoginEmp, $pass, $sDbEmp);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area', 'password', 'activo'));
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array('(id=\'' . $id . '\')'));
        $oBaseDatos->consulta();

        $aIterador = $oBaseDatos->coger_Fila();
        $_SESSION['usuarioconectado'] = true;
        $_SESSION['userid'] = $aIterador[0];
        $_SESSION['nombreUsuario'] = $aIterador[1];
        $_SESSION['perfil'] = $aIterador[2];
        $_SESSION['areausuario'] = $aIterador[3];
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
        $sLoginEmp = $_SESSION['login'];
        $sDbEmp = $_SESSION['db'];

        $config = new Config();

        //Creamos el encriptador, las claves de acceso a la DB van encriptadas
        $css =& new \encriptador();
        $clave = 'encriptame';
        $pass = (string)$css->decrypt(trim($config->sPassEtc), $clave);

        $oBaseDatos = new Manejador_Base_Datos( $sLoginEmp, $pass, $sDbEmp);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area', 'password', 'activo'));
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array('(login=\'' . $username . '\')'));
        $oBaseDatos->consulta();

        $aIterador = $oBaseDatos->coger_Fila();
        $_SESSION['usuarioconectado'] = true;
        $_SESSION['userid'] = $aIterador[0];
        $_SESSION['nombreUsuario'] = $aIterador[1];
        $_SESSION['perfil'] = $aIterador[2];
        $_SESSION['areausuario'] = $aIterador[3];
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
