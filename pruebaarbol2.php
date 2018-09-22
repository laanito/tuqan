<?php
/**
* LICENSE see LICENSE.md file
 */

require_once 'estilo.php';
require_once 'HTML/Page.php';
require_once 'FormatoPagina.php';
require_once 'boton.php';
/*$aMenu= array ('Documentos' => array ('Doc. Vigor' => 'documentacion:vigor',
                                              'Doc. Borrador' => 'documentacion:borra',
                                              'Manual' => 'documentacion:manual',
                                              'P.G.' => 'documentacion:pg',
                                              'I.T.'=>'documentacion:it',
                                              'H.T.' => 'documentacion:ht',
                                              'Prog. Audit.' => 'documentacion:progaudit',
                                              'Procesos'=>'documentacion:procesos'),
                       'Registros' => array ('listado'=>'documentacion:listadoregistros'),
                       'Normativa' => array ('Doc. Externa' => 'documentacion:externa'));
*/
require_once('Manejador_Base_Datos.class.php');
require_once('generador_arboles.php');
if (!isset($_SESSION)) {
    session_start();
}
$PHPSESSID = session_name();
$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
$sTabla = 'menu';
$aCampos = array('nodo', 'etiqueta', 'accion');
$oDb->iniciar_Consulta('SELECT');
$oDb->construir_Campos($aCampos);
$oDb->construir_Tablas(array($sTabla));
$oDb->pon_Where('menu=\'Documentacion\'');
$oDb->construir_Order('id');
$oDb->consulta();
//echo 'contenedor|';
if ($aIterador = $oDb->coger_Fila()) {
    $aMenu = array($aIterador[0] => array($aIterador[1] => $aIterador[2]));
    while ($aIterador = $oDb->coger_Fila()) {
        $aMenu[$aIterador[0]][$aIterador[1]] = $aIterador[2];
    }
    //Fin while
    //    print_r($aMenu);
}


$menu = new Generador_Arboles();
$menu->Inicia_Arbol('pepe');
foreach ($aMenu as $i => $valor) {
    if (is_array($valor)) {
        $nodo_padre = $menu->Nuevo_Nodo($i, 'a1'); //nodo1
        //$menu->Nuevo_Evento_Menu($nodo_padre,'');
        foreach ($valor as $i2 => $valor2) {
            $nodo = $menu->Nuevo_Nodo($i2, 'a2');  //nodo2
            //    $menu->Nuevo_Evento_Menu($nodo,'onClick',"parent.sndReq('".$valor2."','".$PHPSESSID."',1)");
            $menu->Situa_Nodo($nodo, $nodo_padre);
        }
        $menu->Situa_Nodo($nodo_padre);
        //    $menu->Nuevo_Evento_Menu($nodo,'onClick',"parent.sndReq('".$valor2."','".$PHPSESSID."',1)");
    } else {
        $nodo_padre = $menu->Nuevo_Nodo($i, 'a2');    //nodo2
        //    $menu->Nuevo_Evento_Menu($nodo_padre,'onClick',"parent.sndReq('".$valor."','".$PHPSESSID."',1)");
        $menu->Situa_Nodo($nodo_padre);
    }
}
$sPremenu = $menu->Pinta_Arbol();
$oEstilo = new Estilo_Pagina($anchura, $altura, $browser);
$aParametros = variables_Pagina($browser, $sistema);

$oPagina = new HTML_Page(array(
    'charset' => $aParametros[0],
    'language' => $aParametros[1],
    'cache' => $aParametros[2],
    'lineend' => $aParametros[3]
));

$oPagina->addStyleDeclaration($oEstilo, 'text/css');
$oPagina->addScript('javascript/TreeMenu.js', 'text/javascript');
$oPagina->addBodyContent($sPremenu);
$oPagina->addBodyContent("<br /><br /><P ALIGN=\"right\">Utilice el boton aceptar para guardar los cambios<br /><br />");
$oBoton = new boton('Aceptar', 'parent.sndReq(\'aceptar\',\'\',0)', 'general');
$oPagina->addBodyContent($oBoton->to_Html() . "</P>");
$oPagina->display();
