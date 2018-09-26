<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *

 * @version 0.2.1c
 * Archivo que hace el proceso de las opciones comunes para una mayor legibilidad
 */

use \boton;


class Procesar_Listados
{
    /**
     * Esta funcion devuelve el codigo de los menus
     * @param $aParametros
     * @return string
     */

    function procesa_Ver_Cuestionario($aParametros)
    {
        $iIdLegislacion = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        return ("contenedor|<iframe id=\"formmedio\"".
            " src=\"peticiones_Principal.php?action=documentacion:formulariomedio:formulario:nuevo&sesion=&datos=" .
            $iIdLegislacion . "\"  width=\"100%'" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>");
    }


    /**
     * Funcion para el procesado de datos del formulario tipo documentos opcion perfiles
     */

    function procesa_Listado_Permisos_Documentos($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbCount = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbPerfil = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $iIdManual = $aParametros['id'];
        $_SESSION['idDoc'] = $aParametros['id'];
        $sHtml = "<table>";
        $sHtml .= "<tr>";
        $sHtml .= "<th>" . gettext('Permission') . "</th>";
        $sHtml .= "<th>" . gettext('View') . "</th>";
        $sHtml .= "<th>" . gettext('sPCONuevaVersion') . "</th>";
        $sHtml .= "<th>" . gettext('Modify') . "</th>";
        $sHtml .= "<th>" . gettext('sBotonRevisar') . "</th>";
        $sHtml .= "<th>" . gettext('sBotonAprobar') . "</th>";
        $sHtml .= "<th>" . gettext('sBotonHist') . "</th>";
        $sHtml .= "<th>" . gettext('sTTareas') . "</th>";
        $sHtml .= "</tr>";

        // Selecciona los permisos para el documento
        $aCampos = array('id', "nombre as \"" . gettext('sPNNombre') . "\"");
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('perfiles'));
        $oDb->construir_Order(array('id'));
        $oDb->construir_Where(array("perfiles.activo='t'"));
        $oDb->consulta();


        while ($aIterador = $oDb->coger_Fila()) {
            if ($aIterador[0] > 0) {
                $sHtml .= "<tr>";
                $sHtml .= "<td>" . $aIterador[1] . "<td>";
                $aCampos = array('id', "perfil_ver[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilVer') . "\"", "perfil_nueva[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilNueva') . "\"", "perfil_modificar[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilModificar') . "\"", "perfil_revisar[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilRevisar') . "\"", "perfil_aprobar[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilAprobar') . "\"", "perfil_historico[$aIterador[0]] as \"" . 
                    gettext('sPNPerfilHistorico') . "\"", "perfil_tareas[$aIterador[0]] as \"" . gettext('sPNPerfilTareas') . "\"");
                $oDbPerfil->iniciar_Consulta('SELECT');
                $oDbPerfil->construir_Campos($aCampos);
                $oDbPerfil->construir_Tablas(array('documentos'));
                $oDbPerfil->construir_Where(array('id=' . $iIdManual));
                $oDbPerfil->consulta();
                $aPermisos = $oDbPerfil->coger_Fila();
                if ($aPermisos[1] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilVer_" . $aIterador[0] . "\" NAME=\"ver" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilVer_" . $aIterador[0] . "\" NAME=\"ver" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[2] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilNuevo_" . $aIterador[0] . "\" NAME=\"nueva" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilNuevo_" . $aIterador[0] . "\" NAME=\"nueva" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[3] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilModificar_" . $aIterador[0] . "\" NAME=\"modifica" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilModificar_" . $aIterador[0] . "\" NAME=\"modifica" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[4] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilRevisar_" . $aIterador[0] . "\" NAME=\"revisar" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilRevisar_" . $aIterador[0] . "\" NAME=\"revisar" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[5] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilAprobar_" . $aIterador[0] . "\" NAME=\"aprobar" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilAprobar_" . $aIterador[0] . "\" NAME=\"aprobar" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[6] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilHistorico_" . $aIterador[0] . "\" NAME=\"historico" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilHistorico_" . $aIterador[0] . "\" NAME=\"historico" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[7] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilTareas_" . $aIterador[0] . "\" NAME=\"tareas" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilTareas_" . $aIterador[0] . "\" NAME=\"tareas" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
        }
        $sHtml .= "</table>";

        $aCampos = array("count(id)");
        $oDbCount->iniciar_Consulta('SELECT');
        $oDbCount->construir_Campos($aCampos);
        $oDbCount->construir_Tablas(array('perfiles'));
        $oDbCount->construir_Where(array("perfiles.activo='t'", "id <> 0"));
        $oDbCount->consulta();
        $aNumPerfiles = $oDbCount->coger_Fila();
        if ($aNumPerfiles[0] == 0) {
            $oVolver = new boton(gettext('sPNVolver'), "atras(-1)", "noafecta");
            $sHtml .= "<br />" . $oVolver->to_Html();
        } else {
            $oVolver = new boton(gettext('sPNVolver'), "atras(-1)", "noafecta");
            $sHtml .= "<br />" . $oVolver->to_Html();
            $oPermisos = new boton(gettext('sPNActualizar'), "sndReq('documentacion:manual:comun:cambiar:actualizar','',1)", "noafecta");
            $sHtml .= "<br />" . $oPermisos->to_Html();
        }


        return ($sHtml);
    }

    function procesa_Listado_Permisos($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbPerfil = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $sHtml = "<table>";
        $sHtml .= "<tr>";
        $sHtml .= "<th>Permisos</th>";
        $sHtml .= "<th>Ver</th>";
        $sHtml .= "<th>Nueva Version</th>";
        $sHtml .= "<th>Modificar</th>";
        $sHtml .= "<th>Revisar</th>";
        $sHtml .= "<th>Aprobar</th>";
        $sHtml .= "<th>Historico</th>";
        $sHtml .= "<th>Tareas</th>";
        $sHtml .= "</tr>";

        $iIdDoc = $_SESSION['pagina'][$aParametros['fila']];
        $_SESSION['idDocPermisos'] = $iIdDoc;
        $aCampos = array('id', "nombre as \"" . gettext('sPNNombre') . "\"");
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('perfiles'));
        $oDb->construir_Order(array('id'));
        $oDb->construir_Where(array("perfiles.activo='t'"));
        $oDb->consulta();
        while ($aIterador = $oDb->coger_Fila()) {
            if ($aIterador[0] > 0) {
                $sHtml .= "<tr>";
                $sHtml .= "<td>" . $aIterador[1] . "<td>";
                $aCampos = array('id', "perfil_ver[$aIterador[0]] as \"" . gettext('sPNPerfilVer') .
                    "\"", "perfil_nueva[$aIterador[0]] as \"" . gettext('sPNPerfilNueva') . "\"", "perfil_modificar[$aIterador[0]] as \"" .
                    gettext('sPNPerfilModificar') . "\"", "perfil_revisar[$aIterador[0]] as \"" . gettext('sPNPerfilRevisar') .
                    "\"", "perfil_aprobar[$aIterador[0]] as \"" . gettext('sPNPerfilAprobar') . "\"", "perfil_historico[$aIterador[0]] as \"" .
                    gettext('sPNPerfilHistorico') . "\"", "perfil_tareas[$aIterador[0]] as \"" . gettext('sPNPerfilTareas') . "\"");
                $oDbPerfil->iniciar_Consulta('SELECT');
                $oDbPerfil->construir_Campos($aCampos);
                $oDbPerfil->construir_Tablas(array('tipo_documento'));
                $oDbPerfil->construir_Where(array('id=' . $iIdDoc));
                $oDbPerfil->consulta();
                $aPermisos = $oDbPerfil->coger_Fila();


                if ($aPermisos[1] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilVer_" . $aIterador[0] . "\" NAME=\"ver" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilVer_" . $aIterador[0] . "\" NAME=\"ver" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[2] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilNuevo_" . $aIterador[0] . "\" NAME=\"nueva" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilNuevo_" . $aIterador[0] . "\" NAME=\"nueva" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[3] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilModificar_" . $aIterador[0] . "\" NAME=\"modifica" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilModificar_" . $aIterador[0] . "\" NAME=\"modifica" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[4] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilRevisar_" . $aIterador[0] . "\" NAME=\"revisar" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilRevisar_" . $aIterador[0] . "\" NAME=\"revisar" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[5] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilAprobar_" . $aIterador[0] . "\" NAME=\"aprobar" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilAprobar_" . $aIterador[0] . "\" NAME=\"aprobar" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[6] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilHistorico_" . $aIterador[0] . "\" NAME=\"historico" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilHistorico_" . $aIterador[0] . "\" NAME=\"historico" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                if ($aPermisos[7] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilTareas_" . $aIterador[0] . "\" NAME=\"tareas" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"perfilTareas_" . $aIterador[0] . "\" NAME=\"tareas" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
        }
        $sHtml .= "</table>";
//        $sHilo="llega";

        $oVolver = new boton(gettext('sPNVolver'), "atras(-1)", "noafecta");
        $sHtml .= "<br />" . $oVolver->to_Html();
        $oPermisos = new boton(gettext('sPNActualizar'), "sndReq('administracion:permisos:comun:cambiar:actualizar','',1)", "noafecta");
        $sHtml .= "<br />" . $oPermisos->to_Html();


        return ($sHtml);
    }


    /**
     * Esta funcion devuelve el año pedido del calendario
     * @param array $aParametros
     * @return String
     */


    function procesa_Ver_Producto($aParametros)
    {
        $iIdProducto = $_SESSION['pagina'][$aParametros['numeroDeFila']];

        $oBoton = new boton("Volver", "atras(-1)", "noafecta");


        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Sacamos los datos de la incidencia
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('productos.nombre', 'productos.valor',
            'CASE WHEN productos.activo=true THEN \'Activo\' ELSE \'Inactivo\' END', 'proveedores.nombre'));
        $oBaseDatos->construir_Tablas(array('productos', 'proveedores'));
        $oBaseDatos->construir_Where(array('productos.proveedor=proveedores.id', 'productos.id=' . $iIdProducto));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml = "<table class=\"productos\" >";
            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"alineado1\"><span class=\"campo\">" . gettext('sProdDesc') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td class=\"alineado2\">" . $aIterador[0] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"alineado1\"><span class=\"campo\">" . gettext('sProdValorH') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td class=\"alineado2\">" . $aIterador[1] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"alineado1\"><span class=\"campo\">" . gettext('sProdProv') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td class=\"alineado2\">" . $aIterador[3] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"alineado1\"><span class=\"campo\">" . gettext('sProdActivo') . " &nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td class=\"alineado2\">" . $aIterador[2] . "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "</table><br />";
            $sHtml .= $oBoton->to_Html();
        } else {
            $sHtml = gettext('sProdError') . "<br />" . $oBoton->to_Html();
        }
        $oBaseDatos->desconexion();
        return $sHtml;
    }


    function procesa_Ver_Incidencia($aParametros)
    {

        $iIdIncidencia = $_SESSION['pagina'][$aParametros['numeroDeFila']];

        $oBoton = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Sacamos los datos de la incidencia
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('to_char(fecha, \'DD/MM/YYYY\')', 'no_pedido', 'comentario'));
        $oBaseDatos->construir_Tablas(array('incidencias'));
        $oBaseDatos->construir_Where(array('id=' . $iIdIncidencia));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml = "<table class=\"incidencias\">";
            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sIncFecha') . " &nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[0] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sIncPedido') . " &nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[1] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sIncComentario') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[2] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "</table><br /><br />";
            $sHtml .= $oBoton->to_Html();
        } else {
            $sHtml = gettext('sIncError') . "<br />" . $oBoton->to_Html();
        }
        $oBaseDatos->desconexion();
        return $sHtml;
    }


    function procesa_Ver_Contacto($aParametros)
    {

        $iIdContacto = $_SESSION['pagina'][$aParametros['numeroDeFila']];

        $oBoton = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Sacamos los datos de la incidencia
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('nombre', 'telefono1', 'telefono2', 'fax', 'movil'));
        $oBaseDatos->construir_Tablas(array('contactos_proveedores'));
        $oBaseDatos->construir_Where(array('id=' . $iIdContacto));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml = "<table class=\"contactos\">";
            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sCtcNombre') . " &nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[0] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sCtcTlf1') . " &nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[1] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sCtcTlf1') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[2] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sCtcFax') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[3] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sCtcMovil') . "&nbsp;&nbsp;&nbsp;&nbsp;</span></td>";
            $sHtml .= "<td>" . $aIterador[4] . "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "</table><br /><br />";
            $sHtml .= $oBoton->to_Html();
        } else {
            $sHtml = gettext('sCtcError') . "<br />" . $oBoton->to_Html();
        }
        $oBaseDatos->desconexion();
        return $sHtml;
    }


    function procesa_Menu($sAccion)
    {
        return ("menu|<iframe src=\"funcionesMenu.php?action=" . $sAccion . "\" frameborder=no scrolling=no width=100% height=375px></iframe>");
    }


    /**
     * Esta funcion devuelve el menu superior de calidad o medioambiente
     * @param String $sAccion
     * @return String
     */

    function crea_Menu_Superior($sAccion)
    {
        $aDatos['pkey'] = 'menu_nuevo.id';
        $aDatos['padre'] = 'menu_nuevo.padre';
        $aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
        $aDatos['accion'] = 'menu_nuevo.accion';
        $aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo', 'idiomas');
        $aDatos['order'] = 'orden ASC';
        $sCondicion = "menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=idiomas.id " . "
    and idiomas.id='" . $_SESSION['idioma'] . "'";

  if ($_SESSION['admin'] == true || $_SESSION['perfil'] == '0') {

        } else {
             $sCondicion .= " and menu_nuevo.permisos[" . $_SESSION['perfil'] . "]=true";
         }

        $aDatos['condicion'] = $sCondicion;
        $oArbol = new arbol_listas($aDatos, 0);
        $oArbol->genera_arbol_menu();
        $sHtml = "submenu|";
        $sHtml .= $oArbol->to_Html();
        return $sHtml;
    }


    /**
     * Funcion para procesar listados comunes
     * @param $sAccion
     * @param $aParametros
     * @return mixed
     */
    function procesa_Listado($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        switch ($sAccion) {
            case 'auditorias:auditor:seleccionausuario':
            case 'formacion:planes:seleccionausuario':
            case 'mejora:acmejora:seleccionausuario':
            case 'inicio:tarea:seleccionausuario':
                $aCampos = array('id', 'primer_apellido', 'segundo_apellido', 'nombre');
                $aBuscar = array('primer_apellido');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios'));
                $oDb->construir_Where(array('id<>0'));
                break;

            case 'inicio:tarea:seleccionaindicadores':
                $aCampos = array('id', 'nombre', 'definicion');
                $aBuscar = array('nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('indicadores'));
                $oDb->construir_Where(array('id>=0'));
                break;

            case 'documentos:registros:listarfila:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    if ($_SESSION['pagina'][$aParametros['fila']] == "1") {
                        $_SESSION['tiporegistro'] = 'ficha_personal';
                    } else {
                        $_SESSION['tiporegistro'] = 'requisitos_puesto';
                    }

                }
                $sTabla = $_SESSION['tiporegistro'];
                $aCampos = array('id', "codigo as \"" . gettext('sPCCodigo') . "\"", "nombre as \"" . gettext('sPANombre') . "\"",
                    "to_char(fecha,'dd/mm/yyyy') as \"" . gettext('sPAFecha') . "\"", "revision as \"" . gettext('sPARevision') . "\"",
                    "case when " . $sTabla . ".activo then 'Si' else 'No' end as \"" . gettext('sPAActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($_SESSION['tiporegistro']));
                break;

            case 'documentacion:aai:ver':
                $sTabla = 'documentos';
                //        $aBuscar=array('nombres'=>array(gettext('sPLCodigo'),gettext('sPLNombre')),
                //                       'campos'=>array('doc1.codigo','doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdAai, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdAai, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:metas:ver:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['objetivosglobales'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'metas_objetivos';
                    $aCampos = array('id', "plan_accion as \"" . gettext('sPCPlanAccion') . "\"",
                        "fecha_consecucion as \"" . gettext('sPCFechaConsecucion') . "\"",
                        "responsable as \"" . gettext('sPCResponsable') . "\"", "recursos as \"" . gettext('sPCRecursos') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('metas_objetivos'));
                    $oDb->construir_Where(array('objetivo_id=' . $_SESSION['objetivosglobales'], 'metas_objetivos.activo=\'t\''));
                    break;
                }

            case 'documentacion:objetivos:seguimiento:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['objetivo'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'seguimientos';
                    $aCampos = array('id', "fecha as \"" . gettext('sPCFecha') . "\"", "observaciones as \"" .
                        gettext('sFCObservaciones') . "\"", 'objetivos');
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('seguimientos'));
                    $oDb->construir_Where(array('objetivos=' . $_SESSION['objetivo']));
                    break;
                }

            case 'indicadores:objetivos:vermetas:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['objetivosindicadores'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'metas_indicadores';
                    $aCampos = array('id', "plan_accion as \"" . gettext('sPCPlanAccion') . "\"", "fecha_consecucion as \"" .
                        gettext('sPCFechaConsecucion') . "\"", "responsable as \"" . gettext('sPCResponsable') .
                        "\"", "recursos as \"" . gettext('sPCRecursos') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('metas_indicadores'));
                    $oDb->construir_Where(array('objetivo_id=' . $_SESSION['objetivosindicadores'], 'metas_indicadores.activo=\'t\''));
                    break;
                }

            case 'catalogo:valoresindicador:editar:fila':
                $sTabla = 'valores';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['indicador'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                //ICS
                $_SESSION['contenido_proceso'] = 0;
                //ICS
                $aCampos = array('valores.id', "valores.valor as \"" . gettext('sPCValor') .
                    "\"", "to_char(valores.fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('valores'));
                $oDb->construir_Where(array('valores.indicador=' . $_SESSION['indicador'], 'valores.proceso=' . $_SESSION['contenido_proceso'],
                    'valores.activo=true'
                ));
                break;

            case 'catalogo:indicadoresproceso:nuevo':
                $sTabla = 'indicadores';
                $aCampos = array('indicadores.id', "indicadores.nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('indicadores', 'contenido_procesos'));
                $oDb->construir_Where(array('indicadores.activo=\'t\'', 'contenido_procesos.id=' . $_SESSION['contenido_proceso'],
                    '(NOT(indicadores.id=any(contenido_procesos.indicadores)))'
                ));
                break;

            case 'catalogo:documentoproceso:nuevo':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCCodigo')),
                    'campos' => array('nombre', 'codigo'));
                $aCampos = array('documentos.id', "documentos.codigo as \"" . gettext('sPCCodigo') . "\"", "documentos.nombre as \"" . gettext('sPCNombre') . "\"", "documentos.revision as \"" . gettext('sPCRevision') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos', 'contenido_procesos'));
                $oDb->construir_Where(array('documentos.estado=' . iVigor, 'documentos.activo=\'t\'', 'contenido_procesos.id=' . $_SESSION['contenido_proceso'],
                    '(NOT(documentos.id=any(contenido_procesos.anejos)))'
                ));
                break;

            case 'catalogo:areadoc:nuevo':
                $sTabla = 'areas';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla));
                break;

            case 'catalogo:documentosproceso:ver':
                $sTabla = 'documentos';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['contenido_proceso'] = $aParametros['fila'];
                }
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCCodigo')),
                    'campos' => array('documentos.nombre', 'documentos.codigo'));
                $aCampos = array('documentos.id', "documentos.codigo as \"" . gettext('sPCCodigo') . "\"", "documentos.nombre as \"" . gettext('sPCNombre') . "\"", "documentos.revision as \"" . gettext('sPCRevision') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos', 'contenido_procesos'));
                $oDb->construir_Where(array('documentos.activo=\'t\'', 'contenido_procesos.id=' . $_SESSION['contenido_proceso'],
                    'documentos.id=any(contenido_procesos.anejos)'
                ));
                break;

            case 'catalogo:indicadores:ver:radio':

                $sTabla = 'indicadores';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    //Nos llega la id del proceso luego tenemos que sacar la id del contenido_proceso
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('contenido_procesos.id'));
                    $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos'));
                    $oBaseDatos->construir_Where(array('contenido_procesos.proceso=' . $aParametros['fila'],
                        'contenido_procesos.documento=documentos.id', 'documentos.estado=' . iVigor));
                    $oBaseDatos->consulta();
                    if ($aIterador = $oBaseDatos->coger_Fila()) {
                        $_SESSION['contenido_proceso'] = $aIterador[0];
                    } else {
                        //Como no vamos a ningun listado quitamos los valores
                        array_pop($_SESSION['ultimolistado']);
                        array_pop($_SESSION['ultimolistadodatos']);
                        array_pop($_SESSION['paginaanterior']);
                        return '|alert|No hay una ficha definida';
                    }
                } else {
                    array_pop($_SESSION['ultimolistadodatos']);
                    array_pop($_SESSION['ultimolistadodatos']);
                    array_pop($_SESSION['paginaanterior']);
                    return '|alert|No hay una ficha definida';
                }
                $aCampos = array('indicadores.id', "indicadores.nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('indicadores', 'contenido_procesos'));
                $oDb->construir_Where(array('indicadores.activo=\'t\'', 'contenido_procesos.id=' . $_SESSION['contenido_proceso'],
                    'indicadores.id=any(contenido_procesos.indicadores)'
                ));
                break;

            case 'indicadores:valoresindicador:ver:fila':
                $sTabla = 'valores';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['indicador'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                //ICS
                $_SESSION['contenido_proceso'] = 0;
                //ICS
                $aCampos = array('valores.id', "valores.valor as \"" . gettext('sPCValor') . "\"",
                    "to_char(valores.fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('valores'));
                $oDb->construir_Where(array('valores.indicador=' . $_SESSION['indicador'], 'valores.proceso=' . $_SESSION['contenido_proceso'],
                    'valores.activo=true'
                ));
                break;

            case 'catalogo:objetivosindicador:editar:fila':
                $sTabla = 'objetivos_indicadores';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['indicador'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aCampos = array('objetivos_indicadores.id', "objetivos.nombre as \"" .
                    gettext('sPCNombre') . "\"", "valor_objetivo as \"" . gettext('sPCValorObjetivo') .
                    "\"", "to_char(fecha_objetivo, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "objetivos_indicadores.observaciones as \"" . gettext('sPCObservaciones') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('objetivos_indicadores', 'objetivos'));
                $oDb->construir_Where(array('indicador=' . $_SESSION['indicador'], 'proceso=' . $_SESSION['contenido_proceso'],
                    'objetivos_indicadores.activo=true', 'objetivos.id=objetivo'
                ));
                break;

            case 'formacion:planes:seleccionadocumentoext':
                $aCampos = array('id', 'codigo', 'nombre');
                $aBuscar = array('codigo');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('tipo_documento=' . iIdExterno));
                break;

            case 'documentosas:legislacion:fichama:selecciona':
                $aCampos = array('id', 'codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('tipo_documento=' . iIdFichaMa, 'estado=' . iVigor));
                break;

            case 'mejora:listado:ver':
                $sTabla = 'acciones_mejora';

                if ($_SESSION['areasactivadas']) {
                    $aBuscar = array('nombres' => array(gettext('sPLTipo')),
                        'campos' => array('tipo_acciones_idiomas.valor'));
                    $aCampos = array('acciones_mejora.id', "case when acciones_mejora.cliente is null then '" .
                        gettext('sPCNinguno') . "' else " .
                        "(select clientes.nombre from clientes where id=acciones_mejora.cliente) end as \"" . gettext('sPCCliente') .
                        "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                        "tipo_acciones_idiomas.valor as \"" . gettext('sPCTipo') . "\"", "case when cerrada then '" . gettext('sPCCerrada') .
                        "' else '" . gettext('sPCAbierta') . "' end as \"" . gettext('sPCEstado') . "\"",
                        "areas.nombre as \"" . gettext('sPMArea') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('acciones_mejora', 'tipo_acciones', 'tipo_acciones_idiomas', 'areas'));
                    $oDb->construir_Where(array('acciones_mejora.tipo=tipo_acciones.id',
                        'acciones_mejora.area_id=areas.id',
                        'acciones_mejora.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                        'tipo_acciones.id=tipo_acciones_idiomas.mejora', 'tipo_acciones_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                } else {
                    $aBuscar = array('nombres' => array(gettext('sPLTipo')),
                        'campos' => array('tipo_acciones_idiomas.valor'));
                    $aCampos = array('acciones_mejora.id', "case when acciones_mejora.cliente is null then '" . gettext('sPCNinguno') . "' else " .
                        "(select clientes.nombre from clientes where id=acciones_mejora.cliente) end as \"" . gettext('sPCCliente') . "\"",
                        "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                        "tipo_acciones_idiomas.valor as \"" . gettext('sPCTipo') . "\"", "case when cerrada then '" .
                        gettext('sPCCerrada') . "' else '" . gettext('sPCAbierta') . "' end as \"" . gettext('sPCEstado') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('acciones_mejora', 'tipo_acciones', 'tipo_acciones_idiomas'));
                    $oDb->construir_Where(array('acciones_mejora.tipo=tipo_acciones.id',
                        'tipo_acciones.id=tipo_acciones_idiomas.mejora', 'tipo_acciones_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                }
                break;

            case 'inicio:tareas:ver':
                $sTabla = 'tareas';
                $aBuscar = array('nombres' => array(gettext('sPLDocumento')),
                    'campos' => array('doc.nombre'));
                if ($_SESSION['perfil'] == 0) {
                    $aCampos = array('ta.id', "us1.nombre||' '||us1.primer_apellido||' '||us1.segundo_apellido as \"" . gettext('sPCUsuOrigen') . "\"",
                        "us2.nombre||' '||us2.primer_apellido||' '||us2.segundo_apellido as \"" . gettext('sPCUsuDestino') .
                        "\"", "tt.nombre as \"" . gettext('sPCAccion') . "\"", "doc.codigo||' '||doc.nombre as \"" . gettext('sPCDocumento') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us1', 'usuarios us2', 'documentos doc'));
                    $oDb->construir_Where(array("(tt.id=ta.accion)", "(ta.usuario_origen=us1.id)", "ta.usuario_destino=us2.id", "(ta.activo='t')", "(doc.id=ta.documento)"));
                } else {
                    $aCampos = array('ta.id', "us.nombre||' '||us.primer_apellido||' '||us.segundo_apellido as \"" .
                        gettext('sPCUsuOrigen') . "\"", "tt.nombre as \"" . gettext('sPCAccion') . "\"",
                        "doc.codigo||' '||doc.nombre as \"" . gettext('sPCDocumento') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us', 'documentos doc'));
                    $oDb->construir_Where(array("(tt.id=ta.accion)", "(ta.usuario_origen=us.id)", "(ta.activo='t')", "(doc.id=ta.documento)"));
                }
                if ($_SESSION['perfil'] != 0) {
                    $oDb->construir_Where(array("(ta.usuario_destino=" . $_SESSION['userid'] . ")"));
                }


                break;

            //case 'inicio:mensajes:':
            case 'inicio:mensajes:ver':
            case 'inicio:mensajes:inicial':
                $sTabla = 'mensajes';
                $aCampos = array('mensajes.id', "mensajes.titulo as \"" . gettext('sPCTitulo') . "\"",
                    "to_char(mensajes.fecha, 'DD/MM/YYYY') as \"" . gettext('sPCEnviado') . "\"",
                    "to_char(mensajes.fecha, 'hh24:mi') as \"" . gettext('sPCHora') . "\"",
                    "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . gettext('sPCRemitente') . "\"");
                $aBuscar = array('nombres' => array('titulo', 'enviado'),
                    'campos' => array('titulo', 'fecha'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla, 'usuarios'));
                if ($_SESSION['perfil'] != 0) {
                    $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ") OR (destinatario=0)", "(mensajes.activo='t')",
                        "usuarios.id=mensajes.origen"
                    ));
                } else {
                    $oDb->construir_Where(array("usuarios.id=mensajes.origen AND mensajes.activo='t'"));
                }
                break;

            case 'inicio:historicomensajes:ver':
                $sTabla = 'mensajes';
                $aBuscar = array('nombres' => array(gettext('sPLTitulo'), gettext('sPLEnviado')),
                    'campos' => array('titulo', 'fecha'));
                $aCampos = array('mensajes.id', 'destinatario', 'titulo', 'to_char(fecha, \'DD/MM/YYYY\') as enviado',
                    'to_char(fecha, \'hh24:mi\') as hora', 'usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido as remitente');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla, 'usuarios'));
                if ($_SESSION['userid'] != 0) {
                    $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ")", "(mensajes.activo='f')"));
                }
                break;

            case 'documentacion:listadoregistros:ver:fila':
            case 'formacion:fichapersonal:ver':
            case 'formacion:reqpuesto:ver':
                if ($_SESSION['directo'] == 1) {
                    if ($aParametros['fila'] == "1") {
                        $_SESSION['tiporegistro'] = 'ficha_personal';
                    } else if ($aParametros['fila'] == "2") {
                        $_SESSION['tiporegistro'] = 'requisitos_puesto';
                    }
                } else if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    if ($_SESSION['pagina'][$aParametros['fila']] == "1") {
                        $_SESSION['tiporegistro'] = 'ficha_personal';
                    } else if ($_SESSION['pagina'][$aParametros['fila']] == "2") {
                        $_SESSION['tiporegistro'] = 'requisitos_puesto';
                    }
                }
                $sTabla = $_SESSION['tiporegistro'];
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('codigo', 'nombre'));
                $aCampos = array('id', "codigo as \"" . gettext('sPCCodigo') . "\"", "nombre as \"" . gettext('sPCNombre') .
                    "\"", "to_char(fecha,'dd/mm/yyyy') as \"" . gettext('sPCFecha') . "\"", "revision as \"" . gettext('sPCRevision') . "\"");

                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($_SESSION['tiporegistro']));
                $oDb->construir_Where(array($sTabla . '.activo=true'));
                break;

            case 'documentacion:documentonormativa:ver':
                $sTabla = 'documentos';
                $aCampos = array('documentos.id', "documentos.codigo as \"" . gettext('sPCCodigo') . "\"", "documentos.nombre as \"" . gettext('sPCNombre') . "\"", "documentos.revision as \"" . gettext('sPCRevision') . "\"");
                $aBuscar = array('codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('documentos.tipo_documento=' . iIdNormativa,
                    'documentos.activo=\'t\''));
                break;

            case 'formacion:cursos:ver':
                $sTabla = 'plantilla_curso';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "case when tipo is null then '" .
                    gettext('sPCNinguno') . "' else (select nombre from tipos_cursos where tipos_cursos.id=plantilla_curso.tipo) end as \"" .
                    gettext('sPCTipo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('plantilla_curso'));
                $oDb->construir_Where(array('plantilla_curso.activo=\'t\''));
                break;

            case 'formacion:inscripcion:ver':
                $sTabla = 'cursos';
                $aBuscar = array('nombres' => array(gettext('sPLCurso')),
                    'campos' => array('cursos.nombre'));
                $aCampos = array('cursos.id', "cursos.nombre as \"" . gettext('sPCNombre') . "\"", "tipo_estados_curso.nombre as \"" . gettext('sPCEstado') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('cursos', 'tipo_estados_curso'));
                $oDb->construir_Where(array('(cursos.estado=tipo_estados_curso.id)', 'cursos.activo=\'t\''));
                break;

            case 'formacion:inscripcion:asistentes:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'alumnos';
                $aBuscar = array('usuario');
                $aCampos = array('alumnos.id', "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" .
                    gettext('sPCUsuario') . "\"",
                    "case when alumnos.inscrito=true then (case when alumnos.verificado=true then '" . gettext('sPCAlta') . "' 
                            else '" . gettext('sPCSolicitadaAlta') . "' end) else (case when alumnos.verificado=true then '" .
                    gettext('sPCBaja') . "' else '" . gettext('sPCSolicitadaBaja') . "' end) end as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('alumnos', 'usuarios'));
                $oDb->construir_Where(array('alumnos.curso=' . $_SESSION['curso'], 'alumnos.usuario=usuarios.id'));
                break;

            case 'formacion:planes:ver':
                $sTabla = 'plan_formacion';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"",
                    "CASE WHEN vigente=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCVigente') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('plan_formacion'));
                $oDb->construir_Where(array('plan_formacion.activo=\'t\''));
                break;

            case 'formacion:reqpuesto:formaciontecnicarq:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['reqpuesto'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'requisitos_puesto_ft';
                $aCampos = array('id', "formacion_tecnica as \"" . gettext('sPCFormacionTecnica') . "\"",
                    "opcional as \"" . gettext('sPCOpcional') . "\"", "horas as \"" . gettext('sPCHoras') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla));
                $oDb->construir_Where(array('requisitos=' . $_SESSION['reqpuesto']));
                break;

            case 'auditorias:programa:ver':
                $sTabla = 'programa_auditoria';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"",
                    "CASE WHEN vigente=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCVigente') .
                    "\"", "revision as \"" . gettext('sPCRevision') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('programa_auditoria'));
                $oDb->construir_Where(array('programa_auditoria.activo=\'t\''));
                break;

            case 'auditorias:plan:ver':
                $sTabla = 'auditorias';
                $aBuscar = array('nombres' => array(gettext('sPLDescripcion')),
                    'campos' => array('descripcion'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('id'));
                $oDb->construir_Tablas(array('programa_auditoria'));
                $oDb->construir_Where(array('programa_auditoria.vigente=\'t\''));
                $oDb->consulta();
                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['progauditoria'] = $aIterador[0];
                }
                $aCampos = array('auditorias.id', "descripcion as \"" . gettext('sPCDescripcion') . "\"",
                    "tipo_estado_auditoria.nombre as \"" . gettext('sPCEstado') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "case when (fecha_realiza=null) then '-' else to_char(fecha_realiza, 'DD/MM/YYYY') end as \"" . gettext('sPCRealizacion') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('auditorias', 'tipo_estado_auditoria', 'programa_auditoria'));
                $oDb->construir_Where(array('auditorias.programa=programa_auditoria.id', 'auditorias.estado=tipo_estado_auditoria.id',
                    'programa_auditoria.vigente=\'t\'', 'auditorias.activo=\'t\''));
                break;

            case 'indicadores:indicadores:ver':
                $sTabla = 'indicadores';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                if ($_SESSION['areasactivadas']) {
                    $aCampos = array('indicadores.id', "indicadores.nombre as \"" . gettext('sPCNombre') . "\"", "areas.nombre as \"" . gettext('sPMArea') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('indicadores', 'areas'));
                    $oDb->construir_Where(array('indicadores.activo=\'t\'',
                        'indicadores.area_id=areas.id',
                        'indicadores.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                    ));
                } else {
                    $aCampos = array('indicadores.id', "indicadores.nombre as \"" . gettext('sPCNombre') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('indicadores'));
                    $oDb->construir_Where(array('indicadores.activo=\'t\''
                    ));
                }
                break;

            case 'indicadores:objetivos:ver':

                $sTabla = 'objetivos';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('og.nombre'));
                $aCampos = array('og.id',
                    "og.nombre as \"" . gettext('sPANombre') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "case when og.estado=2 then '" . gettext('sPABorrador') . "' when og.estado=3 then '" .
                    gettext('sPARevisado') . "' else '" . gettext('sPAVigor') . "' end as \"" . gettext('sPAEstado') . "\"",
                    "og.fecha_revision::date as \"" . gettext('sPCFecha_revision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "og.fecha_aprobacion::date as \"" . gettext('sPCFecha_aprovacion') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);

                $oDb->construir_Tablas(array('usuarios u1 right outer join objetivos og1 on u1.id=og1.revisadopor',
                    'usuarios u2 right outer join objetivos og on u2.id=og.aprobadopor'));
                $oDb->construir_Where(array('og.activo=\'t\'', 'og1.id=og.id'));

                break;

            case 'documentacion:manual:ver':
                $sTabla = 'documentos';

                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') .
                    "\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdManual, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdManual, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;


            case 'documentacion:pg:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPg, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdPg, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:pe:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPe, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdPe, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:procesoarchivo:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') . "\"",
                    "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdArchivoProc, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdArchivoProc, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:planamb:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPlanAmb, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdPlanAmb, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:docvigor:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "tipo_documento_idiomas.valor as \"" . gettext('sPCTipo') . "\""
                );

                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);

                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'tipo_documento', 'tipo_documento_idiomas'));

                $oDb->construir_Where(array('doc1.estado=' . iVigor, 'doc1.activo=true',
                    'doc1.tipo_documento<>' . iIdPolitica, 'doc1.tipo_documento<>' . iIdManual,
                    'doc2.estado=' . iVigor, 'doc2.activo=true',
                    'doc2.tipo_documento<>' . iIdPolitica, 'doc2.tipo_documento<>' . iIdManual,
                    'doc1.id=doc2.id', 'tipo_documento.id=doc1.tipo_documento', 'tipo_documento.id=doc2.tipo_documento',
                    'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
                break;

            case 'documentacion:frl:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') .
                    "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdFichaMa, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdFichaMa, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:docborrador:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') . "\"",
                    "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "tipo_documento_idiomas.valor as \"" . gettext('sPCTipo') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'tipo_documento', 'tipo_documento_idiomas'));

                $oDb->construir_Where(array('doc1.estado=' . iBorrador . ' OR doc1.estado=' . iRevisado, 'doc1.activo=true',
                    'doc2.estado=' . iBorrador . ' OR doc2.estado=' . iRevisado, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'tipo_documento.id=doc1.tipo_documento', 'tipo_documento.id=doc2.tipo_documento',
                    'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
                break;

            case 'documentacion:registros:ver':
                $sTabla = 'registros';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('registros'));
                break;

            case 'documentacion:docformatos:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') . "\"",
                    "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                    "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdExterno, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdExterno, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'formacion:inscripcion:asistentes':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'alumnos';
                $aBuscar = array('usuario');
                $aCampos = array('alumnos.id', "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . gettext('sPCUsuario') . "\"",
                    "case when alumnos.inscrito=true then (case when alumnos.verificado=true then '" . gettext('sPCAlta') . "' 
                            else '" . gettext('sPCSolicitadaAlta') . "' end) else (case when alumnos.verificado=true then '" . gettext('sPCBaja') . "' else '" . gettext('sPCSolicitadaBaja') . "' end) end as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('alumnos', 'usuarios'));
                $oDb->construir_Where(array('alumnos.curso=' . $_SESSION['curso'], 'alumnos.usuario=usuarios.id'));
                break;

            case 'recursos:reqpuesto:formaciontecnicarq':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['reqpuesto'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'requisitos_puesto_ft';
                $aCampos = array('id', "formacion_tecnica as \"" . gettext('sPCFormacionTecnica') . "\"", "opcional as \"" .
                    gettext('sPCOpcional') . "\"", "horas as \"" . gettext('sPCHoras') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla));
                $oDb->construir_Where(array('requisitos=' . $_SESSION['reqpuesto']));
                break;

            case 'auditoria:programa:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['progauditoria'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLDescripcion')),
                    'campos' => array('descripcion'));
                $sTabla = 'auditorias';
                $aCampos = array('auditorias.id', "descripcion as \"" . gettext('sPCDescripcion') . "\"", "tipo_estado_auditoria.nombre as \"" . gettext('sPCEstado') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "case when (fecha_realiza=null) then '-' else to_char(fecha_realiza, 'DD/MM/YYYY') end as \"" . gettext('sPCRealizacion') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('auditorias', 'tipo_estado_auditoria'));
                $oDb->construir_Where(array('auditorias.programa=' . $_SESSION['progauditoria'], 'auditorias.estado=tipo_estado_auditoria.id',
                    'auditorias.activo=\'t\''));
                break;

            case 'documentacion:general:verhistorico':
                $sTabla = 'documentos';
                $aCampos = array('documentos.id', "documentos.revision as \"" . gettext('sPCRevision') . "\"", "us1.nombre||' '||us1.primer_apellido||' '||us1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "us2.nombre||' '||us2.primer_apellido||' '||us2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos', 'usuarios us1', 'usuarios us2'));
                $oDb->construir_Where(array('documentos.nombre=\'' . $aParametros['nombre'] . '\'', 'documentos.codigo=\'' . $aParametros['codigo'] . '\'',
                    'documentos.revisado_por=us1.id', 'documentos.aprobado_por=us2.id', 'documentos.estado=' . iHistorico));
                break;

            case 'formacion:planes:ver:fila':
                //Solo si es la primera vez ponemos la variable de sesion, en sucesivas veces accedemos directamente
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['planId'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'cursos';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('cursos.nombre'));
                $aCampos = array('cursos.id', "cursos.nombre as \"" . gettext('sPCNombre') . "\"", "tipo_estados_curso.nombre as \"" . gettext('sPCEstado') . "\"",
                    "to_char(cursos.fecha_prevista, 'DD/MM/YYYY') as \"" . gettext('sPCPrevisto') . "\"",
                    "to_char(cursos.fecha_realizacion, 'DD/MM/YYYY') as \"" . gettext('sPCDiaRealiz') . "\"",
                    "to_char(cursos.fecha_realizacion, 'hh24:mi') as \"" . gettext('sPCHoraRealiz') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('cursos', 'tipo_estados_curso'));
                $oDb->construir_Where(array('cursos.plan=' . $_SESSION['planId']
                , 'cursos.estado=tipo_estados_curso.id',
                    'cursos.activo=\'t\''));
                break;

            case 'administracion:usuarios:seleccionaficha':
                $aCampos = array('id', 'codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal'));
                break;

            case 'documentacion:legislacion:seleccionaley':
                $aCampos = array('id', 'codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('documentos.tipo_documento=' . iIdNormativa,
                    'documentos.activo=\'t\'', 'documentos.estado=' . iVigor));
                break;

            case 'documentacion:legislacion:seleccionaficha':
                $aCampos = array('id', 'codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('documentos.tipo_documento=' . iIdFichaMa, 'documentos.activo=\'t\'',
                    'documentos.estado=' . iVigor));
                break;

            case 'administracion:usuarios:seleccionarequisitos':
                $aCampos = array('id', 'codigo', 'nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('requisitos_puesto'));
                break;

            case 'documentosas:usuario:general:selecciona':
                $aCampos = array('id', 'primer_apellido', 'segundo_apellido', 'nombre');
                $aBuscar = array('primer_apellido');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios'));
                $oDb->construir_Where(array('id<>0'));
                break;

            case 'formacion:planes:plan:nuevo':
                $sTabla = 'plantilla_curso';
                $aBuscar = array('nombre');
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('plantilla_curso'));
                $oDb->construir_Where(array('plantilla_curso.activo=\'t\''));
                break;

            //case 'formacion:inscripcion:ver:fila:fila':
            case 'formacion:verAsistentesCurso:nuevo:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'alumnos';
                $aBuscar = array('usuario');
                $aCampos = array('alumnos.id', "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . gettext('sPCUsuario') . "\"",
                    "case when alumnos.inscrito=true then (case when alumnos.verificado=true then '" . gettext('sPCAlta') . "' 
                            else '" . gettext('sPCSolicitadaAlta') . "' end) else (case when alumnos.verificado=true then '" . gettext('sPCBaja') . "' else '" . gettext('sPCSolicitadaBaja') . "' end) end as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('alumnos', 'usuarios'));
                $oDb->construir_Where(array('alumnos.curso=' . $_SESSION['curso'], 'alumnos.usuario=usuarios.id'));
                break;

            case 'formacion:verProfesores:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'profesores';
                $aCampos = array('id', "case when interno then (select usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" .
                    gettext('sPCUsuario') . "\" from usuarios where (usuarios.id=usuario_interno)) else empresa end as \"" . gettext('sPCProfesor') . "\"",
                    "case when interno then '" . gettext('sPCInterno') . "' else '" . gettext('sPCExterno') . "' end as \"" . gettext('sPCInterno') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('profesores'));
                $oDb->construir_Where(array('curso=' . $_SESSION['curso'], 'profesores.activo=true'));
                break;

            case 'proveedores:listado:ver':
                $sTabla = 'proveedores';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono as \"" . gettext('sPCTelefono') . "\"");
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono')),
                    'campos' => array('nombre', 'telefono'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('proveedores'));
                $oDb->construir_Where(array('proveedores.activo=true'));
                break;

            case 'proveedores:incidencias:ver':
                $sTabla = 'incidencias';
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('incidencias'));
                $oDb->construir_Where(array('incidencias.activo=true'));
                break;

            case 'proveedores:contactos:ver':
                $sTabla = 'contactos_proveedores';
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono1')),
                    'campos' => array('nombre', 'telefono1'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono1 as \"" .
                    gettext('sPCTelefono1') . "\"", "telefono2 as \"" . gettext('sPCTelefono2') . "\"",
                    "CASE WHEN contactos_proveedores.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('contactos_proveedores'));
                break;

            case 'proveedores:productos:ver':
                $sTabla = 'productos';
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "case when homologado then 'Si' else 'No' end as \"" .
                    gettext('sPCHomologado') . "\"",
                    "to_char(fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "CASE WHEN productos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('productos'));
                break;

            case 'proveedores:phomologados:ver':
                $sTabla = 'proveedores';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono as \"" . gettext('sPCTelefono') . "\"");
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono')),
                    'campos' => array('nombre', 'telefono'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('proveedores'));
                $oDb->construir_Where(array('proveedores.activo=true', '0<(select count(pr1.id) from productos pr1 where (homologado=\'t\') and (proveedor=proveedores.id))'));
                break;

            case 'proveedores:productos:criterios:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['producto'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aCampos = array('criterios_homologacion.id', "criterios_homologacion.nombre as \"" . gettext('sPCNombre') . "\"", "criterios_homologacion.valor as \"" . gettext('sPCValor') . "\"");
                $sTabla = 'criterios_homologacion';
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('criterios_homologacion', 'productos'));
                $oDb->construir_Where(array('productos.id=' . $_SESSION['producto'],
                    'criterios_homologacion.id=any(productos.criterios)'));
                $oDb->construir_Group(array('criterios_homologacion.id', 'criterios_homologacion.nombre',
                    'criterios_homologacion.valor'));
                break;

            case 'proveedores:criterio:nuevo':
                $sTabla = 'criterios_homologacion';
                $aCampos = array('criterios_homologacion.id', "criterios_homologacion.nombre as \"" . gettext('sPCNombre') . "\"", "criterios_homologacion.valor as \"" . gettext('sPCValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('criterios_homologacion,productos'));
                $oDb->construir_Where(array('criterios_homologacion.activo=true',
                    'NOT(criterios_homologacion.id=any(productos.criterios)) OR (productos.criterios is null)=true)',
                    'productos.id=' . $_SESSION['producto']));
                break;

            case 'proveedores:productos:productoshistorico:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['producto'] = $_SESSION['pagina'][$aParametros['fila']];
                }

                $sTabla = 'historico_criterios';
                $aCampos = array('id', "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "valoracion_obtenida||' '||valoracion_homologacion as \"" . gettext('sPCValoracionObtenida') . "\"",
                    "case when (valoracion_obtenida>=valoracion_homologacion) then 'Si' else 'No' end as \"" .
                    gettext('sPCHomologado') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('historico_productos'));
                $oDb->construir_Where(array('producto=' . $_SESSION['producto']));
                break;

            case 'proveedores:productos:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['proveedor'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $sTabla = 'productos';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "case when homologado then 'Si' else 'No' end as \"" . gettext('sPCHomologado') . "\"",
                    "to_char(fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"", "CASE WHEN productos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('productos'));
                $oDb->construir_Where(array('proveedor=' . $_SESSION['proveedor']));
                break;

            case 'proveedores:incidencias:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['proveedor'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $sTabla = 'incidencias';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('incidencias'));
                $oDb->construir_Where(array('proveedor=' . $_SESSION['proveedor'], 'incidencias.activo=true'));
                break;

            case 'proveedores:contactos:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['proveedor'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono1')),
                    'campos' => array('nombre', 'telefono1'));
                $sTabla = 'contactos_proveedores';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono1 as \"" .
                    gettext('sPCTelefono1') . "\"", "telefono2 as \"" . gettext('sPCTelefono2') . "\"",
                    "CASE WHEN activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('contactos_proveedores'));
                $oDb->construir_Where(array('proveedor=' . $_SESSION['proveedor']));
                break;

            case 'proveedores:productoshomologados:ver:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['proveedor'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $sTabla = 'productos';
                $aCampos = array('pr1.id', "pr1.nombre as \"" . gettext('sPCNombre') . "\"",
                    "case when pr1.homologado then 'Si' else 'No' end as \"" . gettext('sPCHomologado') . "\"",
                    "to_char(pr1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "CASE WHEN productos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('productos pr1'));
                $oDb->construir_Where(array('pr1.proveedor=' . $_SESSION['proveedor']));
                break;

            case 'equipos:listado:ver':
                $sTabla = 'equipos';
                $aBuscar = array('nombres' => array(gettext('sMCNumeroControl'), gettext('sMCDescripcion')),
                    'campos' => array('numero', 'descripcion'));
                $aCampos = array('id', "numero as \"" . gettext('sPCNumeroControl') . "\"", "descripcion as \"" . gettext('sPCDescripcion') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('equipos'));
                $oDb->construir_Where(array('equipos.activo=\'t\''));
                break;

            case 'equipos:revision:ver':
                $sTabla = 'equipos';
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('equipos.id', "equipos.numero as \"" . gettext('sPCNumeroControl') . "\"", "descripcion as \"" . gettext('sPCDescripcion') . "\"",
                    "case when equipos.dias then to_char(max(mantenimientos.fecha_realiza)+(equipos.mantenimiento_cada*interval '1 day'),'DD/MM/YYYY')" .
                    "else to_char(max(mantenimientos.fecha_realiza)+(equipos.mantenimiento_cada*interval '1 month'),'DD/MM/YYYY') end as \"" . gettext('sPCProximaRev') . "\"");
                $oBaseDatos->iniciar_Consulta('SELECT');
                $aBuscar = array('nombres' => array(gettext('sPCDescripcion')),
                    'campos' => array('descripcion'));
                $oBaseDatos->construir_Campos($aCampos);
                $oBaseDatos->construir_Tablas(array('equipos', 'mantenimientos'));
                $oBaseDatos->construir_Where(array('equipos.activo=\'t\'', 'mantenimientos.equipo=equipos.id'));
                $oBaseDatos->construir_Group(array('equipos.id', 'equipos.numero', 'equipos.descripcion', 'equipos.mantenimiento_cada', 'equipos.dias'));
                break;

            case 'equipos:planmantenimientoid:nuevo':
            case 'equipos:planmantenimiento:ver:fila':
            case 'equipos:planmantenimiento:nuevo:fila':
                $sTabla = 'mantenimientos';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['equipo'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aFecha = null;
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('mantenimiento_cada', 'dias'));
                $oBaseDatos->construir_Tablas(array('equipos'));
                $oBaseDatos->construir_Where(array('id=\'' . $_SESSION['equipo'] . '\''));
                $oBaseDatos->consulta();
                if ($aIterador = $oBaseDatos->coger_Fila()) {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    if ($aIterador[1] == 't') {
                        $oBaseDatos->construir_Campos(array('to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' day\',\'dd\')',
                            'to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' day\',\'mm\')',
                            'to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' day\',\'YYYY\')'));
                    } else {
                        $oBaseDatos->construir_Campos(array('to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' month\',\'dd\')',
                            'to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' month\',\'mm\')',
                            'to_char(max(fecha_realiza)+interval\'' . $aIterador[0] . ' month\',\'YYYY\')'));
                    }
                    $oBaseDatos->construir_Tablas(array('mantenimientos'));
                    $oBaseDatos->construir_Where(array(('equipo=\'' . $_SESSION['equipo'] . '\''), ('tipo=\'preventivo\'')));
                    $oBaseDatos->consulta();
                    if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                        $sTexto = gettext('sMantProx') . $aIteradorInterno[0] . "/" . $aIteradorInterno[1] . "/" . $aIteradorInterno[2];
                    }
                }
                $aBuscar = array('nombres' => array(gettext('sPCPrevisto'), gettext('sPCRealizacion')),
                    'campos' => array('fecha_prevista', 'fecha_realiza'));
                $aCampos = array('id', "to_char(fecha_prevista, 'DD/MM/YYYY') as \"" . gettext('sPCPrevisto') . "\"",
                    "to_char(fecha_realiza, 'DD/MM/YYYY') as \"" . gettext('sPCRealizacion') . "\"", 'tipo');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('mantenimientos'));
                $oDb->construir_Where(array('equipo=' . $_SESSION['equipo']));
                break;

            case 'auditorias:areasauditoria:ver:fila':
                $sTabla = 'areas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['auditoria'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aCampos = array('areas.id', "areas.nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('areas', 'auditorias'));
                $oDb->construir_Where(array('auditorias.id=' . $_SESSION['auditoria'],
                    'areas.id=any(auditorias.areas)'));
                break;

            case 'auditorias:areaauditoria:nuevo':
                $sTabla = 'areas';
                $aCampos = array('areas.id', "areas.nombre as \"" . gettext('sPCNombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('areas', 'auditorias'));
                $oDb->construir_Where(array('auditorias.id=' . $_SESSION['auditoria'],
                    'areas.activo=\'t\'', '(NOT(areas.id=any(auditorias.areas)))', 'areas.id<>0'));
                break;

            case 'auditorias:equipoauditor:ver:fila':
                $sTabla = 'auditores';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['auditoria'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aCampos = array('id',
                    "case when usuario_interno is null then nombre else (select us.nombre||' '||us.primer_apellido||' '||us.segundo_apellido" .
                    " from usuarios us where (us.id=usuario_interno)) end as \"" . gettext('sPCAuditor') . "\"",
                    "case when usuario_interno is null then 'Externo' else 'Interno' end as \"" . gettext('sPCInterno') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('auditores'));
                $oDb->construir_Where(array('auditores.auditoria=' . $_SESSION['auditoria'], 'auditores.activo=\'t\''));
                break;

            case 'documentacion:normativa:ver':

                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('doc1.codigo', 'doc1.nombre'));
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" .
                    gettext('sPCNombre') . "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdNormativa, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdNormativa, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'editor:documentos:ver':
                {
                    $sTabla = 'documentos';
                    $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                        'campos' => array('doc1.codigo', 'doc1.nombre'));
                    $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" . gettext('sPCNombre') . "\"",
                        "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                        "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                        "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaRevision') . "\"",
                        "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') . "\"",
                        "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                        "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                        'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                        'estados_documento'));

                    $oDb->construir_Where(array('doc1.tipo_documento=' . iIdEditor, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                        'doc2.tipo_documento=' . iIdEditor, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                        'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                    break;
                }

        }
        //Procesamos los posibles where via textfields

        if (isset($aParametros['where'])) {

            if ($aParametros['where'] == "limpiar") {
                $aParametros['where'] = "limpiar";
            } else {
                foreach ($aCampos as $sKey => $sValor) {
                    $aIntermedio = explode(' as ', $sValor);
                    $iDummy = count($aIntermedio);
                    if ($iDummy != 0)
                        $aCampos[$aIntermedio[$iDummy - 1]] = $aIntermedio[0];
                }
                foreach ($aParametros['where'] as $sKey => $sValor) {
                    //$oDb->pon_Where($aCampos[$sKey]." LIKE '%".$sValor."%'");
                    $oDb->pon_Where($sKey . " LIKE '%" . $sValor . "%'");
                }
            }
        }

        //Procesamos los posibles where via desplegables
        $aSelect = array();
        if (isset($aParametros['select'])) {
            foreach ($aParametros['select'] as $sKey => $sValor) {
                $oDb->pon_Where($sKey . "=" . $sValor);
                $aSelect[$sKey] = $sValor;
            }
        }

        $aOrder = explode(' ', $aParametros['order']);
        $iLongitud = count($aOrder);
        $sOrder = $aOrder[0];

        if (is_array($aOrder)) {
            foreach ($aOrder as $iKey => $sValor) {
                if (($sValor != "DESC") && ($sValor != "ASC") && ($iKey != 0)) {
                    $sOrder .= " " . $sValor;
                }
            }
        }
        $sSentidoOrder = $aOrder[$iLongitud - 1];
        $oDb->construir_Order(array('"' . $sOrder . '" ' . $sSentidoOrder));


        $oPager = new generador_listados($sAccion, $oDb, $aParametros['pagina'], $aParametros['numLinks'],
            $aParametros['numPaginas'], $sOrder, $sSentidoOrder,
            $sTabla, $aParametros['where'], $aBuscar);


        //Ponemos los buscadores desplegables
        switch ($sAccion) {
            case 'mejora:listado:ver':
                $oPager->agrega_Desplegable(gettext('sEstadoMejora'), null,
                    array(-1 => gettext('sMejoraTodos'),
                        'true' => gettext('sMejoraCerrada'),
                        'false' => gettext('sMejoraAbierta')),
                    'cerrada');
                break;

            case 'documentacion:docvigor:ver':
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array($aSelect['activo'] => 'pepe',
                            -1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Especfico',
                            iIdProceso => 'Proceso',
                            iIdExterno => 'Externo',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'doc1.tipo_documento');
                } else {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array(-1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Especfico',
                            iIdProceso => 'Proceso',
                            iIdExterno => 'Externo',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'doc1.tipo_documento');
                }
                break;
            case 'documentacion:docborrador:ver':
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array($aSelect['activo'] => 'pepe',
                            -1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Especfico',
                            iIdProceso => 'Proceso',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'doc1.tipo_documento');
                } else {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array(-1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Especfico',
                            iIdProceso => 'Proceso',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'doc1.tipo_documento');
                }
                break;
            case 'proveedores:productos:ver':
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable('Homologado ', null,
                        array(-1 => 'Todos', 'true' => 'Homologados', 'false' => 'No homologados'),
                        'homologado');
                } else {
                    $oPager->agrega_Desplegable('Homologado ', null,
                        array(-1 => 'Todos', 'true' => 'Homologados', 'false' => 'No homologados'),
                        'homologado');
                }
                break;
        }

        //Ponemos los botones
        if (is_array($aParametros['botones'])) {
            foreach ($aParametros['botones'] as $aBoton) {
                $oPager->agrega_Boton($aBoton[0], $aBoton[1], $aBoton[2]);
            }
        }

        $oPager->agrega_Desplegable(gettext('sElemPag'),
            $sAccion, array($aParametros['numPaginas'], 10, 20, 30, 50));

        return ($oPager->muestra_Pagina());
    }


    function procesa_Listado_Nuevo($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        switch ($sAccion) {
            //DOCUMENTACION

            case 'administracion:menu:verbotones:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['menu'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'botones';
                    $aBuscar = array('nombres' => array(gettext('sPLAccion'), gettext('sPLTipo')),
                        'campos' => array('accion', 'tipo', 'permisos'));
                    $aCampos = array('botones.id', "botones.accion as \"" . gettext('sPLAccion') . "\"",
                        "tipo_botones.nombre as \"" . gettext('sPLTipo') . "\"",
                        "botones.permisos as \"" . gettext('sPNPermisos') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('botones', 'tipo_botones'));
                    $oDb->construir_Where(array("botones.menu='" . $_SESSION['menu'] . "\"", "tipo_botones.id=botones.tipo_botones"));
                    break;
                }
            case 'administracion:idiomas:ver:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['menu'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'menu_idiomas_nuevo';
                    $aCampos = array('menu_idiomas_nuevo.id',
                        "menu_idiomas_nuevo.valor as \"" . gettext('sPNValor') . "\"",
                        "idiomas.nombre as \"" . gettext('sPNIdioma') . "\"");
                    $aBuscar = array('valor', 'idioma');
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('menu_idiomas_nuevo', 'idiomas'));
                    $oDb->construir_Where(array("menu_idiomas_nuevo.menu='" . $_SESSION['menu'] . "\"", "menu_idiomas_nuevo.idioma_id=idiomas.id"));
                    break;
                }
            case 'administracion:idiomasboton:ver:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['boton'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'botones_idiomas';
                    $aCampos = array('botones_idiomas.id',
                        "botones_idiomas.valor as \"" . gettext('sPNValor') . "\"",
                        "idiomas.nombre as \"" . gettext('sPNIdioma') . "\"");
                    $aBuscar = array('valor', 'idioma');
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('botones_idiomas', 'idiomas'));
                    $oDb->construir_Where(array("botones_idiomas.boton='" . $_SESSION['menu'] . "\"", "botones_idiomas.idioma_id=idiomas.id"));
                    break;
                }
            case 'administracion:idiomas:nuevo':
                {
                    $sTabla = 'idiomas';
                    $aCampos = array('id', "nombre as \"" . gettext('sPNIdioma') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('idiomas'));
                    break;
                }
            case 'auditorias:horarioauditoria:ver:fila':
                {
                    if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                        $_SESSION['boton'] = $_SESSION['pagina'][$aParametros['fila']];
                    }
                    $sTabla = 'horario_auditoria';
                    $aBuscar = array('nombres' => array(gettext('sPLArea')),
                        'campos' => array('area'));
                    $aCampos = array('id', "horainicio as \"" . gettext('sPNHorainicio') . "\"",
                        "horafin as \"" . gettext('sPNHorafin') . "\"",
                        "requisito as \"" . gettext('sPNRequisitos') . "\"",
                        "area as \"" . gettext('sPNArea') . "\"", "auditor as \"auditor'");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('horario_auditoria'));
                    break;
                }
            case 'formacion:alumno:nuevo':
                {
                    $sTabla = 'usuarios';
                    $aCampos = array('usuarios.id', "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as alumno");
                    $aBuscar = array('alumno');
                    $oDb->iniciar_Consulta('SELECT DISTINCT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array("usuarios left outer join alumnos on usuarios.id=alumnos.usuario"));
                    $oDb->construir_Where(array("usuarios.id<>0"
                    ));
                    break;
                }
        }
        if ($aParametros['where'] == "limpiar") {
            $aParametros['where'] = "limpiar";
        } else {
            if (is_array($aCampos)) {
                foreach ($aCampos as $sKey => $sValor) {
                    $aIntermedio = explode(' as ', $sValor);
                    $iDummy = count($aIntermedio);
                    if ($iDummy != 0)
                        $aCampos[$aIntermedio[$iDummy - 1]] = $aIntermedio[0];
                }
            }
            if (is_array($aParametros['where'])) {
                foreach ($aCampos as $sKeys => $sValor) {
                    $aCamposComparacion[strtolower($sKeys)] = $sValor;
                }
                foreach ($aParametros['where'] as $sKey => $sValor) {
                    $oDb->pon_Where($aCamposComparacion['"' . $sKey . '"'] . " LIKE '%" . $sValor . "%'");
                }
            }

        }
        //Procesamos los posibles where via desplegables
        $aSelect = array();
        if ($aParametros['select'] != null) {
            foreach ($aParametros['select'] as $sKey => $sValor) {
                $oDb->pon_Where($sKey . "=" . $sValor);
                $aSelect[$sKey] = $sValor;
            }
        }

        $aOrder = explode(' ', $aParametros['order']);
        $iLongitud = count($aOrder);
        $sOrder = $aOrder[0];

        if (is_array($aOrder)) {
            foreach ($aOrder as $iKey => $sValor) {
                if (($sValor != "DESC") && ($sValor != "ASC") && ($iKey != 0)) {
                    $sOrder .= " " . $sValor;
                }
            }
        }
        $sSentidoOrder = $aOrder[$iLongitud - 1];
        $oDb->construir_Order(array('"' . $sOrder . '" ' . $sSentidoOrder));
        $oPager = new generador_listados($sAccion, $oDb, $aParametros['pagina'], $aParametros['numLinks'],
            $aParametros['numPaginas'], $sOrder, $sSentidoOrder,
            $sTabla, $aParametros['where'], $aBuscar);

        //Ponemos los botones
        if (is_array($aParametros['botones'])) {
            foreach ($aParametros['botones'] as $aBoton) {
                $oPager->agrega_Boton($aBoton[0], $aBoton[1], $aBoton[2]);
            }
        }
        $oPager->agrega_Desplegable(gettext('sElemPag'), $sAccion, array($aParametros['numPaginas'], 10, 20, 30, 50));
        return ($oPager->muestra_Pagina());
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     * @throws \PEAR_Error
     */
    function procesa_Listado_Adm($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        switch ($sAccion) {
            case 'administracion:mensajes:ver':
                //Aqui solo mostramos los mensajes generales (los dirigidos al id=0)
                $sTabla = 'mensajes';
                //$aBuscar=array('titulo','enviado');
                $aBuscar = array('nombres' => array(gettext('sPLTitulo'), gettext('sPLEnviado')),
                    'campos' => array('titulo', 'fecha'));
                $aCampos = array('id', "titulo as \"" . gettext('sPATitulo') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPAEnviado') . "\"",
                    "to_char(fecha, 'hh24:mi') as \"" . gettext('sPAHora') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla));
                $oDb->construir_Where(array('(destinatario=0)', '(mensajes.activo=\'t\')'));
                break;

            case 'administracion:menus:nuevo':
                $sTabla = 'menu_nuevo';
                //$aBuscar= array('valor','accion','padre');
                $aBuscar = array('nombres' => array(gettext('sPLValor'), gettext('sPLAccion'), gettext('sPLPadre')),
                    'campos' => array('menu_idiomas_nuevo.valor', 'accion', 'padre'));
                $aCampos = array('menu_nuevo.id', "menu_idiomas_nuevo.valor as \"" . gettext('sPAValor') .
                    "\"", "accion", "padre as \"" . gettext('sPAPadre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array("menu_nuevo left outer join (menu_idiomas_nuevo inner join idiomas" .
                    " on menu_idiomas_nuevo.idioma_id=idiomas.id and idiomas.nombre='" .
                    $_SESSION['idioma'] . "') on menu_nuevo.id=menu_idiomas_nuevo.menu"));
                break;

            case 'administracion:tareas:ver':
                $sTabla = 'tareas';
                $aBuscar = array('nombres' => array(gettext('sPLOrigen'), gettext('sPLDestino'), gettext('sPLAccion'), gettext('sPLDocumento')),
                    'campos' => array('us1.nombre', 'us2.nombre', 'tt.nombre', 'doc.nombre'));

                $aCampos = array('ta.id', "us1.nombre as \"" . gettext('sPAOrigen') . "\"", "us2.nombre as \"" . gettext('sPADestino') . "\"",
                    "tt.nombre as \"" . gettext('sPAAccion') . "\"", "doc.codigo||' '||doc.nombre as \"" . gettext('sPADocumento') . "\"",
                    "CASE WHEN ta.activo=true THEN '" . gettext('sPAAlta') . "' ELSE '" . gettext('sPABaja') . "' END as \"" . gettext('sPAAlta') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us1', 'usuarios us2', 'documentos doc'));
                $oDb->construir_Where(array("(ta.usuario_origen=us1.id)", "ta.usuario_destino=us2.id",
                    "(tt.id=ta.accion)", "ta.documento=doc.id"));
                break;

            case 'administracion:modulos:nuevo':
                $sTabla = 'menu_nuevo';
                $aCampos = array('menu_nuevo.id', 'menu_idiomas_nuevo.valor as "' . gettext('sMCModulo') . '"');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('menu_nuevo', 'menu_idiomas_nuevo'));
                $oDb->construir_Where(array("menu_idiomas_nuevo.menu=menu_nuevo.id", "menu_idiomas_nuevo.idioma_id=" . $_SESSION['idiomaid'],
                    "menu_nuevo.padre=0"));
                break;

            case 'administracion:hospitales:nuevo':
                $sTabla = 'hospitales';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', 'nombre as "' . gettext('sPANombre') . '"');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('hospitales'));
                $oDb->construir_where(array('id<>0', 'hospitales.activo=\'t\''));
                break;

            case 'administracion:centros:nuevo':
                {
                    $sTabla = 'areas';
                    $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                        'campos' => array('nombre'));
                    $aCampos = array('id', 'nombre as "' . gettext('sPANombre') . '"',
                        "CASE WHEN activo=true THEN '" . gettext('sPAAlta') . "' ELSE '" . gettext('sPABaja') .
                        "' END as \"" . gettext('sPAAlta') . "\"");
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('areas'));
                    $oDb->construir_where(array('id<>0'));
                    break;
                }

            case 'administracion:perfiles:ver':
                $sTabla = 'perfiles';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));

                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('perfiles'));
                $oDb->construir_where(array('id<>0', 'perfiles.activo=\'t\''));
                break;

            case 'administracion:usuarios:ver':
                $sTabla = 'usuarios';
                $aBuscar = array('nombres' => array(gettext('sPLPrimerApe')),
                    'campos' => array('primer_apellido'));
                $aCampos = array('id', "primer_apellido as \"" . gettext('sPAPrimerApe') . "\"",
                    "segundo_apellido as \"" . gettext('sPASegundoApe') . "\"", "nombre as \"" . gettext('sPANombre') . "\"",
                    'ultimo_acceso', 'numero_accesos');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios'));
                $oDb->construir_where(array('id<>0'));
                break;

            case 'administracion:aspectos:formula':
                $sTabla = 'formula_aspectos';
                $aCampos = array("id", "formula as gettext('sMCFormula')", "case when id=1 then 'Aspecto Normal' else 'Aspecto Emergencia' end as \"tipo'");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('formula_aspectos'));
                break;

            case 'administracion:aspectos:frecuencia':
                $sTabla = 'tipo_frecuencia';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array("tipo_frecuencia.id", "tipo_frecuencia_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "tipo_frecuencia.valor as \"" . gettext('sPAValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_frecuencia left outer join tipo_frecuencia_idiomas on tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia'));
                $oDb->construir_Where(array("tipo_frecuencia_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_frecuencia.nombre is NULL"));
                break;

            case 'administracion:frecuenciaidioma:idioma:fila':
                $sTabla = 'tipo_frecuencia_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['frecuencia'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_frecuencia_idiomas.id", "tipo_frecuencia_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_frecuencia_idiomas join idiomas on tipo_frecuencia_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_frecuencia_idiomas.frecuencia=" . $_SESSION['frecuencia']));
                break;

            case 'administracion:aspectos:gravedad':
                $sTabla = 'tipo_gravedad';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array("tipo_gravedad.id", "tipo_gravedad_idiomas.valor as \"" . gettext('sPANombre') . "\"",
                    "tipo_gravedad.valor as \"" . gettext('sPAValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_gravedad left outer join tipo_gravedad_idiomas on tipo_gravedad.id=tipo_gravedad_idiomas.gravedad'));
                $oDb->construir_Where(array("tipo_gravedad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_gravedad.nombre is NULL"));
                break;

            case 'administracion:gravedadidioma:idioma:fila':
                $sTabla = 'tipo_gravedad_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['gravedad'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_gravedad_idiomas.id", "tipo_gravedad_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_gravedad_idiomas join idiomas on tipo_gravedad_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_gravedad_idiomas.gravedad=" . $_SESSION['gravedad']));
                break;

            case 'administracion:aspectos:magnitud':
                $sTabla = 'tipo_magnitud';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array("tipo_magnitud.id", "tipo_magnitud_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "tipo_magnitud.valor as \"" . gettext('sPAValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_magnitud left outer join tipo_magnitud_idiomas on tipo_magnitud.id=tipo_magnitud_idiomas.magnitud'));
                $oDb->construir_Where(array("tipo_magnitud_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_magnitud.nombre is NULL"));
                break;

            case 'administracion:magnitudidioma:idioma:fila':
                $sTabla = 'tipo_magnitud_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['magnitud'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_magnitud_idiomas.id", "tipo_magnitud_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_magnitud_idiomas join idiomas on tipo_magnitud_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_magnitud_idiomas.magnitud=" . $_SESSION['magnitud']));
                break;


            case 'administracion:aspectos:severidad':
                $sTabla = 'tipo_severidad';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array("tipo_severidad.id", "tipo_severidad_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "tipo_severidad.valor as \"" . gettext('sPAValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_severidad left outer join tipo_severidad_idiomas on tipo_severidad.id=tipo_severidad_idiomas.severidad'));
                $oDb->construir_Where(array("tipo_severidad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_severidad.nombre is NULL"));
                break;

            case 'administracion:severidadidioma:idioma:fila':
                $sTabla = 'tipo_severidad_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['severidad'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_severidad_idiomas.id", "tipo_severidad_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_severidad_idiomas join idiomas on tipo_severidad_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_severidad_idiomas.severidad=" . $_SESSION['severidad']));
                break;

            case 'administracion:aspectos:probabilidad':
                $sTabla = 'tipo_probabilidad';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array("tipo_probabilidad.id", "tipo_probabilidad_idiomas.valor as \"" . gettext('sPANombre') .
                    "\"", "tipo_probabilidad.valor as \"" . gettext('sPAValor') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_probabilidad left outer join tipo_probabilidad_idiomas on tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad'));
                $oDb->construir_Where(array("tipo_probabilidad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_probabilidad.nombre is NULL"));
                break;

            case 'administracion:probabilidadidioma:idioma:fila':
                $sTabla = 'tipo_probabilidad_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['probabilidad'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_probabilidad_idiomas.id", "tipo_probabilidad_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_probabilidad_idiomas join idiomas on tipo_probabilidad_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_probabilidad_idiomas.probabilidad=" . $_SESSION['probabilidad']));
                break;

            case 'administracion:documentossg:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLTitulo')),
                    'campos' => array('codigo', 'documentos.nombre'));
                $aCampos = array('documentos.id', "codigo as \"" . gettext('sPACodigo') . "\"", "documentos.nombre as \"" .
                    gettext('sPATitulo') . "\"", "revision as \"" . gettext('sPARevision') . "\"", "CASE WHEN documentos.activo=true THEN '" .
                    gettext('sPAAlta') . "' ELSE '" . gettext('sPABaja') . "' END as \"" . gettext('sPAAlta') . "\"",
                    "tipo_documento_idiomas.valor as \"" . gettext('sPATipo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos', 'tipo_documento', 'tipo_documento_idiomas'));
                $oDb->construir_Where(array('tipo_documento.id=documentos.tipo_documento',
                    'tipo_documento.id=tipo_documento_idiomas.tipodoc',
                    'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
                break;

            case 'administracion:normativa:ver':
                $sTabla = 'documentos';
                $aBuscar = array('nombres' => array(gettext('sPLCodigo'), gettext('sPLNombre')),
                    'campos' => array('documentos.codigo', 'documentos.nombre'));
                $aCampos = array('documentos.id', "documentos.codigo as \"" . gettext('sPLCodigo') .
                    "\"", "documentos.nombre as \"" . gettext('sPLNombre') . "\"", "documentos.revision as \"" .
                    gettext('sPARevision') . "\"", "CASE WHEN documentos.activo=true THEN '" .
                    gettext('sPAAlta') . "' ELSE '" . gettext('sPABaja') . "' END as \"" . gettext('sPAAlta') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('documentos'));
                $oDb->construir_Where(array('documentos.tipo_documento=' . iIdExterno));
                break;

            case 'administracion:registros:ver':
                $sTabla = 'registros';
                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('registros'));
                break;

            case 'administracion:tipomejora:ver':
                $sTabla = 'tipo_acciones';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('tipo_acciones.nombre'));
                $aCampos = array('tipo_acciones.id', "tipo_acciones_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "CASE WHEN tipo_acciones.activo=true THEN '" .
                    gettext('sPAAlta') . "' ELSE '" . gettext('sPABaja') . "' END as \"" . gettext('sPAAlta') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_acciones left outer join tipo_acciones_idiomas on tipo_acciones.id=tipo_acciones_idiomas.mejora'));
                $oDb->construir_Where(array("tipo_acciones_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_acciones.nombre is NULL"));
                break;


            case 'administracion:mejoraidioma:idioma:fila':
                $sTabla = 'tipo_acciones_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['mejora'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_acciones_idiomas.id", "tipo_acciones_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_acciones_idiomas join idiomas on tipo_acciones_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_acciones_idiomas.mejora=" . $_SESSION['mejora']));
                break;


            case 'administracion:tiposareas:ver':
                $sTabla = 'tipo_area_aplicacion';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('tipo_area_aplicacion.nombre'));
                $aCampos = array('tipo_area_aplicacion.id', "tipo_area_aplicacion.nombre as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_area_aplicacion'));
                break;

            case 'administracion:tiposamb:ver':
                $sTabla = 'tipo_ambito_aplicacion';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('tipo_ambito_aplicacion.nombre'));
                $aCampos = array('tipo_ambito_aplicacion.id', "tipo_ambito_aplicacion_idiomas.valor as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_ambito_aplicacion left outer join tipo_ambito_aplicacion_idiomas".
            " on tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb'));
                $oDb->construir_Where(array('tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid'] . " OR tipo_ambito_aplicacion.nombre is NULL"));
                break;

            case 'administracion:tipoambidioma:idioma:fila':
                $sTabla = 'tipo_tipoamb_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['tipoamb'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_ambito_aplicacion_idiomas.id", "tipo_ambito_aplicacion_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_ambito_aplicacion_idiomas join idiomas on tipo_ambito_aplicacion_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_ambito_aplicacion_idiomas.tipoamb=" . $_SESSION['tipoamb']));
                break;

            case 'administracion:menus':
                $sTabla = 'menu_nuevo';
                $aBuscar = array('valor', 'accion', 'padre');
                $aCampos = array('menu_nuevo.id', "menu_idiomas_nuevo.valor as \"" . gettext('sPAValor') . "\"", "accion", "padre as \"" . gettext('sPAPadre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array("menu_nuevo left outer join (menu_idiomas_nuevo inner join idiomas on menu_idiomas_nuevo.idioma_id=idiomas.id and idiomas.nombre='" . $_SESSION['idioma'] . "') on menu_nuevo.id=menu_idiomas_nuevo.menu"));
                break;

            case 'administracion:legaplicable:ver':
                $sTabla = 'legislacion_aplicable';
                $aBuscar = array('nombres' => array(gettext('sPLTitulo')),
                    'campos' => array('legislacion_aplicable.nombre'));
                $aCampos = array('legislacion_aplicable.id', "legislacion_aplicable.nombre as \"" . gettext('sPMTitulo') . "\"",
                    "tipo_area_aplicacion.nombre as \"" . gettext('sPMAreaIncidencia') . "\"",
                    "tipo_ambito_aplicacion_idiomas.valor as \"" . gettext('sPMAmbitoApli') . "\"",
                    "case when legislacion_aplicable.verifica='t' then '" . gettext('sPMCumple') .
                    "' when legislacion_aplicable.verifica='f' then '" . gettext('sPMNoCumple') .
                    "' ELSE '" . gettext('sPMNuncaComprobado') . "' END as \"" . gettext('sPMVerifica') . "\"",
                    "CASE WHEN legislacion_aplicable.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('legislacion_aplicable', 'tipo_area_aplicacion', 'tipo_ambito_aplicacion', 'tipo_ambito_aplicacion_idiomas'));
                $oDb->construir_Where(array('legislacion_aplicable.tipo_area=tipo_area_aplicacion.id',
                    'legislacion_aplicable.tipo_ambito=tipo_ambito_aplicacion.id',
                    'tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb', 'tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid']
                ));
                break;


            case 'administracion:tiposimp:ver':
                $sTabla = 'tipo_impactos';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('tipo_impactos.nombre'));
                $aCampos = array('tipo_impactos.id', "tipo_impactos_idiomas.valor as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_impactos left outer join tipo_impactos_idiomas on tipo_impactos.id=tipo_impactos_idiomas.impactos'));
                $oDb->construir_Where(array('tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'] .
                    " OR tipo_impactos.nombre is NULL", 'tipo_impactos.activo=\'t\''));
                break;

            case 'administracion:tipoimpidioma:idioma:fila':
                $sTabla = 'tipo_impactos_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['tipoimp'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_impactos_idiomas.id", "tipo_impactos_idiomas.valor as \"" .
                    gettext('sPANombre') . "\"", "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_impactos_idiomas join idiomas on tipo_impactos_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_impactos_idiomas.impactos=" . $_SESSION['tipoimp']));
                break;

            case 'administracion:tipo_cursos:ver':
                $sTabla = 'tipos_cursos';
                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipos_cursos'));
                break;

            case 'administracion:pcalidad':
            case 'documentacion:politica:ver':
                $sTabla = 'documentos';
                $aCampos = array('doc1.id', "doc1.codigo as \"" . gettext('sPCCodigo') . "\"", "doc1.nombre as \"" .
                    gettext('sPCNombre') . "\"", "doc1.revision as \"" . gettext('sPCRevision') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPCRevisadoPor') . "\"",
                    "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPCAprobadoPor') .
                    "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"" . gettext('sPAFechaAprov') . "\"",
                    "estados_documento.nombre as \"" . gettext('sPCEstado') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                    'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                    'estados_documento'));

                $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPolitica, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                    'doc2.tipo_documento=' . iIdPolitica, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                    'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
                break;

            case 'documentacion:objetivos:ver':
                /*    $sTabla= 'documentos';
            $aCampos=array('id','codigo','nombre','revision');
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos($aCampos);
            $oDb->construir_Tablas(array('documentos'));
            $oDb->construir_Where(array('tipo_documento='.iIdObjetivos,'estado<>'.iHistorico));
            break;

        case 'administracion:objetivos':*/
                $aCampos = array('og.id',
                    "og.nombre as \"" . gettext('sPANombre') . "\"",
                    "case when og.estado=2 then '" . gettext('sPABorrador') . "' when og.estado=4 then '" . gettext('sPARevisado') .
                    "' else '" . gettext('sPAVigor') . "' end as \"" . gettext('sPAEstado') . "\"",
                    "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . gettext('sPARevisadoPor') . "\"",
                    "og.fecha_revision::date as \"" . gettext('sPAFechaRevision') . "\"",
                    "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . gettext('sPAAprobadoPor') . "\"",
                    "og.fecha_aprobacion::date as \"" . gettext('sPAFechaAprov') . "\"", "og.version as \"" . gettext('sPAVersion') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('usuarios u1 right outer join objetivos_globales og1 on u1.id=og1.revisadopor',
                    'usuarios u2 right outer join objetivos_globales og on u2.id=og.aprobadopor'));
                $oDb->construir_Where(array('og.activo=\'t\'', 'og1.id=og.id'));
                break;

            case 'administracion:preguntasleg:nuevo:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['admlegislacion'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'preguntas_legislacion_aplicable';
                $aCampos = array('id', "pregunta as \"" . gettext('sPAPregunta') . "\"",
                    "case when valor_deseado then 'Si' else 'No' end as \"" . gettext('sPAValorDeseado') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('preguntas_legislacion_aplicable'));
                $oDb->construir_Where(array('legislacion_aplicable=' . $_SESSION['admlegislacion'], 'preguntas_legislacion_aplicable.activo=true'));
                break;

            case 'administracion:tipodocumento:nuevo':
                $sTabla = 'tipo_documento,idiomas';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('tipo_documentos.nombre'));
                $aCampos = array('tipo_documento.id', "tipo_documento_idiomas.valor as \"" . gettext('sPANombre') . "\"", "tipo as \"" . gettext('sPATipo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_documento left outer join tipo_documento_idiomas on tipo_documento.id=tipo_documento_idiomas.tipodoc'));
                $oDb->construir_Where(array("tipo_documento_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_documento.nombre is NULL"));
                break;


            case 'administracion:tipodocidioma:idioma:fila':
                $sTabla = 'tipo_documento_idiomas';
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                    $_SESSION['tipodoc'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('valor'));
                $aCampos = array("tipo_documento_idiomas.id", "tipo_documento_idiomas.valor as \"" . gettext('sPANombre') . "\"",
                    "idiomas.nombre as \"" . gettext('sPANombreidioma') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('tipo_documento_idiomas join idiomas on tipo_documento_idiomas.idioma_id=idiomas.id'));
                $oDb->construir_Where(array("tipo_documento_idiomas.tipodoc=" . $_SESSION['tipodoc']));
                break;

            case 'administracion:ayuda:nuevo':
                $sTabla = 'division_ayuda';
                $aBuscar = array('nombres' => array(gettext('sPLMenu'), gettext('sPLBoton')),
                    'campos' => array('menu', 'boton'));
                $aCampos = array("division_ayuda.id as \"id'", "menu as \"" . gettext('sAMenu') . "\"",
                    "boton as \"" . gettext('sABoton') . "\"", "idiomas.nombre as \"" . gettext('sAIdioma') . "\"", "texto as \"" . gettext('sATexto') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('division_ayuda join idiomas on division_ayuda.idioma=idiomas.id'));

                break;

            case 'administracion:clientes:ver':
                $sTabla = 'clientes';
                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"", "telefono as \"" . gettext('sPATelefono') . "\"",
                    "contacto as \"" . gettext('sPAContacto') . "\"", "CASE WHEN clientes.activo=true THEN '" . gettext('sPAAlta') .
                    "' ELSE '" . gettext('sPABaja') . "' END as \"" . gettext('sPAAlta') . "\"");
                $aBuscar = array('nombre');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('clientes'));
                break;

            case 'administracion:criterios:ver':
                $sTabla = 'criterios_homologacion';
                $aBuscar = array('nombre');
                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"", "valor as \"" . gettext('sPAValor') . "\"",
                    "CASE WHEN criterios_homologacion.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPAActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('criterios_homologacion'));
                break;

            case 'administracion:proveedores:ver':
                $sTabla = 'proveedores';
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono as \"" . gettext('sPCTelefono') . "\"",
                    "CASE WHEN proveedores.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono')),
                    'campos' => array('nombre', 'telefono'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('proveedores'));
                break;

            case 'administracion:proveedores:incidencia':
                $sTabla = 'incidencias';
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "CASE WHEN incidencias.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('incidencias'));
                break;

            case 'administracion:proveedores:contacto':
                $sTabla = 'contactos_proveedores';
                $aBuscar = array('nombres' => array(gettext('sPCNombre'), gettext('sPCTelefono1')),
                    'campos' => array('nombre', 'telefono1'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "telefono1 as \"" . gettext('sPCTelefono1') . "\"",
                    "telefono2 as \"" . gettext('sPCTelefono2') . "\"",
                    "CASE WHEN contactos_proveedores.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('contactos_proveedores'));
                break;

            case 'administracion:proveedores:producto':
                $sTabla = 'productos';
                $aBuscar = array('nombres' => array(gettext('sPCNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"",
                    "case when homologado then 'Si' else 'No' end as \"" . gettext('sPCHomologado') . "\"",
                    "to_char(fecha_revision, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "CASE WHEN productos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('productos'));
                break;

            case 'administracion:equipos:ver':
                $sTabla = 'equipos';
                $aBuscar = array('nombres' => array(gettext('sMCNumeroControl'), gettext('sMCDescripcion')),
                    'campos' => array('numero', 'descripcion'));
                $aCampos = array('id', "numero as \"" . gettext('sPCNumeroControl') . "\"", "descripcion as \"" . gettext('sPCDescripcion') . "\"",
                    "CASE WHEN equipos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('equipos'));
                break;

            case 'administracion:auditoriaanual:ver':
                $sTabla = 'programa_auditoria';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPCNombre') . "\"", "CASE WHEN vigente=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCVigente') . "\"", "revision as \"" . gettext('sPCRevision') . "\"",
                    "CASE WHEN programa_auditoria.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('programa_auditoria'));
                //$oDb->construir_Where(array('programa_auditoria.activo=\'t\''));
                break;

            case 'administracion:auditoriavigor:ver':
                $sTabla = 'auditorias';
                $aBuscar = array('nombres' => array(gettext('sPLDescripcion')),
                    'campos' => array('descripcion'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('id'));
                $oDb->construir_Tablas(array('programa_auditoria'));
                $oDb->construir_Where(array('programa_auditoria.vigente=\'t\''));
                $oDb->consulta();
                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['progauditoria'] = $aIterador[0];
                }
                $aCampos = array('auditorias.id', "descripcion as \"" . gettext('sPCDescripcion') . "\"", "tipo_estado_auditoria.nombre as \"" . gettext('sPCEstado') . "\"",
                    "to_char(fecha, 'DD/MM/YYYY') as \"" . gettext('sPCFecha') . "\"",
                    "case when (fecha_realiza=null) then '-' else to_char(fecha_realiza, 'DD/MM/YYYY') end as \"" . gettext('sPCRealizacion') . "\"",
                    "CASE WHEN auditorias.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('auditorias', 'tipo_estado_auditoria', 'programa_auditoria'));
                $oDb->construir_Where(array('auditorias.programa=programa_auditoria.id', 'auditorias.estado=tipo_estado_auditoria.id',
                    'programa_auditoria.vigente=\'t\''));
                break;

            case 'administracion:indicadoresobjetivo:ver':
                $sTabla = 'objetivos';
                $aBuscar = array('nombres' => array(gettext('sPLNombre')),
                    'campos' => array('nombre'));
                $aCampos = array('id', "nombre as \"" . gettext('sPANombre') . "\"",
                    "CASE WHEN objetivos.activo=true THEN 'Si' ELSE 'No' END as \"" . gettext('sPCActivo') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);

                $oDb->construir_Tablas(array('objetivos'));
                //$oDb->construir_Where(array('og1.id=og.id'));

                break;

        }
        //Procesamos los posibles where via textfields

        //@TODO para que era esto?interfiere con los select
        if ($aParametros['where'] == null) {
            $oDb->construir_Where($_SESSION['where']);
        } else
            if ($aParametros['where'] == "limpiar") {
                $aParametros['where'] = "limpiar";
            } else {
                if (is_array($aCampos)) {
                    foreach ($aCampos as $sKey => $sValor) {
                        $aIntermedio = explode(' as ', $sValor);
                        $iDummy = count($aIntermedio);
                        if ($iDummy != 0)
                            $aCampos[$aIntermedio[$iDummy - 1]] = $aIntermedio[0];
                    }
                }
                foreach ($aParametros['where'] as $sKey => $sValor) {
                    //$oDb->pon_Where($aCampos[$sKey]." LIKE '%".$sValor."%'");
                    $oDb->pon_Where($sKey . " LIKE '%" . $sValor . "%'");
                }

            }
        //Procesamos los posibles where via desplegables
        $aSelect = array();
        if ($aParametros['select'] != null) {
            foreach ($aParametros['select'] as $sKey => $sValor) {
                $oDb->pon_Where($sKey . "=" . $sValor);
                $aSelect[$sKey] = $sValor;
            }
        }

        $aOrder = explode(' ', $aParametros['order']);
        $iLongitud = count($aOrder);
        $sOrder = $aOrder[0];

        if (is_array($aOrder)) {
            foreach ($aOrder as $iKey => $sValor) {
                if (($sValor != "DESC") && ($sValor != "ASC") && ($iKey != 0)) {
                    $sOrder .= " " . $sValor;
                }
            }
        }
        $sSentidoOrder = $aOrder[$iLongitud - 1];
        $oDb->construir_Order(array('"' . $sOrder . '" ' . $sSentidoOrder));
        $oPager = new generador_listados($sAccion, $oDb, $aParametros['pagina'], $aParametros['numLinks'],
            $aParametros['numPaginas'], $sOrder, $sSentidoOrder,
            $sTabla, $aParametros['where'], $aBuscar);

        //Aqui ponemos los desplegables para el listado

        switch ($sAccion) {
            /*case 'administracion:tareas':
            $aOpciones = array (-1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja'));
            if (isset($aSelect['activo']))
            {
                $oPager->agrega_Desplegable(gettext('sAlta')Baja,null,array($aSelect['activo'] => gettext('sAnterior') ,-1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja')),'activo');
            }
            else
            {
                $oPager->agrega_Desplegable(gettext('sAlta')Baja,null,array(-1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja')),'activo');
            }
            break;*/

            case 'administracion:areas':
                $aOpciones = array(-1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja'));
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable(gettext('sAltaBaja'), null,
                        array($aSelect['activo'] => gettext('sAnterior'), -1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja')), 'activo');
                } else {
                    $oPager->agrega_Desplegable(gettext('sAltaBaja'), null,
                        array(-1 => gettext('sTodos'), 'true' => gettext('sAlta'), 'false' => gettext('sBaja')), 'activo');
                }
                break;

            case 'administracion:documentossg:ver':
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable(gettext('sAltaBaja'), null,
                        array($aSelect['activo'] => gettext('sAnterior'),
                            -1 => gettext('sTodos'),
                            'true' => gettext('sAlta'),
                            'false' => gettext('sBaja')),
                        'activo');
                } else {
                    $oPager->agrega_Desplegable(gettext('sAltaBaja'), null,
                        array(-1 => gettext('sTodos'),
                            'true' => gettext('sAlta'),
                            'false' => gettext('sBaja')),
                        'activo');
                }
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array(-1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Específico',
                            iIdProceso => 'Proceso',
                            iIdExterno => 'Externo',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'tipo_documento');
                } else {
                    $oPager->agrega_Desplegable('Tipo ', null,
                        array(-1 => 'Todos',
                            iIdPg => 'Procedimiento General',
                            iIdPe => 'Procedimiento Específico',
                            iIdProceso => 'Proceso',
                            iIdExterno => 'Externo',
                            iIdFichaMa => 'Ficha Requisitos Legales'),
                        'tipo_documento');
                }
                break;

        }
        //Ponemos los botones
        if (is_array($aParametros['botones'])) {
            foreach ($aParametros['botones'] as $aBoton) {
                $oPager->agrega_Boton($aBoton[0], $aBoton[1], $aBoton[2]);
            }
        }
        $oPager->agrega_Desplegable(gettext('sElemPag'), $sAccion, array($aParametros['numPaginas'], 10, 20, 30, 50));
        return ($oPager->muestra_Pagina());
    }


    /**
     * Esta funcion devuelve el documento, le pasamos siempre o la fila del listado o el id directamente
     * Por si no vemos el documento desde un listado
     * @param $sAccion
     * @param $aParametros
     * @return string
     * @throws \PEAR_Error
     */

    function procesa_Listado_Medio($sAccion, $aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $config = new Config();

        switch ($sAccion) {
            //DOCUMENTACION
            case 'aambientales:revision:ver':
                //Sacamos la formula
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('formula'));
                $oDb->construir_Tablas(array('formula_aspectos'));
                $oDb->construir_Where(array('id=1'));
                $oDb->consulta();
                $aDevolver = $oDb->coger_Fila();
                $sFormulaMatrizAmbientales = $aDevolver[0];

                if ($_SESSION['areasactivadas']) {
                    $aBuscar = array('nombres' => array(gettext('sPLAspecto'), gettext('sPLImpacto')),
                        'campos' => array('aspectos.nombre', 'tipo_impactos_idiomas.valor'));
                    $sTabla = 'aspectos';

                    $aCampos = array('aspectos.id', "tipo_aspectos.nombre as \"" . gettext('sPMTipo') . "\"",
                        "aspectos.nombre as \"" . gettext('sPMAspecto') . "\"",
                        "tipo_impactos_idiomas.valor as \"" . gettext('sPMImpacto') . "\"",
                        "areas.nombre as \"" . gettext('sPMArea') . "\"",
                        "tipo_magnitud_idiomas.valor as \"" . gettext('sFMagnitud') . "\"",
                        "tipo_gravedad_idiomas.valor as \"" . gettext('sFGravedad') . "\"",
                        "tipo_frecuencia_idiomas.valor as \"" . gettext('sFFrecuencia') . "\"", 'pp.significancia',
                        "case when pp.significancia<15 then '" . gettext('sPMNoSignificativo') .
                        "' else '" . gettext('sPMSignificativo') . "' end as \"" . gettext('sPMValoracion') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('aspectos', 'tipo_impactos', 'tipo_aspectos', 'areas',
                        '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_frecuencia, tipo_magnitud, tipo_gravedad ' .
                        'where aspectos.frecuencia=tipo_frecuencia.id AND aspectos.magnitud=tipo_magnitud.id AND aspectos.gravedad=tipo_gravedad.id)as pp',
                        'tipo_frecuencia', 'tipo_magnitud', 'tipo_gravedad', 'tipo_impactos_idiomas', 'tipo_frecuencia_idiomas', 'tipo_gravedad_idiomas', 'tipo_magnitud_idiomas'));
                    $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id',
                        'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                        'aspectos.tipo_aspecto<>3',
                        'aspectos.area_id=areas.id',
                        'aspectos.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                        'aspectos.magnitud=tipo_magnitud.id', 'aspectos.frecuencia=tipo_frecuencia.id',
                        'aspectos.gravedad=tipo_gravedad.id',
                        'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia', 'tipo_frecuencia_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_gravedad.id=tipo_gravedad_idiomas.gravedad', 'tipo_gravedad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_magnitud.id=tipo_magnitud_idiomas.magnitud', 'tipo_magnitud_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                } else {
                    $aBuscar = array('nombres' => array(gettext('sPLArea'), gettext('sPLAspecto'), gettext('sPLImpacto')),
                        'campos' => array('area', 'aspectos.nombre', 'tipo_impactos_idiomas.valor'));
                    $sTabla = 'aspectos';

                    $aCampos = array('aspectos.id', "tipo_aspectos.nombre as \"" . gettext('sPMTipo') . "\"",
                        "aspectos.nombre as \"" . gettext('sPMAspecto') . "\"",
                        "tipo_impactos_idiomas.valor as \"" . gettext('sPMImpacto') . "\"",
                        "area as \"" . gettext('sPMArea') . "\"",
                        "tipo_magnitud_idiomas.valor as \"" . gettext('sFMagnitud') . "\"",
                        "tipo_gravedad_idiomas.valor as \"" . gettext('sFGravedad') . "\"",
                        "tipo_frecuencia_idiomas.valor as \"" . gettext('sFFrecuencia') . "\"", 'pp.significancia',
                        "case when pp.significancia<15 then '" . gettext('sPMNoSignificativo') . "' else '" .
                        gettext('sPMSignificativo') . "' end as \"" . gettext('sPMValoracion') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('aspectos', 'tipo_impactos', 'tipo_aspectos',
                        '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_frecuencia, tipo_magnitud, tipo_gravedad ' .
                        'where aspectos.frecuencia=tipo_frecuencia.id AND aspectos.magnitud=tipo_magnitud.id AND aspectos.gravedad=tipo_gravedad.id)as pp',
                        'tipo_frecuencia', 'tipo_magnitud', 'tipo_gravedad', 'tipo_impactos_idiomas',
                        'tipo_frecuencia_idiomas', 'tipo_gravedad_idiomas', 'tipo_magnitud_idiomas'));
                    $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id',
                        'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                        'aspectos.tipo_aspecto<>3',
                        'aspectos.magnitud=tipo_magnitud.id', 'aspectos.frecuencia=tipo_frecuencia.id',
                        'aspectos.gravedad=tipo_gravedad.id',
                        'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia', 'tipo_frecuencia_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_gravedad.id=tipo_gravedad_idiomas.gravedad', 'tipo_gravedad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_magnitud.id=tipo_magnitud_idiomas.magnitud', 'tipo_magnitud_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                }

                break;


            case 'aambientales:revisionemergencia:ver':
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('formula'));
                $oDb->construir_Tablas(array('formula_aspectos'));
                $oDb->construir_Where(array('id=2'));
                $oDb->consulta();
                $aDevolver = $oDb->coger_Fila();
                $sFormulaMatrizAmbientales = $aDevolver[0];
                //$aBuscar=array('area','Aspecto','impacto');
                if ($_SESSION['areasactivadas']) {
                    $aBuscar = array('nombres' => array(gettext('sPLAspecto'), gettext('sPLImpacto')),
                        'campos' => array('aspectos.nombre', 'tipo_impactos_idiomas.valor'));
                    $sTabla = 'aspectos';
                    $aCampos = array('aspectos.id', "aspectos.nombre as \"" . gettext('sPMAspecto') . "\"", "tipo_impactos_idiomas.valor as \"" . gettext('sPMImpacto') . "\"", "areas.nombre as \"" . gettext('sPMArea') . "\"",
                        "tipo_probabilidad_idiomas.valor as \"probabilidad'", "tipo_severidad_idiomas.valor as \"severidad\"",
                        'pp.significancia',
                        "case when pp.significancia<$config->iValorRiesgoBajo then '" . gettext('sPMNoSignificativo') .
                        "' when pp.significancia<$config->iValorRiesgoMedio and pp.significancia >=$config->iValorRiesgoBajo then '" .
                        gettext('sPMRiesgoBajo') . "' when pp.significancia<$config->iValorRiesgoAlto and pp.significancia >=$config->iValorRiesgoMedio then '"
                        . gettext('sPMRiesgoMedio') . "' else '" . gettext('sPMRiesgoAlto') . "' end as \"" . gettext('sPMValoracion') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('aspectos', 'areas', 'tipo_impactos', 'tipo_severidad', 'tipo_probabilidad', 'tipo_aspectos',
                        '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_probabilidad,tipo_severidad where tipo_severidad.id=aspectos.severidad AND tipo_probabilidad.id=aspectos.probabilidad)as pp',
                        'tipo_impactos_idiomas', 'tipo_severidad_idiomas', 'tipo_probabilidad_idiomas'));
                    $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                        'aspectos.tipo_aspecto=3', 'tipo_probabilidad.id=aspectos.probabilidad', 'tipo_severidad.id=aspectos.severidad',
                        'aspectos.area_id=areas.id',
                        'aspectos.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                        'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_severidad.id=tipo_severidad_idiomas.severidad', 'tipo_severidad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad', 'tipo_probabilidad_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                } else {
                    $aBuscar = array('nombres' => array(gettext('sPLArea'), gettext('sPLAspecto'), gettext('sPLImpacto')),
                        'campos' => array('area', 'aspectos.nombre', 'tipo_impactos_idiomas.valor'));
                    $sTabla = 'aspectos';
                    $aCampos = array('aspectos.id', "aspectos.nombre as \"" . gettext('sPMAspecto') . "\"", "tipo_impactos_idiomas.valor as \"" . gettext('sPMImpacto') . "\"", "area as \"" . gettext('sPMArea') . "\"",
                        "tipo_probabilidad_idiomas.valor as \"" . gettext('sMatrProbabilidad') . "\"", "tipo_severidad_idiomas.valor as \"" . gettext('sFMGravedad') . "\"",
                        'pp.significancia',
                        "case when pp.significancia<$config->iValorRiesgoBajo then '" . gettext('sPMNoSignificativo') .
                        "' when pp.significancia<$config->iValorRiesgoMedio and pp.significancia >=$config->iValorRiesgoBajo then '" .
                        gettext('sPMRiesgoBajo') . "' when pp.significancia<$config->iValorRiesgoAlto and pp.significancia >=$config->iValorRiesgoMedio then '" .
                        gettext('sPMRiesgoMedio') . "' else '" . gettext('sPMRiesgoAlto') . "' end as \"" . gettext('sPMValoracion') . "\""
                    );
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos($aCampos);
                    $oDb->construir_Tablas(array('aspectos', 'tipo_impactos', 'tipo_severidad', 'tipo_probabilidad', 'tipo_aspectos',
                        '(select aspectos.id, ' . $config->FormulaMatrizAmbientales .
                        ' as significancia from aspectos,tipo_probabilidad,tipo_severidad where tipo_severidad.id=aspectos.severidad' .
                        ' AND tipo_probabilidad.id=aspectos.probabilidad)as pp',
                        'tipo_impactos_idiomas', 'tipo_severidad_idiomas', 'tipo_probabilidad_idiomas'));
                    $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                        'aspectos.tipo_aspecto=3', 'tipo_probabilidad.id=aspectos.probabilidad', 'tipo_severidad.id=aspectos.severidad',
                        'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_severidad.id=tipo_severidad_idiomas.severidad', 'tipo_severidad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                        'tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad', 'tipo_probabilidad_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                }
                break;

            case 'documentacion:legislacion:ver':
                $sTabla = 'legislacion_aplicable';
                $aBuscar = array('nombres' => array(gettext('sPLTitulo')),
                    'campos' => array('legislacion_aplicable.nombre'));
                $aCampos = array('legislacion_aplicable.id', "legislacion_aplicable.nombre as \"" . gettext('sPMTitulo') . "\"",
                    "tipo_area_aplicacion.nombre as \"" . gettext('sPMAreaIncidencia') . "\"",
                    "tipo_ambito_aplicacion_idiomas.valor as \"" . gettext('sPMAmbitoApli') . "\"",
                    "case when legislacion_aplicable.verifica='t' then '" . gettext('sPMCumple') .
                    "' when legislacion_aplicable.verifica='f' then '" . gettext('sPMNoCumple') . "' ELSE '" .
                    gettext('sPMNuncaComprobado') . "' END as \"" . gettext('sPMVerifica') . "\"");
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('legislacion_aplicable', 'tipo_area_aplicacion', 'tipo_ambito_aplicacion', 'tipo_ambito_aplicacion_idiomas'));
                $oDb->construir_Where(array('legislacion_aplicable.tipo_area=tipo_area_aplicacion.id',
                    'legislacion_aplicable.tipo_ambito=tipo_ambito_aplicacion.id',
                    'legislacion_aplicable.activo=\'t\'',
                    'tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb', 'tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid']
                ));
                break;

            case 'documentos:historicocuestionario:nuevo:fila':
                if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                    $_SESSION['legislacion'] = $_SESSION['pagina'][$aParametros['fila']];
                }
                $sTabla = 'historico_cuestionarios';
                $aCampos = array('historico_cuestionarios.id', "to_char(historico_cuestionarios.fecha, 'DD /MM/YYYY') as \"" . gettext('sPMFecha') . "\"",
                    "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.primer_apellido as \"" . gettext('sPMNombre') . "\"",
                    "case when historico_cuestionarios.cumple='t' then '" . gettext('sPMCumple') . "' else '" . gettext('sPMNoCumple') .
                    "' end as \"" . gettext('sPMCumplio') . "\""
                );
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('historico_cuestionarios', 'usuarios'));
                $oDb->construir_Where(array('historico_cuestionarios.usuario=usuarios.id', 'historico_cuestionarios.legislacion_aplicable=' . $_SESSION['legislacion']));
                break;

        }

        //Procesamos los posibles where via textfields

        if ($aParametros['where'] == "limpiar") {
            $aParametros['where'] = "limpiar";
        } else {
            if (is_array($aCampos)) {
                foreach ($aCampos as $sKey => $sValor) {
                    $aIntermedio = explode(' as ', $sValor);
                    $iDummy = count($aIntermedio);
                    if ($iDummy != 0)
                        $aCampos[$aIntermedio[$iDummy - 1]] = $aIntermedio[0];
                }
            }
            if (is_array($aParametros['where'])) {
                foreach ($aParametros['where'] as $sKey => $sValor) {
                    $oDb->pon_Where($sKey . " LIKE '%" . $sValor . "%'");
                }
            }

        }
        //Procesamos los posibles where via desplegables
        $aSelect = array();
        if ($aParametros['select'] != null) {
            foreach ($aParametros['select'] as $sKey => $sValor) {
                $oDb->pon_Where($sKey . "=" . $sValor);
                $aSelect[$sKey] = $sValor;
            }
        }

        $aOrder = explode(' ', $aParametros['order']);
        $iLongitud = count($aOrder);
        $sOrder = $aOrder[0];

        if (is_array($aOrder)) {
            foreach ($aOrder as $iKey => $sValor) {
                if (($sValor != "DESC") && ($sValor != "ASC") && ($iKey != 0)) {
                    $sOrder .= " " . $sValor;
                }
            }
        }
        $sSentidoOrder = $aOrder[$iLongitud - 1];
        $oDb->construir_Order(array('"' . $sOrder . '" ' . $sSentidoOrder));
        $oPager = new generador_listados($sAccion, $oDb, $aParametros['pagina'], $aParametros['numLinks'],
            $aParametros['numPaginas'], $sOrder, $sSentidoOrder,
            $sTabla, $aParametros['where'], $aBuscar);

        switch ($sAccion) {
            case 'mdocumentacion:legislacion':
                if (isset($aSelect['activo'])) {
                    $oPager->agrega_Desplegable(gettext('sLegVerifica'), null,
                        array($aSelect['activo'] => gettext('sLegAnterior'),
                            -1 => gettext('sLegAll'),
                            'true' => gettext('sLegSi'),
                            'false' => gettext('sLegNo')),
                        'Verifica');
                } else {
                    $oPager->agrega_Desplegable(gettext('sLegEstado'), null,
                        array(-1 => gettext('sLegAll'),
                            'true' => gettext('sLegSi'),
                            'false' => gettext('sLegNo')),
                        'Verifica');
                }
                break;
        }
        //Ponemos los botones
        if (is_array($aParametros['botones'])) {
            foreach ($aParametros['botones'] as $aBoton) {
                $oPager->agrega_Boton($aBoton[0], $aBoton[1], $aBoton[2]);
            }
        }
        $oPager->agrega_Desplegable(gettext('sElemPag'), $sAccion, array($aParametros['numPaginas'], 10, 20, 30, 50));
        return ($oPager->muestra_Pagina());
    }


    /**
     * Con esta funcion mostramos los detalles de un proveedor
     * @param array $aParametros
     * @return String
     */

    function procesa_Ver_Proveedor($aParametros)
    {
        require_once 'Manejador_Base_Datos.class.php';
        require_once 'boton.php';

        $iIdProveedor = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBoton = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Sacamos los datos de proveedor
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('nombre', 'direccion', 'telefono', 'web', 'cif', 'to_char(fecha_homologacion, \'DD/MM/YYYY\')',
            'to_char(ultima_revision, \'DD Mon YYYY\')', 'to_char(fecha_deshomologacion, \'DD/MM/YYYY\')'));
        $oBaseDatos->construir_Tablas(array('proveedores'));
        $oBaseDatos->construir_Where(array('id=' . $iIdProveedor));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml = "<table class=\"proveedores\">";
            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvNombre') . " &nbsp;&nbsp;&nbsp;&nbsp; </td>";
            $sHtml .= "<td>" . $aIterador[0] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvNombre') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[1] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvTelefono') . " &nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[2] . "</td>";
            //        $sHtml.="</tr>";

            //    $sHtml.="<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvWeb') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[3] . "</td>";


            //    $sHtml.="</tr>";

            //    $sHtml.="<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvCif') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[4] . "</td>";

            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvFechaHomol') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[5] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvUltimaRevHom') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[6] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sProvDeshomol') . "&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sHtml .= "<td>" . $aIterador[7] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "</table><br />";
            $sHtml .= $oBoton->to_Html();
        } else {
            $sHtml = gettext('sProvError') . "<br />" . $oBoton->to_Html();
        }
        $oBaseDatos->desconexion();
        return $sHtml;
    }


    function procesa_Ver_Equipo($sAccion, $aParametros)
    {
        require_once 'boton.php';
        require_once 'Manejador_Base_Datos.class.php';
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdEquipo = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('numero', 'numero_serie', 'descripcion', 'modelo', 'fabricante', 'ubicacion', 'ver_interna', 'fuera_uso', 'causa', 'fecha_fuera'));
        $oBaseDatos->construir_Tablas(array('equipos'));
        $oBaseDatos->construir_Where(array('id=' . $iIdEquipo));
        $oBaseDatos->consulta();
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml = "<table id=\"ver_equipos\" class=\"ver_docs\">";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqNumCtrl') . " </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[0] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqSN') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[1] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqDesc') . " </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[2] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqModelo') . " </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[3] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqFabrica') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[4] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sEqLugar') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "<span color='#333366'>" . $aIterador[5] . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            if ($aIterador[6] = 't') {
                $sHtml .= "<span class=\"campo\">" . gettext('sEqVerifInt') . "</span>";
            } else {
                $sHtml .= "<span class=\"campo\">" . gettext('sEqCalibra') . "</span>";
            }
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            if ($aIterador[7] == 't') {
                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sEqNoUso') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sEqFechaNoUso') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= "<span color='#333366'>" . $aIterador[9] . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sEqMotivo') . " </span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= "<span color='#333366'>" . $aIterador[8] . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
            $sHtml .= "</table>";
        }
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }


    function procesa_Ver_HistoricoPreguntas($aParametros)
    {
        $iIdHistorico = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBoton = new boton("Volver", "atras(-1)", "sincheck");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('preguntas', 'respuestas'));
        $oBaseDatos->construir_Tablas(array('historico_cuestionarios'));
        $oBaseDatos->construir_Where(array('(id=\'' . $iIdHistorico . '\')'));

        $oBaseDatos->consulta();

        if ($aIterador = $oBaseDatos->coger_Fila()) {
            //Le quitamos las {} al dato obtenido
            $sPreguntas = ltrim(rtrim($aIterador[0], '}'), '{');
            $aPreguntas = explode(',', $sPreguntas);
            $sRespuestas = ltrim(rtrim($aIterador[1], '}'), '{');
            $aRespuestas = explode(',', $sRespuestas);
            $sHtml = "<table class=\"cuestionario\">";

            $sHtml .= "<tr class=\"cuestionario\">";

            $sHtml .= "<th>";
            $sHtml .= gettext('sPregunta');
            $sHtml .= "</th>";
            $sHtml .= "<th>";
            $sHtml .= gettext('sRespuesta');
            $sHtml .= "</th>";
            $sHtml .= "</tr>";
            if ((is_array($aPreguntas)) && (is_array($aRespuestas))) {
                foreach ($aPreguntas as $sKey => $sValor) {
                    $sHtml .= "<tr>";
                    $sHtml .= "<td class=\"campo\">";
                    $sHtml .= ltrim(rtrim($sValor, '"'), '"');
                    $sHtml .= "</td>";
                    $sHtml .= "<td>";
                    if (ltrim(rtrim($aRespuestas[$sKey], '"'), '"') == 't') {
                        $sHtml .= gettext('sAfirmacion');
                    } else {
                        $sHtml .= gettext('sNegacion');
                    }
                    $sHtml .= "</td>";
                    $sHtml .= "</tr><br />";
                }
            }

            $sHtml .= "</table><br />";
            $sHtml .= $oBoton->to_Html();
        }
        return $sHtml;
    }

    function procesa_Ver_HistoricoPreguntasImprimir($aParametros)
    {
        $oBoton = new boton("Volver", "atras(-1)", "sincheck");

        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
            $_SESSION['legislacion'] = $_SESSION['pagina'][$aParametros['fila']];
        }

        $i = 0;

        while ($_SESSION['pagina'][$i]) {

            $sTabla = 'historico_cuestionarios';
            $aCampos = array('historico_cuestionarios.id', "to_char(historico_cuestionarios.fecha, 'DD /MM/YYYY') as \"" . gettext('sPMFecha') . "\"",
                "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.primer_apellido as \"" . gettext('sPMNombre') . "\"",
                "case when historico_cuestionarios.cumple='t' then '" . gettext('sPMCumple') . "' else '" .
                gettext('sPMNoCumple') . "' end as \"" . gettext('sPMCumplio') . "\""
            );
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos($aCampos);
            $oDb->construir_Tablas(array('historico_cuestionarios', 'usuarios'));
            $oDb->construir_Where(array('historico_cuestionarios.usuario=usuarios.id', 'historico_cuestionarios.legislacion_aplicable=' . $_SESSION['legislacion'],
                'historico_cuestionarios.id=' . $_SESSION['pagina'][$i]));

            $oDb->consulta();

            if ($aIterador0 = $oDb->coger_Fila()) {
                $sHtml = "<table border='0' width='500'>" .
                    "<tr>" .
                    "<td><b>" . strtoupper(gettext('sPMFecha')) . "</b></td>" .
                    "<td><b>" . strtoupper(gettext('sPMNombre')) . "</b></td>" .
                    "<td><b>" . strtoupper(gettext('sPMCumplio')) . "</b></td>" .
                    "</tr>";

                $sHtml .= "<tr>" .
                    "<td>" . $aIterador0[1] . "</td>" .
                    "<td>" . $aIterador0[2] . "</td>" .
                    "<td>" . $aIterador0[3] . "</td>" .
                    "</tr>";

                $sHtml .= "</table><br /><br />";

                $oDb1 = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

                $oDb1->iniciar_Consulta('SELECT');
                $oDb1->construir_Campos(array('preguntas', 'respuestas'));
                $oDb1->construir_Tablas(array('historico_cuestionarios'));
                $oDb1->construir_Where(array('historico_cuestionarios.legislacion_aplicable=' . $_SESSION['legislacion'],
                    'historico_cuestionarios.id=' . $_SESSION['pagina'][$i]));


                //    $oBaseDatos->construir_Where(array('(id=\''.$iIdHistorico.'\')'));

                $oDb1->consulta();

                if ($aIterador = $oDb1->coger_Fila()) {
                    //Le quitamos las {} al dato obtenido
                    $sPreguntas = ltrim(rtrim($aIterador[0], '}'), '{');
                    $aPreguntas = explode(',', $sPreguntas);
                    $sRespuestas = ltrim(rtrim($aIterador[1], '}'), '{');
                    $aRespuestas = explode(',', $sRespuestas);
                    $sHtml .= "<table class=\"cuestionario\">";

                    $sHtml .= "<tr class=\"cuestionario\">";

                    $sHtml .= "<th>";
                    $sHtml .= gettext('sPregunta');
                    $sHtml .= "</th>";
                    $sHtml .= "<th>";
                    $sHtml .= gettext('sRespuesta');
                    $sHtml .= "</th>";
                    $sHtml .= "</tr>";
                    if ((is_array($aPreguntas)) && (is_array($aRespuestas))) {
                        foreach ($aPreguntas as $sKey => $sValor) {
                            $sHtml .= "<tr>";
                            $sHtml .= "<td class=\"campo\">";
                            $sHtml .= ltrim(rtrim($sValor, '"'), '"');
                            $sHtml .= "</td>";
                            $sHtml .= "<td>";
                            if (ltrim(rtrim($aRespuestas[$sKey], '"'), '"') == 't') {
                                $sHtml .= gettext('sAfirmacion');
                            } else {
                                $sHtml .= gettext('sNegacion');
                            }
                            $sHtml .= "</td>";
                            $sHtml .= "</tr><br />";

                        }
                    }

                    $sHtml .= "</table><br />";


                }


            }//if
            $i++;
        }//while


        $sHtml .= $oBoton->to_Html();


        return $sHtml;
    }

}
