<?php
/**
 *
 * LICENSE see LICENSE.md file
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

use Tuqan\Classes\FakePage;

require_once 'javascript/FCKeditor/fckeditor.php';
require_once 'boton.php';
require_once 'include.php';
require_once 'Classes/FakePage.php';

if (!isset($_SESSION)) {
    session_start();
}


$oVolver = new boton("Volver", "parent.atraseditor()", "noafecta");

$oPagina = new FakePage();

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
