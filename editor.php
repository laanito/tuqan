<?php
/**
* LICENSE see LICENSE.md file
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

/**
 * @return string
 */
function editor()
{
    $anchura = $_SESSION['ancho'];
    $altura = $_SESSION['alto'];
    $lenguaje = $_SESSION['idioma'];
    $browser = $_SESSION['navegador'];

    require_once('estilo.php');
    require_once 'javascript/FCKeditor/fckeditor.php';
    require_once 'HTML/Page.php';
    $oEstilo = new Estilo_Pagina($anchura, $altura, $browser);
    $oPagina = new HTML_Page();
    $oPagina->addStyleDeclaration($oEstilo, 'text/css');

    $oPagina->addBodyContent("<form name=\"editor\" action=\"procesa_Editor.php\" method=\"post\" target=\"_blank\">");
    $oFCKeditor = new FCKeditor('FCKeditor1');
    $oFCKeditor->BasePath = 'javascript/FCKeditor/';
    $oFCKeditor->Value = 'Default text in editor';
    $oPagina->addBodyContent($oFCKeditor->CreateHtml());
    $oPagina->addBodyContent("<br />");

    //Navegador

    $sNavegador = $_SESSION['navegador'];

    if ($sNavegador == "Microsoft Internet Explorer") {
        $oPagina->addBodyContent("<input class=\"b_activo\" onmouseout=\"this.className='b_activo'\" onmouseover=\"this.className='b_focus'\" type=\"submit\" value=\"Aceptar\">");
    } else {
        $oPagina->addBodyContent("<input class=\"b_activo\" type=\"submit\" value=\"Aceptar\">");
    }
    return $oPagina->toHTML();
}
