<?php
namespace Tuqan;
/**
 * Created on 17-oct-2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>

 * @version 0.5.0a
 *
 * @author Alejandra J Garca Romero <u>ajgarcia@islanda.es</u>
 * Esta es la pagina principal desde la cual mostramos los menus y navegamos por todas las opciones
 * Se compone de una cabecera con el logo, un submenu bajo esta desde el que accedemos a los menus
 * laterales.
 * Cargamos la hoja de estilo 'estilo.php' y las funciones javascript guardadas en 'menu.js'
 */
//Esta funcion nos mira si el usuario tiene algun permiso de administracion para mostrar el boton de administracion

use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Classes\Formato_Pagina;

require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Debugger.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/generador_Formularios.php';
require_once 'Classes/Manejador_Formularios.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/Manejador_De_Respuestas.php';
require_once 'Classes/Manejador_Detalles.php';
require_once 'Classes/generador_SQL.php';
require_once 'Classes/Config.php';
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'estilo.php';
require_once 'Classes/Procesador_De_Peticiones.php';


function permisos_Administracion($iId)
{
    if ($_SESSION['perfil'] == 0) {
        $bAdministrador = true;
    } else {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('menu_nuevo.id'));
        $oBaseDatos->construir_Tablas(array('usuarios', 'perfiles', 'menu_nuevo', 'menu_idiomas_nuevo'));
        $oBaseDatos->construir_Where(array('usuarios.id=' . $iId, 'usuarios.perfil=perfiles.id', 'usuarios.activo=\'t\'', 'menu_nuevo.permisos[usuarios.perfil]=true',
            'menu_idiomas_nuevo.valor like \'%administracion%\'', 'menu_idiomas_nuevo.idioma_id=1', 'menu_idiomas_nuevo.menu=menu_nuevo.id'));
        $oBaseDatos->consulta();
        $bAdministrador = false;
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $bAdministrador = true;
        }
    }
    return $bAdministrador;
}

/**
 * Cogemos nuestro fichero a incluir donde tenemos las cadenas de idioma
 * Ademas incluimos nuestro fichero de librerias include.php el cual contiene el resto de includes y datos
 * tales como el servidor y el puerto a donde conectar
 */
if (!isset($_SESSION)) {
    session_start();
}
require_once 'include.php';


/**
 * @var object
 * Aqui declaramos la hoja de estilo principal
 */
if (isset($_REQUEST['anchura'])) {
    $_SESSION['ancho'] = $_REQUEST['anchura'];
    $_SESSION['alto'] = $_REQUEST['altura'];
}

$anchura = $_SESSION['ancho'];
$altura = $_SESSION['alto'];
$browser = $_SESSION['navegador'];
$sistema = $_SESSION['sistema_operativo'];

if ($browser == "Microsoft Internet Explorer") {
    $anchura = $anchura - 20;
}
$oEstilo = new \Estilo_Pagina($anchura, $altura, $browser);

$_SESSION['ultimolistado'] = array();
$_SESSION['ultimolistadodatos'] = array();
$_SESSION['paginaanterior'] = array();

/**
 * @var object
 * Aqui creamos la pagina
 */

if (!$_SESSION['usuarioconectado']) {
    header("Location: error.php?motivo=nologeado");
}

$formatoPagina =new Formato_Pagina();
$aParametros = $formatoPagina->variables_Pagina($browser, $sistema);

$oPagina = new \HTML_Page(array(
    'charset' => $aParametros[0],
    'language' => $aParametros[1],
    'cache' => $aParametros[2],
    'lineend' => $aParametros[3]
));

$oPagina->setTitle("Qnova");

/**
 * Aadimos el estilo a la pagina
 */

$oPagina->addStyleDeclaration($oEstilo, 'text/css');

/**
 * Añadimos el fichero donde incluimos las funciones javascript
 */
$oPagina->addScript('javascript/really_simple_history/dhtmlHistory.js', "text/javascript");


$oPagina->addScript('javascript/cookies.js', "text/javascript");
$oPagina->addScript('javascript/Manejador_Ajax.js', "text/javascript");
$oPagina->addScript('javascript/dhtmlmenu.js', "text/javascript");
$oPagina->addScript('javascript/ayuda.js', "text/javascript");

$oPagina->addBodyContent("<div id=\"pagina\">");

if (isset($altura)) {
    $oPagina->addBodyContent("<div id=\"cabecera1\"></div>" .
        "<div id=\"cabecera2\"><img class=\"logo_tuqan\" src=\"images/logotipo-tuqan.svg\"></div>" .
        "<div id=\"cabecera3\"><h1 class=\"titulo_cabecera\">Gestión de Calidad y Medioambiente</h1></div>" .
        "<div id=\"cabecera4\"></div>" .
        "<div id=\"cabecera5\"></div>");

    /**
     * Aqui ponemos las opciones del submenu de arriba, cuando hagamos click en alguna de las opciones
     * llamamos a la funcion javascript sndReq, la cual gestiona que se cargue el menu apropiado en la
     * division izquierda
     */


    $oPagina->addBodyContent("<div id=\"imagenizq\"></div>" .
        "<div id=\"usuariodiv\">" . gettext('sUsuario') . "&nbsp;<b>" . $_SESSION['nombreUsuario'] . "</b></div>" .
        "<div id=\"separador\"></div>" .
        "<div id=\"titulo\">Esta ud. en:</div>" .
        "<div id=\"imagender\"></div>");


    if (permisos_Administracion($_SESSION['perfil'])) {
        $_SESSION['admin'] = true;
    }
//$oPagina->addBodyContent("</div>");
    $oPagina->addBodyContent("<div id=\"imgmenuizq\"></div>");
    $oPagina->addBodyContent("<div id=\"submenu\"></div>");
    $oPagina->addBodyContent("<div id=\"imgmenuder\"></div>");


    $oPagina->addBodyContent("<div id=\"BordeIzq\"></div>");
    $oPagina->addBodyContent("<div id=\"contenido\">");
    $oPagina->addBodyContent("<div id=\"contenedor\"></div>");
    $oPagina->addBodyContent("<div id=\"diveditor\"></div>");
    $oPagina->addBodyContent("<div id=\"diviframe\"></div>");
    $oPagina->addBodyContent("</div>");
    $oPagina->addBodyContent("<div id=\"BordeDer\"></div>");

    $oPagina->addBodyContent("<div id=\"Esq_Izq\"></div>");
    $oPagina->addBodyContent("<div id=\"Borde_Inf\"></div>");
    $oPagina->addBodyContent("<div id=\"Esq_Der\"></div>");

    $oPagina->addBodyContent("<div id=\"wait\"></div>");

    $oPagina->addBodyContent("<div id=\"divayuda\"></div>");


} else {
    $oPagina->addBodyContent("<p class=\"parrafo\">El navegador no soporta javascript/cookies,la aplicación está diseñada para Mozilla/Firefox 1.5 o Microsoft Internet Explorer 6, compruebe adems que tiene habilitado javascript/cookies</p>");
}
$oPagina->addBodyContent("</div>");
$oPagina->addBodyContent("<div id=\"ficheros\">");
$oPagina->addBodyContent("<div id=\"logos\"><A class=\"pulsia\" HREF=http://www.pulsia.es target=_new><img class=\"logo_pulsia\" src=\"images/pulsia.svg\"></A>");

$oPagina->addBodyContent("</div>");
$oPagina->display();

