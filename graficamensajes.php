<?php

namespace Tuqan;

require_once 'Image/Graph.php';
require_once 'Classes/generadorGraficas.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/generador_SQL.php';

use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Classes\generadorGrafica;

if (!isset($_SESSION)) {
    session_start();
}
$aDatos = array();
$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

$oDb->iniciar_Consulta('SELECT');
$oDb->construir_Campos(array('count(id)', 'to_char(fecha, \'DD/Mon/YYYY\')'));
$oDb->construir_Tablas(array('mensajes'));
$oDb->construir_Where(array('fecha > (now() - INTERVAL \'1 month\')'));
$oDb->construir_Order(array('fecha'));
$oDb->construir_Group(array('fecha'));
$oDb->consulta();

while ($aIterador = $oDb->coger_Fila()) {
    $sFecha = preg_replace('Jan', 'Ene', $aIterador[1]);
    $sFecha = preg_replace('Apr', 'Abr', $sFecha);
    $sFecha = preg_replace('Aug', 'Ago', $sFecha);
    $sFecha = preg_replace('Dec', 'Dic', $sFecha);
    $aDatos[0][$sFecha] = $aIterador[0];
}
$oGrafica = new generadorGrafica(null, null, 'bar', $aDatos, array('valor_objetivo' => 0, 'titulo' => "Estadisticas mensajes"));
$oGrafica->pintaGrafica();

