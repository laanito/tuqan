<?php
/**
* LICENSE see LICENSE.md file
 *

 * @version 0.1.0a
 * Procesamiento del cuestionario de medioambiente
 *
 */
if (!isset($_SESSION)) {
    session_start();
}
require_once 'constantes.inc.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'boton.php';
$oBoton = new boton("Volver", "parent.atras(-3)", "sincheck");
if (is_array($_POST)) {
    $aRespuestas = array();
    $bCorrecto = 'true';
    foreach ($_POST as $sKey => $sValue) {
        $aRespuestas[] = $sValue;

        if ($sValue != $_SESSION['deseado'][$sKey]) {
            $bCorrecto = 'false';
        }
    }
    $sPreguntas = "{" . implode(",", $_SESSION['preguntas']) . "}";
    $sRespuestas = "{" . implode(",", $aRespuestas) . "}";
    $aConsultas = array();
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

    $oBaseDatos->comienza_transaccion();
    $oBaseDatos->iniciar_Consulta('UPDATE');
    $oBaseDatos->construir_Tablas(array('legislacion_aplicable'));
    $oBaseDatos->construir_SetSin(array('verifica'),
        array($bCorrecto));
    $oBaseDatos->construir_Where(array('id=' . $_SESSION['legislacion']));
    $oBaseDatos->consulta();

    $oBaseDatos->iniciar_Consulta('INSERT');
    $oBaseDatos->construir_Campos(array('legislacion_aplicable', 'usuario', 'preguntas', 'respuestas', 'cumple', 'fecha'));
    $oBaseDatos->construir_Tablas(array('historico_cuestionarios'));
    $oBaseDatos->construir_Value(array($_SESSION['legislacion'], $_SESSION['userid'], $sPreguntas, $sRespuestas, $bCorrecto));
    $oBaseDatos->pon_ValueSin('now()');
    $oBaseDatos->consulta();


    $oBaseDatos->termina_transaccion();
    if ($bCorrecto == 'true') {
        $sHtml = $sTestSuperado . "<br/><br/>";
    } else {
        $sHtml = $sTestNoSuperado . "<br/><br/>";
    }
} else {
    $sHtml = $sErrorTest . "<br/><br/>";
}
$sHtml .= $oBoton->to_Html();
$oBaseDatos->desconexion();
echo $sHtml;
