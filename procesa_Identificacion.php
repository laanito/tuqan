<?php
namespace Tuqan;

/**
 * Created on 13-oct-2005
 *
* LICENSE see LICENSE.md file
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>

 * @version 0.2.4a
 * Pagina que procesa las peticiones, tenemos dos clases, peticion y devolver, la primera
 * es la que recoje las peticiones y decide como procesarlas, la segunda es la que devuelve
 * la respuesta en forma de header.
 *
 */

/**
 * Cogemos nuestro fichero a incluir castellano.php donde tenemos las cadenas de idioma
 * Ademas incluimos nuestro fichero de librerias include.php el cual contiene el resto
 * de includes y datos tales como el servidor y el puerto a donde conectar
 */

require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/generador_SQL.php';
require_once 'encriptador.php';

use Tuqan\Classes\Manejador_Base_Datos;

/**
 * El procesa1 nos va a gestionar el formulario de index.php, el cual nos envia a la segunda
 * identificacion si es correcto (enviando tambien los parametros de conexion a la base
 * de datos de la empresa) o nos devuelve a index.php con valor de error=1
 */

function procesa1()
{
    /**
     * Recuperamos la sesion
     */
    if (!isset($_SESSION)) {
        session_start();
    }
    /**
     *     convertimos el campo password que nos pasan por post a md5)
     * @var string
     */

    $sClaveMd5 = md5($_POST['clave']);
    $sLoginEtc = 'qnova';
    $sPassEtc = 'ZTBlMWI2YTBlYmnDeYFE';
    $sDbEtc = 'qnova';


    /**
     * Creamos un objeto para que realice las llamadas a las funciones de nuestra clase de base de datos
     * @var object
     */

    $_SESSION['loginempresa'] = 0;
    $sLoginEmp = $sLoginEtc;
    $css =& new \encriptador();
    $clave = 'encriptame';
    $sPassEmp = $css->decrypt(trim($sPassEtc), $clave);
    $sDbEmp = $sDbEtc;
    $oBaseDatos = new Manejador_Base_Datos($sLoginEmp, $sPassEmp, $sDbEmp);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('id'));
    $oBaseDatos->construir_Tablas(array('qnova_acl'));
    $oBaseDatos->construir_Where(array('(login_name=\'' . $_POST['nombre'] . '\')', '(login_pass=\'' . $sClaveMd5 . '\')'));
    $oBaseDatos->consulta();

    /**
     *  Si ha devuelto algo es que el login es correcto y redireccionamos a principal
     *  En caso contrario volvemos a index
     */

    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $_SESSION['loginempresa'] = 1;
        /**
         *  Si nos hemos logeado bien obtenemos los parametros de conexion a la base de datos
         *  del usuario
         */

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('nombre_bbdd', 'login_bbdd', 'pass_bbdd', 'empresa'));
        $oBaseDatos->construir_Tablas(array('qnova_bbdd'));
        $oBaseDatos->construir_Where(array('(id=\'' . $aIterador[0] . '\')'));
        $oBaseDatos->consulta();

        if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {

            /**
             *  Si hemos obtenido correctamente los datos de conexion mostramos el segundo login
             *  previamente ponemos las variables de sesion pertinentes para poder
             *  realizar la conexion posteriormente
             */
            $css =& new \encriptador();
            $clave = 'encriptame';
            $_SESSION['conectado'] = true;
            $_SESSION['db'] = $aIteradorInterno[0];
            $_SESSION['login'] = $aIteradorInterno[1];
            $_SESSION['pass'] = $css->decrypt(trim($aIteradorInterno[2]), $clave);
            $_SESSION['empresa'] = $aIteradorInterno[3];


            $sRes = ("Location: login_Usuario.php?sesid=" . session_id());

            /**
             *  Cerramos la base de datos
             */

            $oBaseDatos->desconexion();

            return $sRes;
        }
    } else {
        return ("Location: index.php?error=1?sesid=" . session_id());
    }
    return ("Location: index.php?error=1?sesid=" . session_id());
}

//FIN procesa1

function procesa2()
{

    /**
     * Recuperamos la sesion
     */
    if (!isset($_SESSION)) {
        session_start();
    }

    /** @var string
     *  convertimos el campo password que nos pasan por post a md5)
     */

    $sClaveMd5 = md5($_POST['clave']);

    /**
     * Aqui ponemos en sesion los valores de la base de datos que antes sacabamos de login empresa, pero ahora lo hacemos directamente
     */

    /*$sIdioma=$_POST['idioma'];

    $css=&new encriptador();
    $clave='encriptame';

    $_SESSION['login']=$sLoginEtc;
    $_SESSION['pass']=$css->decrypt(trim($sPassEtc),$clave);
     // Modificacion para seleccion idiomas en index
    $_SESSION['idioma']=$sIdioma;
    $_SESSION['db']=$sDbEtc;*/
    /**
     * @var object
     * Creamos un objeto para que realice las llamadas a las funciones de nuestra clase de base de datos
     */

    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    /**
     * Construimos la consulta a realizar con los metodos de nuestro manejador
     */

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('id', 'login', 'perfil'));
    $oBaseDatos->construir_Tablas(array('usuarios'));
    $oBaseDatos->construir_Where(array('(login=\'' . $_POST['nombre'] . '\')', '(activo=\'t\')', '(password=\'' . $sClaveMd5 . '\')'));
    $oBaseDatos->consulta();
    /**
     * @var array
     * Si ha devuelto algo es que el login es correcto y redireccionamos a principal
     * En caso contrario volvemos a index
     */

    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $_SESSION['usuarioconectado'] = true;
        $_SESSION['userid'] = $aIterador[0];
        $_SESSION['nombreUsuario'] = $aIterador[1];
        $_SESSION['perfil'] = $aIterador[2];

        $oBaseDatos->iniciar_Consulta('UPDATE');
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array("id='" . $_SESSION['userid'] . "'"));
        //$oBaseDatos->construir_Where(array('id='.$_SESSION['userid']));
        $oBaseDatos->construir_SetSin(array('ultimo_acceso', 'numero_accesos'),
            array('now()', 'numero_accesos+1'));
        $oBaseDatos->consulta();
        $oBaseDatos->desconexion();
        return ("Location: qnova.php?sesid=" . session_id());
    } else {
        $oBaseDatos->iniciar_Consulta('UPDATE');
        $oBaseDatos->construir_Tablas(array('usuarios'));
        $oBaseDatos->construir_Where(array("login='" . $_POST['nombre'] . "'"));
        $oBaseDatos->construir_SetSin(array('ultimo_acceso_nulo', 'numero_accesos_nulos'),
            array('now()', 'numero_accesos_nulos+1'));
        //echo $oBaseDatos->to_String_Consulta();
        //die();
        $oBaseDatos->consulta();
        return ("Location: login_Usuario.php?error=1?sesid=".session_id());
    }
}

// FIN procesa2
include_once ('etc/qnova.conf.php');
if ($_POST['numero'] == 1) {
    header(procesa1());
} else {
    header(procesa2());
}

