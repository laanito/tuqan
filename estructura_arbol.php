<?php

require_once('Manejador_Base_Datos.class.php');
require_once('HTML/Page.php');
require_once('HTML/CSS.php');
require_once('generador_arboles.php');
if (!isset($_SESSION)) {
    session_start();
}
require_once 'items.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'include.php';
require_once 'generador_arboles.php';
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
$oEstilo = new HTML_CSS();
$oCSSOpciones['filename'] = 'lib/css/drag-drop-folder-tree.css';
$oPagina = new HTML_Page();
$oPagina->addStyleSheet('lib/css/drag-drop-folder-tree.css', 'text/css');
$oPagina->addStyleSheet('lib/css/context-menu.css', 'text/css');
$oPagina->addBodyContent($oArbol->to_Html());
$oPagina->addScript('lib/js/ajax.js', 'text/javascript');
$oPagina->addScript('lib/js/drag-drop-folder-tree.js', 'text/javascript');
$oPagina->addScript('lib/js/context-menu.js', 'text/javascript');
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
