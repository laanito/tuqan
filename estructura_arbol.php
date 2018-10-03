<?php

use Tuqan\Classes\arbol_listas;
use Tuqan\Classes\FakePage;


require_once 'Classes/FakePage.php';
require_once 'Classes/generador_arboles.php';
require_once 'items.php';
require_once 'include.php';

if (!isset($_SESSION)) {
    session_start();
}
$aDatos['pkey'] = 'menu_nuevo.id';
$aDatos['padre'] = 'menu_nuevo.padre';
$aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
$aDatos['accion'] = 'menu_nuevo.accion';
$aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo');
$aDatos['order'] = 'orden';
$sCondicion = "menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id='" . $_SESSION['idiomaid'] . "'";
$aDatos['condicion'] = $sCondicion;
$oArbol = new arbol_listas($aDatos, 0);
$oArbol->genera_arbol_drag_and_drop();
$oPagina = new FakePage();
$oPagina->addStyleDeclaration('/lib/css/drag-drop-folder-tree.css', 'text/css');
$oPagina->addStyleDeclaration('/lib/css/context-menu.css', 'text/css');
$oPagina->addBodyContent($oArbol->to_Html());
$oPagina->addScript('/lib/js/ajax.js', 'text/javascript');
$oPagina->addScript('/lib/js/drag-drop-folder-tree.js', 'text/javascript');
$oPagina->addScript('/lib/js/context-menu.js', 'text/javascript');
$oPagina->addBodyContent('<form>');
$oPagina->addBodyContent('<input type=\"button\" onclick=\"treeObj.saveTree()\" value=\"Guardar\">');
$oPagina->addBodyContent('<input type=\"button\" onclick=\"parent.sndReq(\'atras\',\'\',0)\" value=\"Volver\">');
$oPagina->addBodyContent('</form>');

$oPagina->addBodyContent("<script type='text/javascript'>" .
    "treeObj = new JSDragDropTree();
    treeObj.setTreeId('arbol_menu');
    treeObj.setMaximumDepth(7);
    treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
    treeObj.initTree();
    </script>");//,'text/javascript');
$oPagina->Display();
