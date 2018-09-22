<?php
/**
* LICENSE see LICENSE.md file
 */

if (isset($_POST['idvalor'])) {
    $iIdValor = $_POST['idvalor'];
} else {
    $iIdProceso = $_POST['proceso'];
    $iIdIndicador = $_POST['indicador'];
}
$fValor = $_POST['valor'];
if (!isset($_SESSION)) {
    session_start();
}
require_once 'boton.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'HTML/Page.php';
require_once 'estilo.php';
include_once 'include.php';
$oEstilo = new Estilo_Pagina($_SESSION['ancho'], $_SESSION['alto'], $_SESSION['navegador']);
$oPagina = new HTML_Page();

$oPagina->addStyleDeclaration($oEstilo, 'text/css');

$oVolver = new boton($sBotonVolver, "parent.atras(-3)", "noafecta");
//Sacamos si habia algun valor ya para el indicador
$oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
if (isset($iIdValor)) {
    $sFecha = date('c');
    $oBaseDatos->iniciar_Consulta('UPDATE');
    $oBaseDatos->construir_SetSin(array('fecha', 'valor'),
        array('array_append(fecha,\'' . $sFecha . '\')', 'array_append(valor,\'' . $fValor . '\')')
    );
    $oBaseDatos->construir_Tablas(array('valores'));
    $oBaseDatos->construir_Where(array('id=' . $iIdValor));
    $oBaseDatos->consulta();
} else {
    $oBaseDatos->iniciar_Consulta('INSERT');
    $oBaseDatos->construir_Campos(array('proceso', 'indicador', 'valor', 'fecha'));
    $oBaseDatos->construir_Value(array($iIdProceso, $iIdIndicador, '{' . $fValor . '}', '{now()}'));
    $oBaseDatos->construir_Tablas(array('valores'));
    $oBaseDatos->construir_Where(array('id=' . $iIdValor));
    $oBaseDatos->consulta();
}
echo "<p style=\"color: #66377e; font-size: 8pt;\" class=\"valores\">" . gettext('sInsCorrecta') . "</p>" . $oVolver->to_Html();


