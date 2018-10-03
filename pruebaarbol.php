<?php
/**
* LICENSE see LICENSE.md file
 */

require_once 'HTML/Page.php';
require_once 'FormatoPagina.php';
require_once 'boton.php';
require_once('generador_arboles.php');
require_once('Manejador_Base_Datos.class.php');
include_once('permisos_usuarios.php');
if (!isset($_SESSION)) {
    session_start();
}
function pinta_arbol($iUserid)
{
    $sPremenu = arbol_permisos($iUserid, $_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $aParametros = variables_Pagina($browser, $sistema);

    $oPagina = new HTML_Page(array(
        'charset' => $aParametros[0],
        'language' => $aParametros[1],
        'cache' => $aParametros[2],
        'lineend' => $aParametros[3]
    ));

    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
    $oPagina->addScript('/javascript/TreeMenu.js', 'text/javascript');
    $oBoton = new boton('Guardar Cambios', 'parent.sndReq(\'aceptar\',\'\',0)', 'noafecta');

    $oPagina->addBodyContent("<P ALIGN=\"center\">" . $oBoton->to_Html() . "</P>");
    $oPagina->addBodyContent($sPremenu);
    $oPagina->addBodyContent("<P ALIGN=\"center\">" . $oBoton->to_Html() . "</P>");
    return $oPagina->toHtml();
}

function pinta_perfil($iUserid)
{
    $sPremenu = ver_arbol_permisos($iUserid, $_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

    $aParametros = variables_Pagina($browser, $sistema);


    $oPagina = new HTML_Page(array(
        'charset' => $aParametros[0],
        'language' => $aParametros[1],
        'cache' => $aParametros[2],
        'lineend' => $aParametros[3]
    ));
    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');

    $oPagina->addScript('javascript/TreeMenu.js', 'text/javascript');
    $oPagina->addBodyContent('PERMISOS:');
    $oPagina->addBodyContent($sPremenu);
    return $oPagina->toHtml();
}

