<?php
/**
 *
 *  Auth management class
 *
 */

namespace Tuqan\Classes;

use Jasny\Auth as JAuth;
use Jasny\Authz;
use Tuqan\Classes\User as User;


class Auth extends JAuth implements Authz
{
    use Authz\ByGroup;
    use JAuth\Sessions;

    /**
     * @var $groups array with permission structure
     */
    protected $groups;

    /**
     * @var $groups array with permission structure
     */
    protected $perfiles;


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
        $user->setRoles($this->getRoleById((int)$aIterador[2]));
        $this->updateSessionData('nombreUsuario', $aIterador[1]);
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
        $user->setRoles($this->getRoleById((int)$aIterador[2]));
        $this->updateSessionData('nombreUsuario', $aIterador[1]);
        return $user;
    }

    /**
     * Get the session data
     * @global array $_SESSION
     *
     * @param string $key
     *
     * @return array
     */
    protected function getSessionData($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Update the session
     * @global array $_SESSION
     *
     * @param string $key
     * @param mixed  $value
     */
    protected function updateSessionData($key, $value)
    {
        if (is_null($value)) {
            unset($_SESSION[$key]);
        } else {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Get the groups and the groups it supersedes.
     *
     * @return array
     */
    protected function getGroupStructure()
    {
        if (!isset($this->groups)) {
            $dbName = $_SESSION['db'];
            $dbUser = $_SESSION['login'];
            $dbPass = $_SESSION['pass'];

            $oBaseDatos = new Manejador_Base_Datos($dbUser, $dbPass, $dbName);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('nombre'));
            $oBaseDatos->construir_Tablas(array('perfiles'));
            $oBaseDatos->construir_where(array('id<>0', 'perfiles.activo=\'t\''));
            $oBaseDatos->consulta();

            while (($row = $oBaseDatos->coger_Fila())) {
                $this->groups[$row[0]] = array();
            }
        }

        return $this->groups;
    }

    /**
     * Gets the role name from its id
     *
     *
     * @param $id integer
     *
     * @return string
     */
    protected function getRoleById($id){
        if (!isset($this->perfiles)) {
            $dbName = $_SESSION['db'];
            $dbUser = $_SESSION['login'];
            $dbPass = $_SESSION['pass'];

            $oBaseDatos = new Manejador_Base_Datos($dbUser, $dbPass, $dbName);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id', 'nombre'));
            $oBaseDatos->construir_Tablas(array('perfiles'));
            $oBaseDatos->construir_where(array('perfiles.activo=\'t\''));
            $oBaseDatos->consulta();
            while (($row = $oBaseDatos->coger_Fila())) {
                $this->perfiles[(int)$row[0]] = $row[1];
            }
        }
        return $this->perfiles[(int)$id];
    }
}
