<?php
/**
 * Created on 21-nov-2005
 *
* LICENSE see LICENSE.md file
 *

 * @version 0.2.0a
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * Procesamiento temporal del editor, simplemente saca por pantalla
 * @todo insercion en la base de datos
 */


/**
 * Esta funcion nos crea un array para meter en la base de datos con una entrada por usuario puesto a false
 * return String
 */
function crearArrayPermisos()
{
    require_once 'Manejador_Base_Datos.class.php';

    $sArrayPermisos = "{";
    for ($iContador = 1; $iContador < iNumeroPerfiles; $iContador++) {
        if ($_SESSION['perfil'] == $iContador) {
            $sArrayPermisos .= "true,";
        } else {
            $sArrayPermisos .= "false,";
        }
    }
    $sArrayPermisos .= "false}";
    return $sArrayPermisos;
}

//Fin Funcion

require_once 'constantes.inc.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'HTMLcleaner/htmlcleaner.php';
if (!isset($_SESSION)) {
    session_start();
}
//Limpiamos los caracteres que pueda traer el documento que dan problemas
// $_SESSION['contenidoDoc']=$_POST['FCKeditor1'];
//$sContenido = htmlcleaner::cleanup($_POST['FCKeditor1']);
$sContenido = $_POST['FCKeditor1'];

$iArea = $_POST['areadoc'];
$sNombre = $_POST['nombredoc'];
$sCodigo = $_POST['codigodoc'];
$oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

if (isset($_SESSION['iddoc'])) {
    $oBaseDatos->comienza_transaccion();
    $oBaseDatos->iniciar_Consulta('UPDATE');
    $oBaseDatos->construir_SetSlashes(array('nombre', 'codigo'),
        array($sNombre, $sCodigo));
    $oBaseDatos->construir_Tablas(array('documentos'));
    $oBaseDatos->construir_Where(array('id=\'' . $_SESSION['iddoc'] . '\''));
    $oBaseDatos->consulta();

    $oBaseDatos->iniciar_Consulta('UPDATE');
    $oBaseDatos->construir_Set(array('contenido'), array($sContenido));
    $oBaseDatos->construir_Tablas(array('contenido_texto'));
    $oBaseDatos->construir_Value(array($sContenido));
    $oBaseDatos->construir_Where(array('id=\'' . $_SESSION['iddoc'] . '\''));
    $oBaseDatos->consulta();

    $oBaseDatos->termina_transaccion();
    unset ($_SESSION['iddoc']);
} else {

    //Insertamos en la base de datos el documento

    $sArrayPermisos = crearArrayPermisos();
    if (isset($_SESSION['version'])) {
        $sVersion = $_SESSION['version'];
    } else {
        $sVersion = "1.0.0";
    }
    $oBaseDatos->comienza_transaccion();
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico', 'perfil_tareas'));
    $oBaseDatos->construir_Tablas(array('tipo_documento'));
    $oBaseDatos->construir_Where(array('id=' . $_SESSION['idtipo']));
    $oBaseDatos->consulta();
    $aIterador = $oBaseDatos->coger_Fila();
    if ($aIterador) {
        $sArrayPermisosVer = $aIterador[0];
        $sArrayPermisosNueva = $aIterador[1];
        $sArrayPermisosModificar = $aIterador[2];
        $sArrayPermisosRevisar = $aIterador[3];
        $sArrayPermisosAprobar = $aIterador[4];
        $sArrayPermisosHistorico = $aIterador[5];
        $sArrayPermisosTareas = $aIterador[6];
    } else {
        $sArrayPermisosVer = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosNueva = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosModificar = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosRevisar = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosAprobar = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosHistorico = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
        $sArrayPermisosTareas = '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}';
    }

    $oBaseDatos->iniciar_Consulta('INSERT');
    $oBaseDatos->construir_Campos(array('nombre', 'codigo', 'tipo_documento', 'estado',
        'perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar',
        'perfil_aprobar', 'perfil_historico', 'perfil_tareas', 'revision', 'activo'));
    $oBaseDatos->construir_Tablas(array('documentos'));
    $oBaseDatos->construir_Value(array($sNombre, $sCodigo, $_SESSION['idtipo'], iBorrador,
        $sArrayPermisosVer, $sArrayPermisosNueva, $sArrayPermisosModificar, $sArrayPermisosRevisar,
        $sArrayPermisosAprobar, $sArrayPermisosHistorico, $sArrayPermisosTareas, $sVersion, 't'));
    unset ($_SESSION['idtipo']);
    $oBaseDatos->consulta();

    //Seleccionamos el id del documento para poder insertar su contenido

    $iIdDocumento = 'SELECT last_value' . ',\'' . $sContenido . '\'' . ' from documentos_id_seq';

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('last_value'));
    $oBaseDatos->construir_Tablas(array('documentos_id_seq'));
    $oBaseDatos->consulta();
    $aIterador = $oBaseDatos->coger_Fila();
    if ($aIterador) {
        //Ponemos el id del documento para en caso que le demos de nuevo al boton editemos
        $_SESSION['iddoc'] = $aIterador[0];
    }
    //Insertamos en la base de datos su contenido

    $oBaseDatos->iniciar_Consulta('INSERT');
    $oBaseDatos->construir_Campos(array('id', 'contenido'));
    $oBaseDatos->construir_Tablas(array('contenido_texto'));
    $oBaseDatos->pon_Select($iIdDocumento);
    $oBaseDatos->consulta();

    $oBaseDatos->termina_transaccion();
    unset($_SESSION['version']);
}

unset ($_SESSION['nombredoc']);
unset ($_SESSION['codigodoc']);

echo "<p style=\"color:#432452; position:absolute;top:150px;left:150px;" .
    "font-family:sans-serif;font-size:20px;text-align:center; " .
    "vertical-align:middle;\">" . $sDocExito . "</p>";
