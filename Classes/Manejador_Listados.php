<?php
namespace Tuqan\Classes;


class Manejador_Listados
{

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sMenu_Completo
     * @return array
     */
    function prepara_Listado_Inicial_Sec($sAccion, $aDatos, $sMenu_Completo)
    {
        unset ($_SESSION['where']);
        if (($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if ($aDatos[1] == null) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenu_Completo . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));
            $oBaseDatos->consulta();
            $aBotones = array();
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            switch ($sAccion) {

                case 'catalogo:areadoc:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMCSeleccionar'), "sndReq('catalogo:areadoc:listado:ver:fila','',1)", "fila"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta"));
                    break;
                case 'catalogo:documentoproceso:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMCAgregar'), "sndReq('catalogo:documentoproceso:comun:alta:general','',1)", "general"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                    );
                    break;
                case 'catalogo:indicadoresproceso:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMCAgregar'), "sndReq('catalogo:indicadoresproceso:comun:alta:general','',1)", "general"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                    );
                    break;
                case 'equipos:planmantenimientoid:nuevo':
                    $_SESSION['equipo'] = $aDatos[0];
                    $aDatos[0] = 1;
                    $aDatos[1] = gettext('sMCRealizacion') . " ASC";
                    $aBotones = array(array(gettext('sMCDetalles'), "sndReq('equipos:mantenimiento:detalles:ver:fila','',1)", "fila"),
                        array(gettext('sMCMantPreventivo'), "sndReq('equipos:mantenimientoprev:formulario:nuevo','',1)", "sincheck"),
                        array(gettext('sMCMantCorrectivo'), "sndReq('equipos:mantenimientocorr:formulario:nuevo','',1)", "sincheck"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                    );
                    break;
            }
        }
        if ($aDatos[2] == null) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sMenu_Completo
     * @return array
     */
    function prepara_Listado_Inicial($sAccion, $aDatos, $sMenu_Completo)
    {
        unset ($_SESSION['where']);
        unset ($_SESSION['ultimolistado']);
        unset ($_SESSION['ultimolistadodatos']);
        unset ($_SESSION['paginaanterior']);
        $_SESSION['ultimolistado'] = array($sMenu_Completo);
        $_SESSION['ultimolistadodatos'] = array($aDatos);
        $_SESSION['paginaanterior'] = array($_SESSION['pagina']);
        if (!isset($aDatos[0]) || ($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if (!isset($aDatos[1])) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenu_Completo . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));
            $oBaseDatos->consulta();
            $aBotones = array();
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            $aBotones = Botones::getButtons($sMenu_Completo);
            switch ($sAccion) {
                case 'mejora:listado:ver':
                    $aDatos[1] = gettext('sMCFecha') . " ASC";
                    break;

                case 'inicio:tareas:ver':
                    $aDatos[1] = gettext('sMCDocumento') . " ASC";
                    $aBotones = array(array(gettext('sMCVerTarea'), "sndReq('inicio:tareas:comun:ver:fila','',1)", "fila"),
                        array(gettext('sMCVerDocumento'), "sndReq('inicio:documentoid:detalles:ver:fila','',1)", "fila"),
                        array(gettext('sBajarxls'), "sndReq('inicio:tareas:excel:ver','',1)", "noafecta")
                    );
                    break;

                case 'catalogo:areadoc:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMCSeleccionar'), "sndReq('catalogo:areadoc:listado:ver:fila','',1)", "fila"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta"));
                    break;

                case 'editor:documentos:ver':
                    $aDatos[1] = gettext('sMCCodigo') . " ASC";
                    $aBotones = array(array(gettext('sMCAgregar'), "sndReq('editor:documento:editor:nuevo','',1)", "sincheck"),
                        array(gettext('sMCEditar'), "sndReq('editor:documento:editor:editar:fila','',1)", "fila")
                    );
                    break;

                case 'documentacion:documentonormativa:ver':
                case 'documentacion:aai:ver':
                case 'documentacion:normativa:ver':
                case 'documentacion:pg:ver':
                case 'documentacion:procesoarchivo:ver':
                case 'documentacion:pe:ver':

                case 'documentacion:planamb:ver':
                case 'documentacion:frl:ver':
                case 'documentacion:manual:ver':
                case 'documentacion:docvigor:ver':
                case 'documentacion:docborrador:ver':
                case 'documentacion:docformatos:ver':
                    $aDatos[1] = gettext('sMCCodigo') . " ASC";
                    break;

                case 'documentacion:registros:ver':
                    $aDatos[1] = gettext('sMCId') . " ASC";
                    break;

                case 'formacion:cursos:ver':
                case 'formacion:inscripcion:ver':
                case 'formacion:planes:ver':
                case 'proveedores:phomologados:ver':
                case 'proveedores:listado:ver':
                case 'proveedores:contactos:ver':
                case 'proveedores:productos:ver':
                case 'prov:listado:crear:nuevo':
                case 'indicadores:indicadores:ver':
                case 'auditorias:programa:ver':
                case 'administracion:centros:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    break;

                case 'proveedores:incidencias:ver':
                    $aDatos[1] = gettext('sMCFecha') . " ASC";
                    break;

                case 'auditorias:plan:ver':
                    $aDatos[1] = gettext('sMCDescripcion') . " ASC";
                    break;

                case 'indicadores:objetivos:ver':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    break;

                case 'administracion:aspectos:frecuencia':
                case 'administracion:aspectos:significancia':
                case 'administracion:aspectos:magnitud':
                case 'administracion:aspectos:gravedad':
                case 'administracion:aspectos:probabilidad':
                case 'administracion:aspectos:severidad':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    break;

                case 'administracion:modulos:nuevo':
                    $aDatos[1] = gettext('sMCModulo') . " ASC";
                    break;

                case 'administracion:aspectos:formula':
                    $aDatos[1] = gettext('sMCFormula') . " ASC";
                    break;

                case 'equipos:listado:ver':
                    $aDatos[1] = gettext('sMCNumeroControl') . " ASC";
                    break;

                case 'auditorias:areaauditoria:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMCAgregar'), "sndReq('auditorias:areaauditoria:comun:alta:general','',1)", "general"),
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                    );
                    break;

                case 'equipos:revision:ver':
                    $aDatos[1] = gettext('sMCNumeroControl') . " ASC";
                    $aBotones = array(array(gettext('sMCPlanMant'), "sndReq('equipos:planmantenimiento:listado:ver:fila','',1)", "fila")
                    );
                    break;
            }
        }
        TuqanLogger::debug("End of manejador_listados: ", ['adatos' => $aDatos]);
        if (!(isset($aDatos[2]))) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sCodigo
     * @return mixed
     */
    function prepara_Datos_Permisos_Documentos($sAccion, $aDatos, $sCodigo)
    {
        $aDatos['id'] = $_SESSION['pagina'][$aDatos[0]];
        $aDatos['accion'] = $sCodigo;
        return $aDatos;
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Listado_Inicial_Fila_Nuevo($sAccion, $aDatos)
    {


        unset ($_SESSION['where']);
        $aDatos[3] = 1;
        switch ($sAccion) {

            case 'administracion:permisosdocumento:ver:fila':

                $aDatos[4] = gettext('sMNNombre') . " ASC";
                $aBotones = array(array(gettext('sMCPlanMant'), "atras(-1)", "noafecta"),
                    array(gettext('sMAActualizar'), "sndReq('ver:permisosdocumento','',1)", "sincheck")
                );
                break;
            case 'proveedores:criterio:nuevo':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCVolver'), "atras(-1)", "sincheck"),
                    array(gettext('sMCAgregar'), "sndReq('proveedores:criterios:comun:alta:general','',1)", "general")
                );
                break;

            case 'administracion:menu:verbotones:fila':
                $aDatos[4] = gettext('sMNAccion') . " ASC";
                $aBotones = array(array(gettext('sMANuevo'), "sndReq('administracion:boton:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMAEditar'), "sndReq('administracion:boton:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMNIdiomas'), "sndReq('administracion:idiomasboton:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCPlanMant'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:idiomasboton:ver:fila':
                $aDatos[4] = gettext('sMNIdiomas') . " ASC";
                $aBotones = array(array(gettext('sMAEditar'), "sndReq('administracion:idiomaboton:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCPlanMant'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:idiomas:ver:fila':
                $aDatos[4] = gettext('sMNIdiomas') . " ASC";
                $aBotones = array(array(gettext('sMAEditar'), "sndReq('administracion:idiomamenu:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMANuevo'), "sndReq('administracion:idiomamenu:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCPlanMant'), "atras(-1)", "noafecta")
                );
                break;

            case 'auditorias:horarioauditoria:ver:fila':
                $aDatos[4] = gettext('sMNArea') . " ASC";
                $aBotones = array(array(gettext('sMANuevo'), "sndReq('auditorias:horarioauditoria:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMAEditar'), "sndReq('auditorias:horarioauditoria:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCPlanMant'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('auditorias:horarioauditoria:excel:ver','',1)", "noafecta")
                );
                break;

            case 'formacion:alumno:nuevo':
                $aDatos[4] = "alumno ASC";
                $aBotones = array(array(gettext('sMLAgregar'), "sndReq('formacion:alumno:comun:alta:general','',1)", "general"),
                    array(gettext('sMLVolver'), "atras(-1)", "noafecta")
                );
                break;
        }

        $iNumeroPaginas = 20;
        $_SESSION['botones'] = $aBotones;
        return (array('fila' => $aDatos[0], 'accion' => $sAccion, 'pagina' => $aDatos[3],
            'order' => $aDatos[4], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones
        ));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Listado_Inicial_Fila($sAccion, $aDatos)
    {
        unset ($_SESSION['where']);
        $aDatos[3] = 1;
        switch ($sAccion) {
            case 'documentacion:listadoregistros:ver:fila':
            case 'formacion:fichapersonal:ver':
            case 'formacion:reqpuesto:ver':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                if ($aDatos[1] == "directo") {
                    $_SESSION['tipoRegistro'] = $aDatos[0];
                    $_SESSION['directo'] = 1;
                } else if ($_SESSION['pagina'][$aDatos[0]] != null) {
                    $_SESSION['tipoRegistro'] = $_SESSION['pagina'][$aDatos[0]];
                }
                switch ($_SESSION['tipoRegistro']) {
                    case 1:
                        {
                            $aBotones = array(array(gettext('sMLVolver'), "sndReq('formacion:fichapersonal:formulario:nuevo','',1)", "sincheck"),
                                array(gettext('sMCVerPDF'), "sndReq('recursos:fichapersonal:listado:pdf:fila','',1)", "fila"),
                                array(gettext('sMCEditar'), "sndReq('formacion:fichapersonal:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCDatosPersonales'), "sndReq('formacion:fpdp:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCIncorporacion'), "sndReq('formacion:fpinc:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCFormacion'), "sndReq('formacion:fpfor:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCPreFormacion'), "sndReq('formacion:fppre:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCIdiomas'), "sndReq('formacion:fpidiomas:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCCursos'), "sndReq('formacion:fpcursos:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCFormacionTecnica'), "sndReq('formacion:fpft:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCExperienciaLaboral'), "sndReq('formacion:fpel:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCCambiosPerfil'), "sndReq('formacion:fpcp:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCCambiosDpto'), "sndReq('formacion:fpcd:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sBajarxls'), "sndReq('documentacion:listadoregistros:excel:ver','',1)", "noafecta")
                            );
                            break;
                        }
                    case 2:
                        {

                            $aBotones = array(array(gettext('sMLVolver'), "sndReq('formacion:reqpuesto:formulario:nuevo','',1)", "sincheck"),
                                array(gettext('sMCVerPDF'), "sndReq('formacion:reqpuesto:listado:pdf:fila','',1)", "fila"),
                                array(gettext('sMCEditar'), "sndReq('formacion:reqpuesto:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCDatosPuesto'), "sndReq('formacion:datospuesto:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCCompetencias'), "sndReq('formacion:competenciasrq:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCFormacion'), "sndReq('formacion:formacionrq:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCPromocion'), "sndReq('formacion:promocionrq:formulario:editar:fila','',1)", "fila"),
                                array(gettext('sMCFormacionTecnica'), "sndReq('formacion:reqpuesto:listado:formaciontecnicarq:fila','',1)", "fila"),
                                array(gettext('sBajarxls'), "sndReq('formacion:reqpuesto:excel:ver','',1)", "noafecta")
                            );
                            break;
                        }

                    default:
                }
                if ($aDatos[1] != "directo") {
                    $aBotones[] = array(gettext('sMLVolver'), "atras(-1)", "noafecta");
                }
                break;

            case 'administracion:tipodocidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:tipodocidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCNuevoUnico'), "sndReq('administracion:tipodocidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'formacion:reqpuesto:formaciontecnicarq:fila':
                $aDatos[4] = gettext('sPCOpcional') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('formacion:formaciontecnicarq:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('formacion:formaciontecnicarq:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")


                );
                break;

            case 'administracion:tipoambidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:tipoambidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:tipoambidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:tipoimpidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:tipoimpidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:tipoimpidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:mejoraidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:mejoraidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:mejoraidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:magnitudidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:magnitudidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:magnitudidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:gravedadidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:gravedadidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:gravedadidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:probabilidadidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:probabilidadidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:probabilidadidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:frecuenciaidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:frecuenciaidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:frecuenciaidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'administracion:severidadidioma:idioma:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCEditar'), "sndReq('administracion:severidadidioma:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMLVolver'), "sndReq('administracion:severidadidioma:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'documentos:registros:listarfila:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                if ($_SESSION['pagina'][$aDatos[0]] != null) {
                    $_SESSION['tipoRegistro'] = $_SESSION['pagina'][$aDatos[0]];
                }
                switch ($_SESSION['tipoRegistro']) {
                    case 1:
                        {
                            $aBotones = array(array(gettext('sMCAlta'), "sndReq('administracion:ficha_personal:comun:alta:general','',1)", "general"),
                                array(gettext('sMCBaja'), "sndReq('administracion:ficha_personal:comun:baja:general','',1)", "general"),
                                array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                                array(gettext('sBajarxls'), "sndReq('adminisracion:adminregistros:excel:ver','',1)", "noafecta")
                            );
                            break;
                        }
                    case 2:
                        {
                            $aBotones = array(array(gettext('sMCAlta'), "sndReq('administracion:requisitos_puesto:comun:alta:general','',1)", "general"),
                                array(gettext('sMCBaja'), "sndReq('administracion:requisitos_puesto:comun:baja:general','',1)", "general"),
                                array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                                array(gettext('sBajarxls'), "sndReq('adminisracion:adminregistros:excel:ver','',1)", "noafecta")
                            );
                            break;
                        }
                }
                break;

            case 'indicadores:valoresindicador:ver:fila':
                $aDatos[4] = gettext('sMCValor') . " ASC";
                $aBotones = array(array(gettext('sMCNuevoMultiple'), "sndReq('indicadores:valor:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCNuevoUnico'), "sndReq('indicadores:valorunico:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('indicadores:valor:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('indicadores:valor:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('indicadores:valoresindicador:excel:ver','',1)", "noafecta")
                );
                break;

            case 'catalogo:documentosproceso:ver':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('catalogo:documentoproceso:listado:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEliminar'), "sndReq('catalogo:documentoproceso:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVer'), "sndReq('catalogo:documentosproceso:comun:ver:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'catalogo:indicadores:ver:radio':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('catalogo:indicadoresproceso:listado:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEliminar'), "sndReq('catalogo:indicadoresproceso:comun:baja:general','',1)", "general"),
                    array(gettext('sMCValores'), "sndReq('catalogo:valoresindicador:listado:editar:fila','',1)", "fila"),
                    array(gettext('sMCObjetivos'), "sndReq('catalogo:objetivosindicador:listado:editar:fila','',1)", "fila"),
                    array(gettext('sMCGrafica'), "sndReq('catalogo:graficaindicador:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                );
                break;

            case 'catalogo:objetivosindicador:editar:fila':
                $aDatos[4] = gettext('sMCFecha') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('catalogo:objindicador:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('catalogo:objindicador:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('catalogo:objetivos_indicadores:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'formacion:inscripcion:asistentes:fila':
                $aDatos[4] = gettext('sMCUsuario') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('formacion:asistentes:excel:ver','',1)", "noafecta"));
                break;

            case 'formacion:planes:ver:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sMCAgregar'), "sndReq('formacion:planes:listado:plan:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('formacion:cursoplan:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('formacion:cursos:comun:baja:general','',1)", "general"),
                    array(gettext('sMCEstadoDetalles'), "sndReq('formacion:cursoplandetalles:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCAsistentes'), "sndReq('formacion:verAsistentesCurso:listado:nuevo:fila','',1)", "fila"),
                    array(gettext('sMCProfesores'), "sndReq('formacion:verProfesores:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCFirmas'), "sndReq('formacion:adjunto:formulario:nuevo:fila','',1)", "fila"),
                    array(gettext('sFCOVerHojaFirmas'), "sndReq('formacion:adjunto:comun:ver:fila','',1)", "fila"),
                    array(gettext('sMCEnviarMsj'), "sndReq('formacion:mensajetodos:formulario:crear:nuevo','',1)", "sincheck"),
                    array(gettext('sBajarxls'), "sndReq('formacion:cursosplan:excel:ver','',1)", "noafecta")
                );
                break;

            case 'documentacion:metas:ver:fila':
                $aDatos[4] = gettext('sMCNumeroMeta') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('documentacion:meta:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('documentacion:meta:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCBaja'), "sndReq('documentacion:meta:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('documentacion:meta:excel:ver','',1)", "noafecta")

                );
                break;

            case 'documentacion:objetivos:seguimiento:fila':
                $aDatos[4] = gettext('sMCFecha') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('documentacion:seguimiento:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('documentacion:seguimiento:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCSubirFichero'), "sndReq('documentacion:seguimiento:formulario:nuevo:fila','',1)", "general"),
                    array(gettext('sMCVerFichero'), "sndReq('documentacion:seguimiento:comun:ver:fila','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('documentacion:seguimiento:excel:ver','',1)", "noafecta")
                );
                break;

            case 'indicadores:objetivos:vermetas:fila':
                $aDatos[4] = gettext('sMCNumeroMeta') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('indicadores:metaobjetivosindicadores:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('indicadores:metaobjetivosindicadores:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCBaja'), "sndReq('indicadores:metaobjetivosindicadores:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('indicadores:vermetas:excel:ver','',1)", "noafecta")
                );
                break;

            case 'formacion:verProfesores:ver:fila':
                $aDatos[4] = gettext('sMCProfesor') . " ASC";
                $aBotones = array(array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sMLVolver'), "sndReq('formacion:profesor:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('formacion:profesor:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('formacion:profesores:comun:baja:general','',1)", "general"),
                    array(gettext('sBajarxls'), "sndReq('formacion:verProfesores:excel:ver','',1)", "noafecta")
                );
                break;

            case 'catalogo:valoresindicador:editar:fila':
                $aDatos[4] = gettext('sMCValor') . " ASC";
                $aBotones = array(array(gettext('sMCNuevoMultiple'), "sndReq('catalogo:valor:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCNuevoUnico'), "sndReq('catalogo:valorunico:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('catalogo:valor:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('catalogo:valores:comun:baja:general','',1)", "general"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'formacion:verAsistentesCurso:nuevo:fila':
                $aDatos[4] = gettext('sMCUsuario') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('formacion:alumno:listado:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEliminar'), "sndReq('formacion:alumno:comun:baja:general','',1)", "general"),
                    array(gettext('sMCConfirmarAlta'), "sndReq('formacion:confirmaralumno:comun:alta:fila','',1)", "fila"),
                    array(gettext('sMCConfirmarBaja'), "sndReq('formacion:confirmaralumno:comun:baja:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'proveedores:productos:ver:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('proveedores:producto:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVer'), "sndReq('proveedores:producto:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCEditar'), "sndReq('proveedores:producto:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCCriterios'), "sndReq('proveedores:productos:listado:criterios:fila')", "fila"),
                    array(gettext('sMCCriterios'), "sndReq('proveedores:producto:comun:revisar:fila','',1)", "fila"),
                    array(gettext('sMCHistorico'), "sndReq('proveedores:productos:listado:productoshistorico:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'proveedores:productos:productoshistorico:fila':
                $aDatos[4] = gettext('sMCFecha') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "atras(-1)", "noafecta"));
                break;

            case 'proveedores:productos:criterios:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sMLAgregarCriterio'), "sndReq('proveedores:criterio:listado:nuevo','',1)", "sincheck"),
                    array(gettext('sMLQuitarCriterio'), "sndReq('proveedores:criterios:comun:baja:general','',1)", "general")
                );
                break;

            case 'proveedores:contactos:ver:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array("Nuevo", "sndReq('proveedores:contacto:formulario:nuevo','',1)", "sincheck"),
                    array("Ver", "sndReq('proveedores:contacto:listado:ver:fila','',1)", "fila"),
                    array("Editar", "sndReq('proveedores:contacto:formulario:editar:fila','',1)", "fila"),
                    array("Volver", "atras(-1)", "noafecta")
                );
                break;

            case 'proveedores:incidencias:ver:fila':
                $aDatos[4] = gettext('sMCFecha') . " ASC";
                $aBotones = array(array(gettext('sMLVolver'), "sndReq('proveedores:incidencia:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVer'), "sndReq('proveedores:incidencia:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCEditar'), "sndReq('proveedores:incidencia:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "sincheck")
                );
                break;

            case 'equipos:planmantenimiento:ver:fila':
                $aDatos[4] = gettext('sMCRealizacion') . " ASC";
                $aBotones = array(array(gettext('sMCDetalles'), "sndReq('equipos:mantenimiento:detalles:ver:fila','',1)", "fila"),
                    array(gettext('sMCMantPreventivo'), "sndReq('equipos:mantenimientoprev:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCMantCorrectivo'), "sndReq('equipos:mantenimientocorr:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;


            case 'equipos:planmantenimiento:nuevo:fila':
                $aDatos[4] = gettext('sMCRealizacion') . " ASC";
                $aBotones = array(array(gettext('sMCDetalles'), "sndReq('equipos:mantenimiento:detalles:ver:fila','',1)", "fila"),
                    array(gettext('sMCMantPreventivo'), "sndReq('equipos:mantenimientoprev:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCMantCorrectivo'), "sndReq('equipos:mantenimientocorr:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;

            case 'auditoria:programa:ver:fila':
                $aDatos[4] = gettext('sMCDescripcion') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('auditorias:auditoria:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEditar'), "sndReq('auditorias:auditoria:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEliminar'), "sndReq('auditorias:auditoria:comun:baja:general','',1)", "general"),
                    array(gettext('sMCEstadoDetalles'), "sndReq('auditorias:auditoria:detalles:ver:fila','',1)", "fila"),
                    array(gettext('sMCPlanAuditoria'), "sndReq('auditorias:plan:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCEquipoAuditor'), "sndReq('auditorias:equipoauditor:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCHorarioAuditorias'), "sndReq('auditorias:horarioauditoria:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMCInforme'), "sndReq('auditorias:informeauditoria:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('auditoria:progauditoria:excel:ver','',1)", "noafecta")
                );
                break;

            case 'auditorias:areasauditoria:ver:fila':
                $aDatos[4] = gettext('sMCNombre') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('auditorias:areaauditoria:listado:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEliminar'), "sndReq('auditorias:areaauditoria:comun:baja:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta")
                );
                break;


            case 'auditorias:equipoauditor:ver:fila':
                $aDatos[4] = gettext('sMCAuditor') . " ASC";
                $aBotones = array(array(gettext('sMCAgregar'), "sndReq('auditorias:auditor:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMCEliminar'), "sndReq('auditorias:auditor:comun:baja:fila','',1)", "fila"),
                    array(gettext('sMCSubirDocAdj'), "sndReq('auditorias:adjunto:formulario:nuevo:fila','',1)", "fila"),
                    array(gettext('sMCVerDocAdj'), "sndReq('auditorias:adjunto:comun:ver:fila','',1)", "fila"),
                    array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('auditoria:equipoauditor:excel:ver','',1)", "noafecta")
                );
                break;

        }

        $iNumeroPaginas = 20;
        $_SESSION['botones'] = $aBotones;
        return (array('fila' => $aDatos[0], 'accion' => $sAccion, 'pagina' => $aDatos[3],
            'order' => $aDatos[4], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones
        ));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sMenu_Completo
     * @return array
     */
    function prepara_Listado_Inicial_Comun($sAccion, $aDatos, $sMenu_Completo)
    {
        /*    unset ($_SESSION['where']);
            unset ($_SESSION['ultimolistado']);
            unset ($_SESSION['ultimolistadodatos']);
            unset ($_SESSION['paginaanterior']);
            $_SESSION['ultimolistado']=array($sMenu_Completo);
            $_SESSION['ultimolistadodatos']=array($aDatos);
            $_SESSION['paginaanterior']=array($_SESSION['pagina']);*/
        if (($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if ($aDatos[1] == null) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenu_Completo . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));

            $oBaseDatos->consulta();
            $aBotones = array();
            /*
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'],$_SESSION['pass'],$_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones.texto','botones.accion','botones.tipo_botones'));
            $oBaseDatos->construir_Tablas(array('botones','menu'));
            $oBaseDatos->construir_Where(array('menu.id=botones.menu','menu.accion=\'inicio:mensajes\''));
            $oBaseDatos->consulta();
            $aBotones=array();
            */
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            switch ($sAccion) {
                case 'inicio:mensajes':
                case 'inicio:historicomensajes:ver':
                    $aDatos[1] = gettext('sMAEnviado') . " ASC";
                    $aBotones = array(
                        array(gettext('sMCVolver'), "atras(-1)", "noafecta"),
                        array(gettext('sMAVer'), "sndReq('inicio:mensajes:listado:ver:fila','',1,'administracion:mensajes')", "fila"),
                        array(gettext('sBajarxls'), "sndReq('inicio:historicomensajes:excel:ver','',1)", "noafecta")

                    );
                    break;

                case 'formacion:planes:plan:nuevo':
                    $aDatos[1] = gettext('sMCNombre') . " ASC";
                    $aBotones = array(array(gettext('sMLAgregar'), "sndReq('formacion:cursoplan:formulario:nuevo:fila','',1)", "fila"),
                        array(gettext('sMLVolver'), "atras(-1)", "noafecta")
                    );
                    break;

                case 'qnovainicial':
                case 'calidad':
                    $aDatos[1] = gettext('sMCOEnviado') . " ASC";
                    $aBotones[] = array(gettext('sMCOEliminar'), "sndReq('inicio:mensajes:comun:baja:general','',1)", "general");
                    $aBotones[] = array(gettext('sMCOVer'), "sndReq('inicio:mensajes:listado:ver:fila','',1,'inicio:mensajes')", "fila");
                    $aBotones[] = array(gettext('sMCOHistorico'), "sndReq('inicio:historicomensajes:listado:ver','',1)", "noafecta");
                    break;
            }
        }
        if ($aDatos[2] == null) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sMenu_Completo
     * @return array
     */
    function prepara_Listado_Inicial_Medio($sAccion, $aDatos, $sMenu_Completo)
    {
        unset ($_SESSION['where']);
        unset ($_SESSION['ultimolistado']);
        unset ($_SESSION['ultimolistadodatos']);
        unset ($_SESSION['paginaanterior']);
        $_SESSION['ultimolistado'] = array($sMenu_Completo);
        $_SESSION['ultimolistadodatos'] = array($aDatos);
        $_SESSION['paginaanterior'] = array($_SESSION['pagina']);
        if (($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if ($aDatos[1] == null) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenu_Completo . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));

            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->pon_Where('botones.permisos[' . $_SESSION['perfil'] . ']=true');
            }
            $oBaseDatos->consulta();
            $aBotones = array();
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            switch ($sAccion) {
                case 'aambientales:matriz:ver':
                case 'aambientales:revision:ver':
                case 'aambientales:revisionemergencia:ver':
                    $aDatos[1] = "significancia ASC";
                    break;

                case 'documentacion:legislacion:ver':
                    $aDatos[1] = gettext('sMMTitulo') . " ASC";
                    break;
            }
        }
        if ($aDatos[2] == null) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }


    function prepara_Listado_Inicial_Adm($sAccion, $aDatos, $sMenu_Completo)
    {
        unset ($_SESSION['where']);
        unset ($_SESSION['ultimolistado']);
        unset ($_SESSION['ultimolistadodatos']);
        unset ($_SESSION['paginaanterior']);
        $_SESSION['ultimolistado'] = array($sMenu_Completo);
        $_SESSION['ultimolistadodatos'] = array($aDatos);
        $_SESSION['paginaanterior'] = array($_SESSION['pagina']);
        if (($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if ($aDatos[1] == null) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenu_Completo . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));

            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->pon_Where('botones.permisos[' . $_SESSION['perfil'] . ']=true');
            }

            $oBaseDatos->consulta();
            $aBotones = array();
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            //Aqui ponemos la ordenacion de cada listado por defecto
            switch ($sAccion) {
                case 'administracion:usuarios:ver':
                    $aDatos[1] = gettext('sMAPrimerApe') . " ASC";
                    break;

                case 'administracion:clientes:ver':
                case 'administracion:criterios:ver':
                case 'administracion:tiposareas:ver':
                case 'administracion:tiposamb:ver':
                case 'administracion:tipomejora:ver':
                case 'administracion:tiposimp:ver':
                case 'administracion:tipo_cursos:ver':
                case 'administracion:registros:ver':
                case 'administracion:perfiles:ver':
                case 'administracion:tipodocumento:nuevo':
                case 'administracion:hospitales:nuevo':
                    $aDatos[1] = gettext('sMANombre') . " ASC";
                    break;

                case 'administracion:ayuda:nuevo':
                    $aDatos[1] = gettext('sAMenu') . " ASC";
                    break;

                case 'administracion:menus:nuevo':
                    $aDatos[1] = gettext('sMNValor') . " ASC";
                    break;

                case 'administracion:legaplicable:ver':
                    $aDatos[1] = gettext('sMATitulo') . " ASC";
                    break;

                case 'administracion:mensajes:ver':
                    $aDatos[1] = gettext('sMAEnviado') . " ASC";
                    break;

                case 'administracion:tareas:ver':
                    $aDatos[1] = gettext('sMAOrigen') . " ASC";
                    break;

                case 'administracion:normativa:ver':
                case 'documentacion:politica:ver':
                    $aDatos[1] = gettext('sMACodigo') . " ASC";
                    break;

                case 'administracion:documentossg:ver':
                    {
                        $aDatos[1] = gettext('sMACodigo') . " ASC";

                        break;
                    }
                case 'documentacion:objetivos:ver':
                    $aDatos[1] = gettext('sPANombre') . " ASC";
                    break;

                case 'administracion:proveedores:ver':
                case 'administracion:proveedores:incidencia':
                case 'administracion:proveedores:contacto':
                case 'administracion:proveedores:producto':
                    $aDatos[1] = gettext('sMANombre') . " ASC";
                    break;

                case 'administracion:equipos:ver':
                    $aDatos[1] = gettext('sPCDescripcion') . " ASC";
                    break;
                case 'administracion:auditoriaanual:ver':
                    $aDatos[1] = gettext('sMANombre') . " ASC";
                    break;

                case 'administracion:auditoriavigor:ver':
                    $aDatos[1] = gettext('sPCDescripcion') . " ASC";
                    break;

                case 'administracion:indicadoresobjetivo:ver':
                    $aDatos[1] = gettext('sPANombre') . " ASC";
                    break;

            }
        }
        if ($aDatos[2] == null) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }

    /**
     * Esta funcion prepara para ver una fila de un listado
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Ver_Fila_Comun($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $aDatos[1]));
    }

//Estos dos procedimiento son para listados especificos que reciben algun parametro extra
    function prepara_Listado_Historico($sAccion, $aDatos)
    {
        $aBotones = array(array("Volver", "atras(-1)", "noafecta"),
            array("Ver", "sndReq('catalogo:verdocumentoprocesosinfilahistorial:listado:ver:fila','',1)", "fila"));
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => 1,
            'order' => 'revision ASC', 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
            'codigo' => $aDatos[0], 'nombre' => $aDatos[1]));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Listado_Inicial_Fila_Adm($sAccion, $aDatos)
    {
        unset ($_SESSION['where']);
        $aDatos[3] = 1;
        switch ($sAccion) {
            case 'administracion:preguntasleg:nuevo:fila':
                $aDatos[4] = gettext('sMAPregunta') . " ASC";
                $aBotones = array(array(gettext('sMANuevo'), "sndReq('administracion:preguntasleg:formulario:nuevo','',1)", "sincheck"),
                    array(gettext('sMAEditar'), "sndReq('administracion:preguntasleg:formulario:editar:fila','',1)", "fila"),
                    array(gettext('sMADarDeBaja'), "sndReq('administracion:preguntasleg:comun:baja:general','',1)", "general"),
                    array(gettext('sMCPlanMant'), "atras(-1)", "noafecta"),
                    array(gettext('sBajarxls'), "sndReq('administracion:preguntasleg:excel:ver','',1)", "noafecta")
                );
                break;
        }
        $iNumeroPaginas = 20;
        $_SESSION['botones'] = $aBotones;
        return (array('fila' => $aDatos[0], 'accion' => $sAccion, 'pagina' => $aDatos[3],
            'order' => $aDatos[4], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones
        ));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @param $sMenuCompleto
     * @return array
     */
    function prepara_Listado_Inicial_Nuevo($sAccion, $aDatos, $sMenuCompleto)
    {
        unset ($_SESSION['where']);
        unset ($_SESSION['ultimolistado']);
        unset ($_SESSION['ultimolistadodatos']);
        unset ($_SESSION['paginaanterior']);
        $_SESSION['ultimolistado'] = array($sMenuCompleto);
        $_SESSION['ultimolistadodatos'] = array($aDatos);
        $_SESSION['paginaanterior'] = array($_SESSION['pagina']);
        if (($aDatos[0] == "undefined") || ($aDatos[0] < 1)) {
            $aDatos[0] = 1;
        }
        if ($aDatos[1] == null) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('botones_idiomas.valor', 'botones.accion', 'tipo_botones.nombre'));
            $oBaseDatos->construir_Tablas(array('botones', 'menu_nuevo', 'botones_idiomas', 'idiomas', 'tipo_botones'));
            $oBaseDatos->construir_Where(array('menu_nuevo.id=botones.menu', 'menu_nuevo.accion=\'' . $sMenuCompleto . '\'',
                'botones_idiomas.boton=botones.id', "botones_idiomas.idioma_id=idiomas.id", "idiomas.id='" . $_SESSION['idiomaid'] . "'",
                'tipo_botones.id=botones.tipo_botones'));
            if ($_SESSION['perfil'] != 0) {
                $oBaseDatos->pon_Where('botones.permisos[' . $_SESSION['perfil'] . ']=true');
            }
            $oBaseDatos->consulta();
            $aBotones = array();
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $aBotones[] = array($aIterador[0], $aIterador[1], $aIterador[2]);
            }
            switch ($sAccion) {
                case 'administracion:idiomas:nuevo':
                    {
                        $aDatos[1] = gettext('sMNIdiomas') . " ASC";
                        break;
                    }
            }
        }
        if ($aDatos[2] == null) {
            $iNumeroPaginas = 20;
        } else {
            $iNumeroPaginas = $aDatos[2];
        }
        $_SESSION['botones'] = $aBotones;
        return (array('accion' => $sAccion, 'pagina' => $aDatos[0],
            'order' => $aDatos[1], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Listado_Inicial_Fila_Medio($sAccion, $aDatos)
    {
        unset ($_SESSION['where']);
        $aDatos[3] = 1;
        switch ($sAccion) {

            case 'documentos:historicocuestionario:nuevo:fila':
                $aDatos[4] = gettext('sMMFecha') . " ASC";
                //echo "alert|".$aDatos[4];
                $aBotones = array(array(gettext('sMMVerPreguntas'), "sndReq('documentos:preguntashistorico:listado:ver:fila','',1)", "fila"),
                    array(gettext('sMMImprimir'), "sndReq('documentos:preguntashistoricoimprimir:listado:ver','',1)", "sincheck"),
                    array(gettext('sMMVolver'), "atras(-1)", "noafecta")
                );
                break;
        }
        $iNumeroPaginas = 20;
        $_SESSION['botones'] = $aBotones;
        return (array('fila' => $aDatos[0], 'accion' => $sAccion, 'pagina' => $aDatos[3],
            'order' => $aDatos[4], 'numLinks' => 5, 'numPaginas' => $iNumeroPaginas, 'botones' => $aBotones
        ));
    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_Ver_HistoricoPreguntas($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'numeroDeFila' => $aDatos[0]));
    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_Ver_HistoricoPreguntasImprimir($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'numeroDeFila' => $aDatos[0]));
    }

    /**
     * @param $aDatos
     * @return array
     */
    function prepara_Listado($aDatos)
    {
        if (($aDatos[1] == "undefined") || ($aDatos[1] < 1)) {
            $aDatos[1] = 1;
        }
        $aWhere = array();
        if ($aDatos[4] == "limpiar") {
            $aWhere = "limpiar";
        } else {
            $aSplitWhere = explode(separadorCadenas, $aDatos[4]);
            for ($iContador = 1; $aSplitWhere[$iContador] != null; $iContador = $iContador + 2) {
                $aWhere[$aSplitWhere[$iContador]] = $aSplitWhere[$iContador + 1];
            }
            $aSplitSelect = explode(separadorCadenas, $aDatos[5]);
            for ($iContador = 1; $aSplitSelect[$iContador] != null; $iContador = $iContador + 2) {
                $aSelect[$aSplitSelect[$iContador]] = $aSplitSelect[$iContador + 1];
            }
        }
        $aTrocear = explode(":", $aDatos[0], 5);
        if ($aTrocear[3]) $aAccionFinal = $aTrocear[0] . ":" . $aTrocear[1] . ":listado:" . $aTrocear[2] . ":" . $aTrocear[3];
        else $aAccionFinal = $aTrocear[0] . ":" . $aTrocear[1] . ":listado:" . $aTrocear[2];
        return (array('accion' => $aAccionFinal, 'pagina' => $aDatos[1], 'order' => $aDatos[2], 'numLinks' => 5,
            'numPaginas' => $aDatos[3], 'where' => $aWhere, 'select' => $aSelect, 'botones' => $_SESSION['botones']));
    }
}