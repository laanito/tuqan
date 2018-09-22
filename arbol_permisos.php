<?php

require_once('Manejador_Base_Datos.class.php');
require_once('HTML/Page.php');
require_once('HTML/CSS.php');
require_once('generador_arboles.php');
require_once 'items.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'include.php';
require_once 'generador_arboles.php';
require_once 'boton.php';

if (!isset($_SESSION)) {
    session_start();
}

function arbol_permisos($iId)
{
    $aDatos['pkey'] = 'menu_nuevo.id';
    $aDatos['padre'] = 'menu_nuevo.padre';
    $aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
    $aDatos['accion'] = 'menu_nuevo.accion';
    $aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo');
    $aDatos['tipoboton'] = 'menu';
    $sCondicion = "menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id='" . $_SESSION['idiomaid'] . "' " .
        "AND ((menu_nuevo.padre=" . $iId . ") OR (menu_nuevo.padre IN (select m2.id from menu_nuevo m2 where padre=" . $iId . ")))";
    $aDatos['condicion'] = $sCondicion;
    $oArbol = new arbol_listas($aDatos, 0);
    $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array("menu_idiomas_nuevo.valor"));
    $oDb->construir_Tablas(array("menu_nuevo", "menu_idiomas_nuevo"));
    $oDb->construir_Where(array("menu_nuevo.id=" . $iId . " AND menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=" . $_SESSION['idiomaid']));
    $oDb->consulta();
    $aIteradorPrimero = $oDb->coger_Fila();
    $oArbol->accionMenu[$iId] = "parent.sndReq('administracion:permisos:comun:menu','',1,$iId)";
    if (is_array($oArbol->aArbol)) {
        foreach ($oArbol->aArbol as $sKey => $sValue) {
            if (is_array($sValue)) {
                foreach ($sValue as $sKeyInterno => $sValueInterno) {
                    $oArbol->accionMenu[$sKeyInterno] = "parent.sndReq('administracion:permisos:comun:menu','',1,$sKeyInterno)";
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos(array('botones.id', 'menu', 'botones_idiomas.valor'));
                    $oDb->construir_Tablas(array('botones', 'botones_idiomas'));
                    $oDb->construir_Where(array($sKeyInterno . '=menu', 'botones_idiomas.boton=botones.id', 'botones_idiomas.idioma_id=' . $_SESSION['idiomaid']));
                    $oDb->consulta();
                    unset ($aArray);
                    while ($aIterador = $oDb->coger_Fila()) {
                        $aArray[$aIterador[0]] = $aIterador[2] . separador . "boton";
                        $oArbol->aArbol[$sKeyInterno] = $aArray;
                        $oArbol->accionBotones[$aIterador[0]] = "parent.sndReq('administracion:permisos:comun:botones','',1,$aIterador[0])";
                    }
                }
            }
        }
    }
    $oArbol->aOpciones['permisos'] = true;
    $oArbol->genera_arbol_drag_and_drop_permisos($iId, $aIteradorPrimero[0]);
    $oEstilo = new HTML_CSS();
    $oCSSOpciones['filename'] = 'lib/css/drag-drop-folder-tree.css';
    $oPagina = new HTML_Page();
    $oPagina->addStyleSheet('lib/css/drag-drop-folder-tree.css', 'text/css');
    $oVolver = new boton(gettext('sBotonVolver'), "parent.sndReq('administracion:modulos:listado:nuevo','',0)", "noafecta");
    $sBoton = "<br />" . $oVolver->to_Html() . "<br /><br />";
    $oPagina->addBodyContent($sBoton);
    $oPagina->addBodyContent($oArbol->to_Html());
    $oPagina->addScript('lib/js/ajax.js', 'text/javascript');
    $oPagina->addScript('lib/js/drag-drop-folder-tree.js', 'text/javascript');
    $oPagina->addBodyContent("<script type='text/javascript'>" .
        "treeObj = new JSDragDropTree();
        treeObj.setTreeId('arbol_menu');
        treeObj.setMaximumDepth(7);
        treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
        treeObj.initTree();
        </script>");//,'text/javascript');
    $oPagina->addBodyContent("</div>");
    $oPagina->addBodyContent("<div id=\"contenedor_derecha\">");
    $oPagina->addBodyContent("</div>");
    $oPagina->addStyleSheet($oEstilo);
    return $oPagina->toHtml();
}

function arbol_permisos_verPerfil($iPerfil)
{
    $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $aDatos['pkey'] = 'menu_nuevo.id';
    $aDatos['padre'] = 'menu_nuevo.padre';
    $aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
    $aDatos['accion'] = 'menu_nuevo.accion';
    $aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo');
    $aDatos['tipoboton'] = 'menu';

    $iId = 0;
    //Sacamos primero los id de los menus los modulos que tengamos permisos
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array("menu_idiomas_nuevo.valor"));
    $oDb->construir_Tablas(array("menu_nuevo", "menu_idiomas_nuevo"));
    $oDb->construir_Where(array("menu_nuevo.id=" . $iId . " AND menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=" . $_SESSION['idiomaid']));
    $oDb->consulta();

    $sCondicion = "menu_nuevo.permisos[" . $iPerfil . "]='t' AND menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id='" . $_SESSION['idiomaid'] . "' " .
        "AND ((menu_nuevo.padre=" . $iId . ") OR (menu_nuevo.padre IN (select m2.id from menu_nuevo m2 where padre=" . $iId . ")))";
    $aDatos['condicion'] = $sCondicion;
    $oArbol = new arbol_listas($aDatos, 0);

    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array("menu_idiomas_nuevo.valor"));
    $oDb->construir_Tablas(array("menu_nuevo", "menu_idiomas_nuevo"));
    $oDb->construir_Where(array("menu_nuevo.id=" . $iId . " AND menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=" . $_SESSION['idiomaid']));
    $oDb->consulta();
    $aIteradorPrimero = $oDb->coger_Fila();
    foreach ($oArbol->aArbol as $sKey => $sValue) {
        if (is_array($sValue)) {
            foreach ($sValue as $sKeyInterno => $sValueInterno) {
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('botones.id', 'menu', 'botones_idiomas.valor'));
                $oDb->construir_Tablas(array('botones', 'botones_idiomas'));
                $oDb->construir_Where(array($sKeyInterno . '=menu', 'botones_idiomas.boton=botones.id', 'botones_idiomas.idioma_id=' . $_SESSION['idiomaid']));
                $oDb->consulta();
                unset ($aArray);
                while ($aIterador = $oDb->coger_Fila()) {
                    $aArray[$aIterador[0]] = $aIterador[2] . separador . "boton";
                    $oArbol->aArbol[$sKeyInterno] = $aArray;
                }
            }
        }
    }
    $oArbol->aOpciones['permisos'] = true;
    $oArbol->genera_arbol_drag_and_drop_permisos($iId, $aIteradorPrimero[0]);
    $oEstilo = new HTML_CSS();
    $oCSSOpciones['filename'] = 'lib/css/drag-drop-folder-tree.css';
    $oPagina = new HTML_Page();
    $oPagina->addStyleSheet('lib/css/drag-drop-folder-tree.css', 'text/css');
    $oPagina->addBodyContent($oArbol->to_Html());
    $oPagina->addScript('lib/js/ajax.js', 'text/javascript');
    $oPagina->addScript('lib/js/drag-drop-folder-tree.js', 'text/javascript');
    $oPagina->addBodyContent("<script type='text/javascript'>" .
        "treeObj = new JSDragDropTree();
        treeObj.setTreeId('arbol_menu');
        treeObj.setMaximumDepth(7);
        treeObj.setMessageMaximumDepthReached('Maximum depth reached'); // If you want to show a message when maximum depth is reached, i.e. on drop.
        treeObj.initTree();
        </script>");//,'text/javascript');
    $oPagina->addStyleSheet($oEstilo);
    return $oPagina->toHtml();
}
