<?php
/**
 * Created on 10-ene-2006
 *
* LICENSE see LICENSE.md file
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 */
function cuestionario()
{
    $sHtml = "<div align=\"center\" <h1>" . gettext('sCuestionario') . "</h1><br />";
    $sHtml .= "<form  action=\"_self\" method=\"post\">";
    $sHtml .= "<p>";
    for ($iterador = 1; $iterador < 6; $iterador++) {
        $sHtml .= "<br /><br />" . gettext('sPreg') . $iterador;
        $sHtml .= "<br /><INPUT type=\"radio\" name=\pregunta" . $iterador . "\" value=\"Female\">" . gettext('sRes') . "1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $sHtml .= "<INPUT type=\"radio\" name=\pregunta" . $iterador . "\" value=\"Pepe\">" . gettext('sRes') . "<br /";
    }
    $sHtml .= "</p>";
    $sHtml .= "</form>";
    $sHtml .= "<INPUT type=\"submit\" value=" . gettext('sBotonEnviar') . "> <INPUT type=\"reset\"></div>";
    return $sHtml;
}

