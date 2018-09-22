<?php

/* Input to this file - $_GET['saveString']; */
require_once('Manejador_Base_Datos.class.php');
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_GET['saveString'])) die("no input");

$items = explode(",", $_GET['saveString']);
$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
for ($no = 0; $no < count($items); $no++) {
    $tokens = explode("-", $items[$no]);
    if ($tokens[0] != '0000' && $tokens[0] != '999999' && $tokens[0] != '0') {
        $oDb->iniciar_Consulta('UPDATE');
        $oDb->construir_Tablas(array('menu_nuevo'));
        $oDb->construir_Set(array('padre', 'orden'), array($tokens[1], $no));
        $oDb->construir_Where(array('id=' . $tokens[0]));
        $oDb->consulta();
    }
}
echo "Cambios Realizados\n";
