<?php
namespace Tuqan;

/**
 * Created on 13-oct-2005
 *
* LICENSE see LICENSE.md file
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates

 * @version 0.2.2g
 *
 * Pagina que procesa las peticiones, aqui combinamos el manejador de peticiones con el de
 * respuestas y el procesador de peticiones, creamos un objeto manejador de peticiones el
 * cual preparara los datos para darle a un objeto procesador de peticiones, el cual a su
 * vez le dara el codigo necesario a un ultimo objeto manejador de respuestas y este ultimo
 * se lo devolvera al manejador ajax
 */
require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'Procesar_Editor.php';
require_once 'Classes/Procesar_Funciones_Comunes.php';
require_once 'Classes/Procesar_Detalles.php';
require_once 'Classes/Procesar_Formularios.php';
require_once 'Classes/generador_Formularios.php';
require_once 'Classes/Form_Administracion.php';
require_once 'Classes/Form_Comun.php';
require_once 'Classes/Form_Calidad.php';
require_once 'Classes/Form_Medio.php';
require_once 'Classes/Form_Nuevo.php';
require_once 'Classes/Manejador_Detalles.php';
require_once 'Classes/forms.php';
require_once 'Classes/Procesar_Arbol.php';
require_once 'Classes/Procesar_Ayuda.php';
require_once 'Classes/Titulos.php';
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/Manejador_Listados.php';
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/Manejador_Formularios.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/generador_SQL.php';
require_once 'Classes/Config.php';
require_once 'Classes/Manejador_De_Respuestas.php';
require_once 'Classes/Procesar_Listados.php';
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'estilo.php';
require_once 'Classes/Procesador_De_Peticiones.php';
require_once 'boton.php';
require_once 'HTML/TreeMenu.php';
require_once 'Classes/generador_arboles.php';
require_once 'HTML/Table.php';
require_once 'Pager/Pager.php';
require_once 'Classes/generador_listados.php';
include_once 'constantes.inc.php';






use Tuqan\Classes\Manejador_De_Peticiones;
use Tuqan\Classes\Manejador_De_Respuestas;
use Tuqan\Classes\Procesador_De_Peticiones;

/**
 *        Datos ya separados que nos pasa el manejador AJAX
 * @var array
 */
if (!isset($_SESSION)) {
    session_start();
}


$aDatos = explode(separador, $_REQUEST['datos']);

if (isset($_REQUEST['pageID'])) {
    $aDatos[] = $_REQUEST['pageID'];
}
/**
 *         Cambiar este valor para poner el modo debug, 1=modo debug, 0=modo normal
 * @var integer
 */


if (!isset($_SESSION['usuarioconectado'])) {
    echo "contenedor|logout";
    die();
}

/**
 *     Este es nuestro manejador de peticiones
 * @var object
 */
if ($_REQUEST['action'] == 'atras') {
    $_REQUEST['action'] = array_pop($_SESSION['ultimolistado']);
    $aDatos = array_pop($_SESSION['ultimolistadodatos']);
    $_SESSION['pagina'] = array_pop($_SESSION['paginaanterior']);
    if ($_REQUEST['action'] == $_SESSION['accionactual']) {
        $_REQUEST['action'] = array_pop($_SESSION['ultimolistado']);
        $aDatos = array_pop($_SESSION['ultimolistadodatos']);
        $_SESSION['pagina'] = array_pop($_SESSION['paginaanterior']);
    }
}
$oPeticion = new Manejador_De_Peticiones($_REQUEST['action'], $aDatos);

/**
 * Guardamos la ultima peticion y la actual
 */
if(isset($_SESSION['accionanterior'])) {
    $_SESSION['accioniframe'] = $_SESSION['accionanterior'];
}
if(isset($_SESSION['accionactual'])) {
    $_SESSION['accionanterior'] = $_SESSION['accionactual'];
}
if(isset($_REQUEST['action'])) {
    $_SESSION['accionactual'] = $_REQUEST['action'];
}
/**
 *         Estos son los parametros que necesita el procesador de peticiones
 * @var array
 */
$aParametros = $oPeticion->devuelve_Parametros();
/**
 *     Este es el procesador de peticiones
 * @var object
 */

$oProcesador = new Procesador_De_Peticiones($aParametros);

$oProcesador->procesar();

/**
 *     Esta cadena contiene lo que le devolveremos al ajax
 * @var string
 */

$sCadena = $oProcesador->devolver();

/**
 *         Este es nuestro manejador de respuestas
 * @var object
 */

$oRetorno = new Manejador_De_Respuestas($sCadena);

$oRetorno->toAjax();


