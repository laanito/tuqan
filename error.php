<?php
/**
* LICENSE see LICENSE.md file
 *
 * Esta es la pagina de errores segun el error que nos pasan por get mostraremos diversa informacion
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

require_once 'HTML/Page.php';

/**
 * Nuestro objeto pagina
 * @var object
 */

$oPagina = new HTML_Page();
$oPagina->addBodyContent("Hay un error en la base de datos, pongase en contacto con el administracion de la aplicacion");
$oPagina->display();