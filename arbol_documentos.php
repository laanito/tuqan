<?php
/**
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 */

use Tuqan\Classes\FakePage;

require_once 'Classes/FakePage.php';

require_once 'boton.php';
require_once 'Classes/generador_arboles.php' ;
require_once 'Classes/Manejador_Base_Datos.class.php' ;
include_once 'permisos_usuarios.php' ;
if (!isset($_SESSION)) {
    session_start();
}

function pinta_arbol($iUserid, $bUsuario = true)
{
    $sPremenu = permisos_documentos($iUserid, $bUsuario, $_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

    $oPagina = new FakePage();

    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
    $oPagina->addScript('/javascript/TreeMenu.js', 'text/javascript');
    $oPagina->addScript('/javascript/cursor.js', 'text/javascript');


    if ($bUsuario == true) {
        $oBoton = new boton(gettext('sCambios'), 'parent.sndReq(\'aceptar_documentos\',\'\',0)', 'noafecta');
    } else {
        $oBoton = new boton(gettext('sCambios'), 'parent.sndReq(\'aceptar_perfil_doc\',\'\',0)', 'noafecta');
    }

    $oPagina->addBodyContent("<p align=\"center\">" . $oBoton->to_Html() . "</p>");
    $oPagina->addBodyContent("<div id=\"arbol_centro\">" . $sPremenu . "</div>");
    $oPagina->addBodyContent("<p align=\"center\">" . $oBoton->to_Html() . "</P>");
    return $oPagina->toHtml();
}
