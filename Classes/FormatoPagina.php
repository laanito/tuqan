<?php
namespace Tuqan\Classes;
/**
 * Created on 15-nov-2005
 *
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 * Con esta función lo que pretendemos es detectar el navegador y el sistema
 * operativo del cliente para asignarle valores al HTML_Page y que nos muestre
 * la página principal esos valores y ese cliente.
 */

class Formato_Pagina {

    /**
     * @param $sNavegador
     * @param $sSistema
     * @return array
     */
function variables_Pagina($sNavegador, $sSistema) {

    switch ($sNavegador) {

        case "Konqueror":
            $sCharset = $_SESSION['encodingapache'];
            $sLanguage = "es";
            $sCache = "false";
            $sLineend = "unix";
            break;

        case "Microsoft Internet Explorer":
            $sCharset = $_SESSION['encodingapache'];
            $sLanguage = "es";
            $sCache = "false";
            $sLineend = "win";

            break;

        case "Netscape":
            if ($sSistema == "Win32") {
                $sCharset = $_SESSION['encodingapache'];
                $sLanguage = "es";
                $sCache = "false";
                $sLineend = "win";

            } else {
                $sCharset = $_SESSION['encodingapache'];
                $sLanguage = "es";
                $sCache = "false";
                $sLineend = "unix";

            }
            break;

        default:
            $sCharset = $_SESSION['encodingapache'];
            $sLanguage = "es";
            $sCache = "false";
            $sLineend = "unix";

    }

    return (array($sCharset, $sLanguage, $sCache, $sLineend));
    }
}