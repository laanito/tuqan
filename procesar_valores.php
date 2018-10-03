<?php
/**
* license see license.md file
 */

use tuqan\classes\fakepage;
use tuqan\classes\manejador_base_datos;

if (isset($_post['idvalor'])) {
    $iidvalor = $_post['idvalor'];
} else {
    $iidproceso = $_post['proceso'];
    $iidindicador = $_post['indicador'];
}
$fvalor = $_post['valor'];
if (!isset($_session)) {
    session_start();
}
require_once 'boton.php';
require_once 'classes/manejador_base_datos.class.php';
require_once 'classes/fakepage.php';
include_once 'include.php';
$opagina = new fakepage();

$opagina->addstyledeclaration('/css/tuqan', 'text/css');

$ovolver = new boton($sbotonvolver, "parent.atras(-3)", "noafecta");
//sacamos si habia algun valor ya para el indicador
$obasedatos = new manejador_base_datos($_session['login'], $_session['pass'], $_session['db']);
if (isset($iidvalor)) {
    $sfecha = date('c');
    $obasedatos->iniciar_consulta('update');
    $obasedatos->construir_setsin(array('fecha', 'valor'),
        array('array_append(fecha,\'' . $sfecha . '\')', 'array_append(valor,\'' . $fvalor . '\')')
    );
    $obasedatos->construir_tablas(array('valores'));
    $obasedatos->construir_where(array('id=' . $iidvalor));
    $obasedatos->consulta();
} else {
    $obasedatos->iniciar_consulta('insert');
    $obasedatos->construir_campos(array('proceso', 'indicador', 'valor', 'fecha'));
    $obasedatos->construir_value(array($iidproceso, $iidindicador, '{' . $fvalor . '}', '{now()}'));
    $obasedatos->construir_tablas(array('valores'));
    $obasedatos->construir_where(array('id=' . $iidvalor));
    $obasedatos->consulta();
}
$opagina->addbodycontent("<p style=\"color: #66377e; font-size: 8pt;\" class=\"valores\">" .
    gettext('sinscorrecta') . "</p>" . $ovolver->to_html());
$opagina->display();


