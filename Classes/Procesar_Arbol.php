<?php
namespace Tuqan\Classes;

use \Generador_Arboles;
use \Estilo_Pagina;
use \HTML_Page;
use \boton;

class Procesar_Arbol
{
    /**
     *     Esta es nuestra funcion recursiva, le pasamos el arbol donde iran todos los nodos, el nodo padre, es decir al
     *  que tenemos que enganchar los nodos que saquemos, el nivel maximo sobre el que permitiremos recursividad
     */

    function sacar_hijos(Generador_Arboles &$oArbol, &$oPadre, $iNivel, $iIdPadre = null)
    {

        if (($iNivel > 6) || ($iIdPadre == null)) {
            //No hacemos nada
        } else {
            //Creamos el nodo y lo enganchamos
            $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id', 'nombre'));
            $oDb->construir_Tablas(array('procesos'));
            $oDb->construir_Where(array('padre=' . $iIdPadre, 'activo=true'));
            $oDb->construir_Order(array('nombre'));
            $oDb->consulta();
            //Cogemos todos los hijos
            $aHijos = array();
            while ($aIterador = $oDb->coger_Fila()) {
                $oArbol->valor_check(0, $aIterador[0]);
                $nodo = $oArbol->Nuevo_Nodo($aIterador[1], 'a3', true);
                sacar_hijos($oArbol, $nodo, $iNivel + 1, $aIterador[0]);
                $aHijos[] = $aIterador[0];
                $oArbol->Situa_Nodo($nodo, $oPadre);
            }
        }
    }


    /**
     * Esta funcion nos muestra todos los procesos en forma de arbol
     */

    function procesa_Arbol_Procesos($sAccion, $aParametros)
    {
        $browser = $_SESSION['navegador'];
        $sistema = $_SESSION['sistema_operativo'];


        $sProviene = $aParametros['proviene'];
        $oEstilo = new Estilo_Pagina($_SESSION['ancho'], $_SESSION['alto'], $browser);

        $formatoPagina =new Formato_Pagina();
        $aParametros = $formatoPagina->variables_Pagina($browser, $sistema);


        $oPagina = new HTML_Page(array(
            'charset' => $aParametros[0],
            'language' => $aParametros[1],
            'cache' => $aParametros[2],
            'lineend' => $aParametros[3]
        ));
        $oPagina->addScript('javascript/TreeMenu.js', "text/javascript");
        $oPagina->addScript('javascript/radio.js', "text/javascript");
        $oPagina->addScript('javascript/cursor.js', "text/javascript");
        $oPagina->addStyleDeclaration($oEstilo, 'text/css');

        $oProcesos = new Generador_Arboles();
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
        $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'tipo_botones', 'idiomas'));
        $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sProviene . '\'',
            'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.nombre='" . $_SESSION['idioma'] . "'",
            'botones.tipo_botones=tipo_botones.id'));
        if ($_SESSION['perfil'] != 0) {
            $oBaseDatos->pon_Where('botones.permisos[' . $_SESSION['perfil'] . ']=true');
        }
        $oBaseDatos->pon_Order('botones.id');
        $oBaseDatos->consulta();
        $aBotones = array();
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aBotones[] = new boton($aIterador[0], $aIterador[1], $aIterador[2]);
        }
        if ($sAccion == 'procesos:general:ver:arbol_procesos') {
            $oProcesos->Inicia_Arbol('radio');
            $oLimpiar = new boton("Limpiar", "limpiar()", "noafecta");
        } else {
            $oProcesos->Inicia_Arbol('nodepende');
            $oMatriz = new boton("Ver Matriz", "parent.sndReq('matriz:procesos','',1)", "noafecta");
        }

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'nombre'));
        $oBaseDatos->construir_Tablas(array('procesos'));
        $oBaseDatos->construir_Where(array('padre=0', 'id>0', 'activo=true'));
        $oBaseDatos->construir_Order(array('nombre'));
        $oBaseDatos->consulta();
        //Para todos los padres sacamos sus hijos
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            if (is_array($aIterador)) {
                $oProcesos->valor_check(0, $aIterador[0]);
                $nodo_padre = $oProcesos->Nuevo_Nodo($aIterador[1], 'a1', true);

                /**
                 *     Llamada a la funcion recursiva, le pasamos el arbol, el nodo al que engancharemos lo que saquemos,
                 *  el nivel y la id del nodo correspondiente
                 */

                sacar_hijos($oProcesos, $nodo_padre, 2, $aIterador[0]);
                $oProcesos->Situa_Nodo($nodo_padre);
            }
        }
        $oBaseDatos->desconexion();
        if ($sAccion == 'procesos:general:ver:arbol_procesos') {
            $oPagina->addBodyContent("<br /><br /><div id=\"centra_botones\">");
            if (is_array($aBotones)) {
                foreach ($aBotones as $sValor) {
                    $oPagina->addBodyContent($sValor->to_Html());
                }
            }
            $oPagina->addBodyContent($oLimpiar->to_Html());
            $oPagina->addBodyContent("</div><br /><br />");
        } else {
            $oPagina->addBodyContent("<div id=\"centra_botones\">" . $oMatriz->to_Html() . "</div><br /><br />");

        }

        $oPagina->addBodyContent("<div id=\"arbol_centro\">" . $oProcesos->Pinta_Arbol() . "</div>");
        if ($sAccion == 'procesos:general:ver:arbol_procesos') {
            $oPagina->addBodyContent("<br /><br /><div id=\"centra_botones\">");
            if (is_array($aBotones)) {
                foreach ($aBotones as $sValor) {
                    $oPagina->addBodyContent($sValor->to_Html());
                }
            }
            $oPagina->addBodyContent($oLimpiar->to_Html());
            $oPagina->addBodyContent("</div><br /><br />");

        } else {
            $oPagina->addBodyContent("<br /><div id=\"centra_botones\">" . $oMatriz->to_Html() . "</div>");
        }
        return ($oPagina->toHtml());
    }


    function procesa_arbol($sTipo, $aParametros = null, $sAccion)
    {
        switch ($sTipo) {
            case 'menu':
                return ("menu|<iframe src=\"funcionesMenu.php?action=" . $aParametros . "\" frameborder=no scrolling=\"no\" width=100% height=375px></iframe>");
                break;
            case 'verpermisos':
                return ("calendario|<iframe id=\"verpermisos\" src=\"/ajax/form?action=administracion:usuarios:arbol:ver_permiso&sesion=&datos=" . $aParametros . "\" width=\"100%\" height=375px " .
                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>");
                break;
            case 'permisos':
                {
                    $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=administracion:general:arbol:ver:arbol_permiso&sesion=&datos=" . $iId . "\" width=\"100%\" " .
                        " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>");
                    break;
                }
            case 'documentos':
                {
                    $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=arbol_documentos&sesion=&datos=" . $iId . "\" width=\"100%\" " .
                        " frameborder=\"0\" style=\"z-index: 0\" overflow:\"scroll\" ><\iframe>");
                    break;
                }
            case 'perfil_doc':
                {
                    $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=administracion:general:arbol:ver:arbol_perfil_doc&sesion=&datos=" . $iId . "\" width=\"100%\" " .
                        " frameborder=\"0\" style=\"z-index: 0\" overflow:\"scroll\" ><\iframe>");
                    break;
                }
            case 'procesos:catalogos':
                {
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=procesos:general:arbol:ver:arbol_procesos&sesion=&datos=" . $sAccion . "\" width=\"100%\" " .
                        " frameborder=\"0\" style=\"z-index: 0\" overflow:\"scroll\" ><\iframe>");
                    break;
                }
            case 'procesos:matriz':
                {
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=arbol_matriz&sesion=&datos=\" width=\"100%\" " .
                        " frameborder=\"0\" style=\"z-index: 0\" overflow:\"scroll\" ><\iframe>");
                    break;
                }
            case 'permisosusuarios':
                {
                    return ("diviframe|<iframe id=\"arbol\" src=\"/ajax/form?action=administracion:modulos:arbol:permisos&sesion=&datos=" . $_SESSION['pagina'][$aParametros['numeroDeFila']] . "\" width=\"100%\" " .
                        " frameborder=\"0\" style=\"z-index: 0\" overflow:\"scroll\" ><\iframe>");
                    break;
                }
            default:
                {
                    return ("diviframe|Error");
                }
        }

    }

}