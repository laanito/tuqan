<?php
/*
 * Created on 27-ene-06
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'generadorGraficas.php';
require_once 'Manejador_Base_Datos.class.php';
include 'etc/qnova.conf.php';
if (!isset($_SESSION)) {
    session_start();
}

function obten_valor($aValor, $sMes, $sTipo)
{
    if ($sTipo == 'posterior') {
        if (12 < $sMes) {
            return false;
        } else if (array_key_exists($sMes + 1, $aValor)) {
            return $aValor[$sMes + 1];
        } else {
            return (obten_valor($aValor, $sMes + 1, $sTipo));
        }
    } else {
        if ($sMes < 1) {
            return false;
        } else if (array_key_exists($sMes - 1, $aValor)) {
            return $aValor[$sMes - 1];
        } else {
            return (obten_valor($aValor, $sMes - 1, $sTipo));
        }
    }
}

/*function graficaIndicador($iId,$iPid,$tipo,)
{*/
$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

$iId = $_GET['indicador'];
$iPid = $_GET['proceso'];
$tipo = $_GET['tipo'];
$modo = $_GET['modo'];
//$tipo=1; // 1 .- 12 ultimos meses, 2.- ultimo a�o, 3 .- comparativa anual. 4.- comparativa 2 ultimos a�os
$aCampos = array('valor', 'fecha');
$oDb->iniciar_Consulta('SELECT');
$aWhere = array('proceso=\'' . $iPid . '\'', 'indicador=\'' . $iId . '\'');
switch ($tipo) {
    case 1:
        $aWhere[] = 'fecha between (now()-interval \'12 month\') and now()';
        $aCampos = array('valor', 'to_char(fecha, \'DD/Mon/YYYY\')');
        break;
    case 2:
    case 4:
    case 5:
        $aWhere[] = 'fecha >= (extract(year from now()))';
        $aCampos = array('valor', 'extract(month from fecha)');
        break;
    case 3:
        $aCampos = array('valor', 'to_char(fecha, \'DD/Mon/YYYY\')');
        break;
}
$oDb->construir_Campos($aCampos);
$oDb->construir_Tablas(array('valores'));
$oDb->construir_Where($aWhere);
$oDb->construir_Order(array('fecha'));
$oDb->consulta();
$j = 0;

//Comienzo cambios

$aDatos = array();
$aDatos[0] = array();
while ($aIterador = $oDb->coger_Fila()) {
    $sFecha = preg_replace('Jan', 'Ene', $aIterador[1]);
    $sFecha = preg_replace('Apr', 'Abr', $sFecha);
    $sFecha = preg_replace('Aug', 'Ago', $sFecha);
    $sFecha = preg_replace('Dec', 'Dic', $sFecha);
    $aDatos[0][$sFecha] = $aIterador[0];
}

if ($tipo == 4) {
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array('valor', 'extract(month from fecha)'));
    $oDb->construir_Tablas(array('valores'));
    $oDb->construir_Where(array('proceso=\'' . $iPid . '\'', 'indicador=\'' . $iId . '\'',
        '(extract(year from fecha))=extract(year from (now()- interval \'1 year\'))'));
    $oDb->construir_Order(array('fecha'));
    $oDb->consulta();
    while ($aIterador = $oDb->coger_Fila()) {
        $aDatos[1][$aIterador[1]] = $aIterador[0];
    }
    //Debo comprobar que existen los datos para todos los meses, si no es asi interpolo
    foreach ($aDatos as $iKey => $aValor) {
        for ($iContador = 1; $iContador <= 12; $iContador++) {
            if (!array_key_exists($iContador, $aValor)) {
                $iAnterior = obten_valor($aValor, $iContador, 'anterior');
                $iPosterior = obten_valor($aValor, $iContador, 'posterior');
                if ($iAnterior != false) {
                    if ($iPosterior != false) {
                        $aDatos[$iKey][$iContador] = ($iAnterior + $iPosterior) / 2;
                    } else {
                        $aDatos[$iKey][$iContador] = $iAnterior;
                    }
                } else if ($iPosterior != false) {
                    $aDatos[$iKey][$iContador] = $iPosterior;
                } else {
                    $aDatos[$iKey][$iContador] = 0;
                }
            }
        }
        ksort($aDatos[$iKey]);
    }
} else if ($tipo == 5) {
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array('valor', 'extract(month from fecha) as mes', 'extract(year from fecha) as agno'));
    $oDb->construir_Tablas(array('valores'));
    $oDb->construir_Where(array('proceso=\'' . $iPid . '\'', 'indicador=\'' . $iId . '\'', 'extract(year from fecha)!=extract(year from now())'));
    $oDb->construir_Order(array('fecha'));
    $oDb->consulta();
    while ($aIterador = $oDb->coger_Fila()) {
        $aDatos[$aIterador[2]][$aIterador[1]] = $aIterador[0];
    }
    //Debo comprobar que existen los datos para todos los meses, si no es asi interpolo
    foreach ($aDatos as $iKey => $aValor) {
        for ($iContador = 1; $iContador <= 12; $iContador++) {
            if (!array_key_exists($iContador, $aValor)) {
                $iAnterior = obten_valor($aValor, $iContador, 'anterior');
                $iPosterior = obten_valor($aValor, $iContador, 'posterior');
                if ($iAnterior != false) {
                    if ($iPosterior != false) {
                        $aDatos[$iKey][$iContador] = ($iAnterior + $iPosterior) / 2;
                    } else {
                        $aDatos[$iKey][$iContador] = $iAnterior;
                    }
                } else if ($iPosterior != false) {
                    $aDatos[$iKey][$iContador] = $iPosterior;
                } else {
                    $aDatos[$iKey][$iContador] = 0;
                }
            }
        }
        ksort($aDatos[$iKey]);
    }
}
//Ahora sacamos los datos extras (valores tolerables, objetivo,etc)

$oDb->iniciar_Consulta('SELECT');
$oDb->construir_Campos(array('valor_inicial', 'valor_objetivo', 'valor_tolerable', 'valor_tolerable2'));
$oDb->construir_Tablas(array('indicadores'));
$oDb->construir_Where(array('id=' . $iId));
$oDb->consulta();
$aResultado = $oDb->coger_Fila();
$aDatosExtra = array('valor_inicial' => $aResultado[0], 'valor_objetivo' => $aResultado[1],
    'valor_tolerable' => $aResultado[2], 'valor_tolerable2' => $aResultado[3], 'titulo' => "Grafica indicador");

$oGrafica = new generadorGrafica($iId, $iPid, $modo, $aDatos, $aDatosExtra);
$oGrafica->pintaGrafica();
//}
