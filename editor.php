<?php
/**
* LICENSE see LICENSE.md file
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

use Tuqan\Classes\FakePage;

/**
 * @return string
 */
function editor()
{

    require_once 'javascript/FCKeditor/fckeditor.php';
    require_once 'Classes/FakePage.php';
    $oPagina = new FakePage();
    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');

    $oPagina->addBodyContent("<form name=\"editor\" action=\"procesa_Editor.php\" method=\"post\" target=\"_blank\">");
    $oFCKeditor = new FCKeditor('FCKeditor1');
    $oFCKeditor->BasePath = 'javascript/FCKeditor/';
    $oFCKeditor->Value = 'Default text in editor';
    $oPagina->addBodyContent($oFCKeditor->CreateHtml());
    $oPagina->addBodyContent("<br />");
    $oPagina->addBodyContent("<input class=\"b_activo\" type=\"submit\" value=\"Aceptar\">");
    return $oPagina->toHTML();
}
