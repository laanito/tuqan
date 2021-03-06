<?php
namespace Tuqan;
/**
 * Created on 03-nov-2005
 *
* LICENSE see LICENSE.md file
 *
 * version temporal de menus que utiliza el generador de menus
 * Actualmente es llamado directamente por el iframe.
 * @TODO utilizar el manejador de peticiones
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

require_once 'vendor/autoload.php';
require_once 'Classes/FakePage.php';
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/Manejador_Formularios.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/generador_SQL.php';
require_once 'Classes/Config.php';
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'Classes/Procesador_De_Peticiones.php';

use Tuqan\Classes\FakePage;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Classes\Generador_Arboles;

if (!isset($_SESSION)) {
    session_start();
}
$PHPSESSID = session_name();
$sAccion = $_REQUEST['action'];

//Para el superusuario los permisos son automaticos

$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
$sTabla = 'menu';
$aCampos = array('nodo', 'etiqueta', 'accion');
$oDb->iniciar_Consulta('SELECT');
$oDb->construir_Campos($aCampos);
$oDb->construir_Tablas(array($sTabla));
$oDb->pon_Where('menu=\'' . $sAccion . '\'');
$oDb->pon_Order('id ASC');
if ($_SESSION['perfil'] != 0) {
    $oDb->pon_Where('permisos[' . $_SESSION['perfil'] . ']=true');
}
$oDb->construir_Order(array('id ASC'));
$oDb->consulta();

if ($aIterador = $oDb->coger_Fila()) {
    $aMenu = array($aIterador[0] => array($aIterador[1] => $aIterador[2]));
    while ($aIterador = $oDb->coger_Fila()) {
        $aMenu[$aIterador[0]][$aIterador[1]] = $aIterador[2];
    }
    //Fin while
}
$menu = new Generador_Arboles();
$menu->Inicia_Arbol('menu');
/**
 * @TODO poner un nivel mas de foreach
 */
if (is_array($aMenu)) {
    foreach ($aMenu as $i => $valor) {
        if (is_array($valor)) {
            $nodo_padre = $menu->Nuevo_Nodo($i, 'nodo1'); //nodo1
            foreach ($valor as $i2 => $valor2) {
                $nodo = $menu->Nuevo_Nodo($i2, 'nodo2');  //nodo2
                $menu->Nuevo_Evento_Menu($nodo, 'onClick',
                    "parent.sndReq('" . $valor2 . "','" . $PHPSESSID . "',1)");


                $menu->Situa_Nodo($nodo, $nodo_padre);
            }
            $menu->Situa_Nodo($nodo_padre);
            $menu->Nuevo_Evento_Menu($nodo, 'onClick', "parent.sndReq('" . $valor2 . "','" . $PHPSESSID . "',1)");


        } else {
            $nodo_padre = $menu->Nuevo_Nodo($i, 'nodo2');    //nodo2
            $menu->Nuevo_Evento_Menu($nodo_padre, 'onClick', "parent.sndReq('" . $valor . "','" . $PHPSESSID . "',1)");
            $menu->Situa_Nodo($nodo_padre);
        }
    }
    $sPremenu = $menu->Pinta_Arbol();
} else {
    $sPremenu = gettext('sNoAcceso');
}
//vamos a meter aqui un Page


$oPagina = new FakePage();

$oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');

$oPagina->addScript('/javascript/TreeMenu.js', 'text/javascript');
$oPagina->addBodyContent($sPremenu);
$oPagina->display();

