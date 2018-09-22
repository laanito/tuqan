<?php

/**
 *    The purpose of this file is to rename a node in the database
 *
 *    Input : $_GET['renameId']
 *            $_GET['newName']
 *
 *        OR
 *
 *            $_GET['deleteIds']
 *
 *        If a delete request is sent
 *
 ***/

require_once('Manejador_Base_Datos.class.php');
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['renameId']) && isset($_GET['newName'])) {
    if (isset($_GET['renameId'])) {
        //Renombrar
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb->iniciar_Consulta('UPDATE');
        $oDb->construir_Tablas(array('menu_idiomas_nuevo'));
        $oDb->construir_Set(array('valor'), array($_GET['newName']));
        $oDb->construir_Where(array('menu=' . $_GET['renameId'], 'idioma_id=' . $_SESSION['idiomaid']));
        $oDb->consulta();
    } else {
        //Eliminar
    }
} else {
    echo "Error al realizar la operacion";
}