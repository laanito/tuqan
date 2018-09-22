<?php
/**
 *
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 */
require_once 'javascript/FCKeditor/fckeditor.php';
require_once 'boton.php';
require_once 'include.php';
require_once 'Manejador_Base_Datos.class.php';

if (!isset($_SESSION)) {
    session_start();
}
$anchura = $_SESSION['ancho'];
$altura = $_SESSION['alto'];
$lenguaje = $_SESSION['idioma'];
$browser = $_SESSION['navegador'];
$sistema = $_SESSION['sistema_operativo'];
$cliente_usuario = $_SESSION['cliente'];

$oVolver = new boton("Volver", "parent.atraseditor()", "noafecta");

$oEstilo = new Estilo_Pagina($_SESSION['ancho'], $_SESSION['alto'], $_SESSION['navegador']);

$aParametros = variables_Pagina($browser, $sistema);

$oPagina = new HTML_Page(array(
    'charset' => $aParametros[0],
    'language' => $aParametros[1],
    'cache' => $aParametros[2],
    'lineend' => $aParametros[3]
));

$oPagina->addStyleDeclaration($oEstilo, 'text/css');
$oPagina->addScript('javascript/checkeditor.js', "text/javascript");

$oPagina->addBodyContent("<br /><b>" . $sModEditor . "</b><br />");
$oPagina->addBodyContent("<form class=\"fckeditor\" target=proceso name=\"editor\" action=\"procesa_Editor.php\" method=\"post\" onsubmit=\"return checkform(this);\">");
$oPagina->addBodyContent($sEditorTit . "<input id=\"nombredoc\" name=\"nombredoc\" type=\"text\" size=10 value=\"" . $_GET['nombre'] . "\">&nbsp;&nbsp;");
$oPagina->addBodyContent($sEditorCod . "<input id=\"codigodoc\" name=\"codigodoc\" type=\"text\" size=10 value=\"" . $_GET['codigo'] . "\">&nbsp;&nbsp;");


$oFCKeditor = new FCKeditor('FCKeditor1');
$oFCKeditor->BasePath = 'javascript/FCKeditor/';
$oFCKeditor->Value = $_SESSION['datoseditor'];
$oPagina->addBodyContent($oFCKeditor->CreateHtml());
$oPagina->addBodyContent("<br />");

$sNavegador = $_SESSION['navegador'];

if ($sNavegador == "Microsoft Internet Explorer") {
    $oPagina->addBodyContent("<input type=\"submit\" class=\"b_activo\" onMouseOver=\"this.className='b_focus'\"" .
        "onMouseOut=\"this.className='b_activo'\"  value=\"Aceptar\"");
} else {
    $oPagina->addBodyContent("<input type=\"submit\" class=\"b_activo\" value=\"Aceptar\"");
}

$oPagina->addBodyContent("</form>");
//$oPagina->addBodyContent("<br />".$oVolver->to_Html());

unset ($_SESSION['datoseditor']);
$oPagina->display();
