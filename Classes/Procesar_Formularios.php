<?php
namespace Tuqan\Classes;

class Procesar_Formularios
{
    /**
     * Esta funcion devuelve el formulario pedido
     * @param String $sAccion
     * @return String
     */

    function procesa_Formulario($sAccion, $aParametros = null)
    {
        if ($aParametros != null) {
            unset ($_SESSION['dentroform']);
            if (isset($aParametros['numeroDeFila'])) {
                $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
            } else {
                $iId = $aParametros['id'];
            }
            return ("diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos=" .
                $sAccion . separador . $iId . "\"  width=\"90%\"" .
                " height=\"1000px\" scrolling=\"auto\" frameborder=\"0\"><\iframe>");
        } else {
            return ("diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos="
                . $sAccion . "\" width=\"90%\" " .
                " height=\"1000px\" scrolling=\"auto\" frameborder=\"0\"  ><\iframe>");
        }
    }

    /**
     * Esta funcion la separamos del resto de formularios por que para editar primero comprobamos que no esta
     * cerrada la accion
     */

    function procesa_Formulario_Accmejora($sAccion, $aParametros = null)
    {
        $iIdMejora = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Primero miramos que la accion no este cerrada.
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('cerrada'));
        $oBaseDatos->construir_Tablas(array('acciones_mejora'));
        $oBaseDatos->construir_Where(array('id=' . $iIdMejora));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            if ($aIterador[0] == true) {
                $sHtml = "alert|" . gettext('sAccYaCerrada');
                return $sHtml;
            } else {
                if ($aParametros != null) {
                    unset ($_SESSION['dentroform']);
                    if (isset($aParametros['numeroDeFila'])) {
                        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
                    } else {
                        $iId = $aParametros['id'];
                    }
                    return ("diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos=" . $sAccion . separador . $iId . "\"  width=\"90%\"" .
                        " height=\"1000px\" scrolling=\"auto\" frameborder=\"0\"><\iframe>");
                } else {
                    return ("diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos=" . $sAccion . "\" width=\"90%\" " .
                        " height=\"1000px\" scrolling=\"auto\" frameborder=\"0\"  ><\iframe>");
                }
            }
        }
        return "contenedor|Error";
    }

    function procesa_Formulario_Informe($sAccion, $aParametros)
    {
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('tipo_estado_auditoria.nombre'));
        $oBaseDatos->construir_Tablas(array('auditorias', 'tipo_estado_auditoria'));
        $oBaseDatos->construir_Where(array('(auditorias.id=\'' . $iId . '\')', 'auditorias.estado=tipo_estado_auditoria.id'));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            if ($aIterador[0] == gettext('sRealizado')) {
                return ("diviframe|<iframe id=\"form\" src=\"/ajax/form?action=formulario&sesion=&datos=" . $sAccion . separador . $iId . "\"  width=\"100%\"" .
                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>");
            } else {
                return ("alert|" . gettext('sFalloAuditoria'));
            }
        }
        return "contenedor|Error";
    }


    function subir_Fichero_Documento($aParametros)
    {
        if (is_array($aParametros)) {
            //Segun el tipo de documento ponemos los datos
            return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=documentossg:general:upload:nuevo&sesion=&datos=" .
                $aParametros['tipo'] . separador . $aParametros['idtipo'] . separador . $aParametros['vigor'] .
                "\"  width=\"100%\" frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
        }
        return "contenedor|Error";
    }


    function procesa_Ver_Cuestionario($aParametros)
    {
        $iIdLegislacion = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        return ("contenedor|<iframe id=\"formmedio\" src=\"/ajax/form?action=documentacion:formulariomedio:formulario:nuevo&sesion=&datos=" . $iIdLegislacion . "\"  width=\"100%\"" .
            " frameborder=\"0\" height=\"600 px\" style=\"z-index: 0\"><\iframe>");
    }
}