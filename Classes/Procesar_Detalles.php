<?php
namespace Tuqan\Classes;

use \boton;

class Procesar_Detalles
{

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Detalles_Auditorias($sAccion, $aParametros)
    {
        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $oEquipos = new boton(gettext('sPCEquipoAuditor'), "sndReq('auditorias:equipoauditor:listado:ver:fila','',1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        if (($aParametros['numeroDeFila'] == -1) || ($aParametros['numeroDeFila'] == null)) {
            $iIdAuditoria = $_SESSION['auditoria'];
        } else {
            $iIdAuditoria = $_SESSION['pagina'][$aParametros['numeroDeFila']];
            $_SESSION['auditoria'] = $iIdAuditoria;
        }
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('descripcion', 'to_char(fecha, \'DD/MM/YYYY\')', 'observaciones', 'tipo_estado_auditoria.nombre', 'interna',
            'requisitos', 'alcance', 'areas'));
        $oBaseDatos->construir_Tablas(array('auditorias', 'tipo_estado_auditoria'));
        $oBaseDatos->construir_Where(array('auditorias.id=' . $iIdAuditoria, 'auditorias.estado=tipo_estado_auditoria.id'));
        $oBaseDatos->consulta();
        $sHtml='';
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sHtml = "<table id=\"auditoria\">";
            $sHtml .= "<tr><td class=\"tauditoria\" colspan=2>" . gettext('sAudNombre') . "</td></tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudDesc') . " &nbsp;&nbsp;&nbsp;&nbsp; </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[0];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudMes') . " &nbsp;&nbsp;&nbsp;&nbsp; </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[1];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudObserva') . "&nbsp;&nbsp;&nbsp;&nbsp;</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[2];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudEstado') . "&nbsp;&nbsp;&nbsp;&nbsp;</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[3];
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "</table><br /><br /><br /><br />";


            $sHtml .= "<table class=\"plan_auditoria\">";
            $sHtml .= "<tr><td class=\"tauditoria\" colspan=2>" . gettext('sAudPlan') . "</td></tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudTipo') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            if ($aIterador[4] = 't') {
                $sHtml .= gettext('sAudInterna');
            } else {
                $sHtml .= gettext('sAudExterna');
            }
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudRequisito') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[5];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudAlcance') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[6];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sAudAreas') . "</span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[7];
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";
            $sHtml .= "</table>";
        }
        $sHtml .= "<br />" . $oVolver->to_Html() . $oEquipos->to_Html();
        return $sHtml;
    }


    function procesa_Detalles_Revision($sAccion, $aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdMantenimiento = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('to_char(fecha_realiza, \'DD/MM/YYYY\')', 'comentarios', 'motivos', 'tipo'));
        $oBaseDatos->construir_Tablas(array('mantenimientos'));
        $oBaseDatos->construir_Where(array('id=' . $iIdMantenimiento));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sHtml = "<table class=\"ver_docs\">";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sDetFecha') . " </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[0];
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= "<span class=\"campo\">" . gettext('sDetComent') . " </span>";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aIterador[1];
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
            if ($aIterador[3] == "correctivo") {
                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sDetMotivo') . " </span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[2];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
            $sHtml .= "</table>";
        }
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }


    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */

    function procesa_Detalles($sAccion, $aParametros)
    {
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBoton = new boton("Volver", "atras(-1)", "sincheck");
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        switch ($sAccion) {
            case 'formacion:inscripcion:ver:fila':
                $oDb->iniciar_Consulta('SELECT');
                $aCampos = array('cursos.nombre', 'cursos.lugar', 'cursos.num_horas', 'to_char(cursos.fecha_prevista, \'DD/MM/YYYY\')',
                    'to_char(cursos.fecha_prevista,\'hh24 mi\')', 'usuarios.nombre', 'cursos.objetivos', 'cursos.contenido',
                    'cursos.material_necesario', 'cursos.material_suministrado');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('cursos', 'usuarios'));
                $oDb->construir_Where(array('cursos.id=' . $iId, 'cursos.responsable=usuarios.id'));

                break;
            default:
                $sHtml = gettext('sDocError');
        }

        $oDb->consulta();
        $aIterador = $oDb->coger_Fila();
        $sHtml='';
        switch ($sAccion) {
            case 'formacion:inscripcion:ver:fila':
                $sHtml = "<table class=\"documento\" border=0>";
                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoNombre') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[0];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoLugar') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[1];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>"; //td
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoDuracion') . "&nbsp;</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[2];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";


                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoFechaPrev') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[3];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoHoraPrev') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[4];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoResponsable') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[5];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoObj') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[6];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoCont') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[7];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoMaterialN') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[8];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";

                $sHtml .= "<tr>";
                $sHtml .= "<td>";
                $sHtml .= "<span class=\"campo\">" . gettext('sCursoMaterialS') . "</span>";
                $sHtml .= "</td>";
                $sHtml .= "<td>";
                $sHtml .= $aIterador[9];
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
                $sHtml .= "</table>";
        }
        $sHtml .= "<br /><br />" . $oBoton->to_Html();
        $oDb->desconexion();
        return $sHtml;
    }


    function procesa_Detalles_Procesos($sAccion, $aParametros)
    {
        if (($aParametros['numeroDeFila'] != -1) && ($aParametros['numeroDeFila'] != 'undefined')) {
            $_SESSION['detalleproceso'] = $aParametros['numeroDeFila'];
        }
        $iIdProceso = $_SESSION['detalleproceso'];
        //Debo ver si tengo o no ficha
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('contenido_procesos.id', 'documentos.id'));
        $oBaseDatos->construir_Tablas(array('documentos', 'contenido_procesos'));
        $oBaseDatos->construir_Where(array('contenido_procesos.proceso=\'' . $iIdProceso . '\'', 'documentos.id=contenido_procesos.documento', 'documentos.estado<>' . iHistorico));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            //Si existe lo mandamos directamente
            $_SESSION['documentodetalles'] = $aIterador[1];
            $sHtml = "contenedor|" . $this->procesa_Detalles_Documento(-1);
        } else {
            $sHtml = "diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos=catalogo:contenidoproceso:formulario:editar:fila" . separador . $iIdProceso . "\"  width=\"100%\"" .
                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>|alert|" . gettext('sNoFichCrea');

        }
        return $sHtml;
    }


    /**
     * @param null $iFila
     * @param null $sTipo
     * @return string
     */
    function procesa_Detalles_Documento($iFila = null, $sTipo = null)
    {
        $oVolver = new boton(gettext('sPDVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Esto lo hacemos por si volvemos atras de alguna de las opciones, lo guardamos en sesion para que sea accesible
        if ($iFila == -1) {
            if ($sTipo == 'tarea') {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documento'));
                $oBaseDatos->construir_Tablas(array('tareas'));
                $oBaseDatos->construir_Where(array('(id=\'' . $iFila . '\')'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $iDocumento = $aIterador[0];
                } else {
                    $iDocumento = $_SESSION['documentodetalles'];
                }
            } else {
                $iDocumento = $_SESSION['documentodetalles'];
            }
        } else {
            if ($sTipo == 'tarea') {
                $iTarea = $_SESSION['pagina'][$iFila];
                //Sacamos el documento asociado
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documento'));
                $oBaseDatos->construir_Tablas(array('tareas'));
                $oBaseDatos->construir_Where(array('(id=\'' . $iTarea . '\')'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $iDocumento = $aIterador[0];
                } else {
                    $iDocumento = $_SESSION['documentodetalles'];
                }
            } else {
                $iDocumento = $_SESSION['pagina'][$iFila];
                $_SESSION['documentodetalles'] = $iDocumento;
            }
        }

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('documentos.codigo', 'documentos.nombre', 'documentos.revision', 'documentos.estado',
            'documentos.id', 'documentos.revisado_por', 'documentos.aprobado_por', 'tipo_documento'));
        $oBaseDatos->construir_Tablas(array('documentos'));
        $oBaseDatos->construir_Where(array(
                '(documentos.id=\'' . $iDocumento . '\')',
                '(documentos.estado<>' . iHistorico . ')')
        );
        $oBaseDatos->consulta();

        //Aqui metemos el documento en vigor y el borrador en caso de que exista alguno de los dos
        $aVigor = null;
        $aBorra = null;
        //Entramos aqui para coger el documento ya este en borrador o vigor y luego si lo hay su contrapartida en borrador o vigor
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            if ($aIterador[3] == iVigor) {
                $aVigor = array('codigo' => $aIterador[0], 'nombre' => $aIterador[1], 'revision' => $aIterador[2],
                    'estado' => $aIterador[3], 'id' => $aIterador[4], 'revisado' => $aIterador[5],
                    'aprobado' => $aIterador[6], 'tipo' => $aIterador[7]);
            } //Fin if
            else if (($aIterador[3] == iBorrador) || ($aIterador[3] == iPendRevision) || ($aIterador[3] == iRevisado) || ($aIterador[3] == iPendAprobacion)) {
                $aBorra = array('codigo' => $aIterador[0], 'nombre' => $aIterador[1], 'revision' => $aIterador[2],
                    'estado' => $aIterador[3], 'id' => $aIterador[4], 'revisado' => $aIterador[5],
                    'aprobado' => $aIterador[6], 'tipo' => $aIterador[7]);
            }
            //Fin else if

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo', 'documentos.nombre', 'documentos.revision', 'documentos.estado',
                'documentos.id', 'documentos.revisado_por', 'documentos.aprobado_por', 'tipo_documento'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('(documentos.codigo=\'' . $aIterador[0] . '\')',
                '(documentos.nombre=\'' . $aIterador[1] . '\')',
                '(documentos.id<>' . $iDocumento . ')',
                '(documentos.estado<>' . iHistorico . ')'
            ));
            $oBaseDatos->consulta();
            if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                if ($aIteradorInterno[3] == iVigor) {
                    $aVigor = array('codigo' => $aIteradorInterno[0], 'nombre' => $aIteradorInterno[1], 'revision' => $aIteradorInterno[2],
                        'estado' => $aIteradorInterno[3], 'id' => $aIteradorInterno[4], 'revisado' => $aIteradorInterno[5],
                        'aprobado' => $aIteradorInterno[6], 'tipo' => $aIterador[7]);
                } //Fin if
                else if (($aIteradorInterno[3] == iBorrador) || ($aIteradorInterno[3] == iPendRevision) || ($aIteradorInterno[3] == iRevisado) || ($aIteradorInterno[3] == iPendAprobacion)) {
                    $aBorra = array('codigo' => $aIteradorInterno[0], 'nombre' => $aIteradorInterno[1], 'revision' => $aIteradorInterno[2],
                        'estado' => $aIteradorInterno[3], 'id' => $aIteradorInterno[4], 'revisado' => $aIteradorInterno[5],
                        'aprobado' => $aIteradorInterno[6], 'tipo' => $aIterador[7]);
                }
                //Fin else if
            }
            //Fin if
        }

        //Fin if
        $sHtml = "<div id=\"mostrar_doc\"><div class=\"vtitulo\">" . gettext('sVigenteNombre') . "</div>";
        $sHtml .= "<table class=\"vigente\" border=0>";

        //Si tenemos un documento en vigor lo ponemos
        if (is_array($aVigor)) {
            //Primero sacamos el nombre del usuario que ha revisado el documento y del que lo ha aprobado
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
            $oBaseDatos->construir_Tablas(array('usuarios'));
            $oBaseDatos->construir_Where(array('usuarios.id=\'' . $aVigor['revisado'] . '\''));
            $oBaseDatos->consulta();
            $aFila = $oBaseDatos->coger_Fila();
            $sRevision = $aFila[0];
            //Ahora sacamos el del que lo ha aprobado

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
            $oBaseDatos->construir_Tablas(array('usuarios'));
            $oBaseDatos->construir_Where(array('usuarios.id=\'' . $aVigor['aprobado'] . '\''));
            $oBaseDatos->consulta();
            $aFila = $oBaseDatos->coger_Fila();
            $sAprobado = $aFila[0];

            //Ya que tenemos un documento vigor creamos los botones para las acciones
            //Ponemos los botones segun el tipo de documento sea proceso u otro
            if ($aVigor['tipo'] == iIdProceso) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                $oBaseDatos->construir_Where(array('documento=' . $aVigor['id']));
                $oBaseDatos->consulta();
                $aProcesos = $oBaseDatos->coger_Fila();
                $iIdFicha = $aProcesos[0];
                $oFlujo = new boton("Flujograma", "sndReq('catalogo:flujograma:comun:nuevo','',1,'" . $iIdFicha . "')", "noafecta");

                $oVer = new boton(gettext('sPLVer'), "sndReq('catalogo:verdocumentoprocesosinfila:listado:ver','',1,'" . $iIdFicha . "')", "noafecta");
                $oNuevaVersion = new boton(gettext('sPLNuevaVersion'), "sndReq('catalogo:nuevaversionproceso:comun:nuevo','',1,'" . $aVigor['id'] . separador . $iIdFicha . "')", "noafecta");
                $oToPdf = new boton("Imprimir", "sndReq('imprimirproceso','',1,'" . $iIdFicha . "')", "noafecta");

            } /*    else if($aVigor['tipo']==iIdExterno)
                        {
                            $oVer=new boton(gettext('sPCOVer'),"sndReq('ver:docextid','',1)","noafecta");
                            
                        }*/
            else {
                $oVer = new boton(
                    gettext('sPLVer'),
                    "sndReq('documentacion:general:comun:verdocumento','',1,'" . $aVigor['id'] . "')",
                    "noafecta"
                );
                $oNuevaVersion = new boton(
                    gettext('sPLNuevaVersion'),
                    "sndReq('documentacion:general:comun:nuevaversion','',1,'" . $aVigor['id'] . separador . $aVigor['tipo'] . "')",
                    "noafecta"
                );
                //$oToPdf=new boton("Imprimir","sndReq('imprimirdocvigor','',1,'".$aVigor['id'].separador.$iIdFicha."')","noafecta");
            }
            //Ponemos las variables necesarias para el historico, esto lo hacemos tambien en el borrador por si no hay doc en vigor
            $sCod = $aVigor['codigo'];
            $sNom = $aVigor['nombre'];


            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sDocumento') . "</td>";
            $sHtml .= "<td>" . $aVigor['codigo'] . " " . $aVigor['nombre'] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sDocRevision') . "</td>";
            $sHtml .= "<td>" . $aVigor['revision'] . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sDocRevisado') . "</td>";
            $sHtml .= "<td>" . $sRevision . "</td>";
            $sHtml .= "</tr>";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">" . gettext('sDocAprobar') . "</td>";
            $sHtml .= "<td>" . $sAprobado . "</td>";
            $sHtml .= "</tr><br />";

            $sHtml .= "<tr>";
            $sHtml .= "<td class=\"campo\">";
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_ver[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aVigor['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oVer->to_Html();
                if ($aVigor['tipo'] != iIdExterno) {
                    //Aadido PRUEBAS PRUEBAS

                    //    $sHtml.=$oFlujo->to_Html();
                    //Aadido PRUEBAS PRUEBAS
                    //$sHtml.=$oToPdf->to_Html();
                }
            }

            //    if($aVigor['tipo']!=iIdExterno)
            //    {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array(
                    'documentos.perfil_nueva[' . $_SESSION['perfil'] . ']=true',
                    'documentos.id=' . $aVigor['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oNuevaVersion->to_Html();
            }

            //$sHtml.=$oToPdf->to_Html();
            //    }
            $sHtml .= "</td>";
            $sHtml .= "</tr><br />";

        } else {
            $sHtml .= "<tr><td>" . gettext('sNoVersionVigor') . "</td></tr><br />";
        }


        $sHtml .= "</table><br /><tr>";

        $sHtml .= "<div class=\"tborrador\"><b>" . gettext('sDocBorrador') . "</b></div>";
        $sHtml .= "<table class=\"borrador\">";
        Config::initialize();

        //$sHtml.="</tr><br />";
        //Si tenemos un documento en borrador lo ponemos
        if (is_array($aBorra)) {
            //Ponemos las variables necesarias para el historico, esto lo hacemos tambien en el borrador por si no hay doc en vigor
            $sCod = $aBorra['codigo'];
            $sNom = $aBorra['nombre'];
            //Creamos los botones

            //Segun el tipo de documento que sea ponemos el boton
            if ($aBorra['tipo'] == iIdHt) {
                $sAccion = 'editar:documentoht';
            } else if ($aBorra['tipo'] == iIdPg) {
                $sAccion = 'editar:documentopg';
            } else if ($aBorra['tipo'] == iIdIt) {
                $sAccion = 'editar:documentoit';
            } else if ($aBorra['tipo'] == iIdExterno) {
                $sAccion = 'editar:documentoexterno';
            }
            if ($aBorra['tipo'] == iIdProceso) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                $oBaseDatos->construir_Where(array('documento=' . $aBorra['id']));
                $oBaseDatos->consulta();
                $aProcesos = $oBaseDatos->coger_Fila();
                $iIdFicha = $aProcesos[0];
                $oEditar = new boton(gettext('sPCOEditar'), "sndReq('catalogo:contenidoproc:formulario:editar','',1,'" . $iIdFicha . "')", "noafecta");
                $oRevisar = new boton(gettext('sPCORevisar'), "sndReq('documentacion:general:comun:revisardocumento','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
                $oAprobar = new boton(gettext('sPCOAprobar'), "sndReq('documentacion:general:comun:aprobardocumento','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
                $oTareas = new boton(gettext('sPCOTareas'), "sndReq('documentacion:general:comun:asignatarea','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
                $oVerBorra = new boton(gettext('sPCOVer'), "sndReq('catalogo:verdocumentoprocesosinfilaborrador:listado:ver:fila','',1,'" . $iIdFicha . "')", "noafecta");
                $oDoc = new boton("Documentos Anexos", "sndReq('catalogo:documentosproceso:listado:ver','',1,'" . $iIdFicha . "')", "noafecta");
                $oFlujo = new boton("Flujograma", "sndReq('catalogo:flujograma:comun:nuevo','',1,'" . $iIdFicha . "')", "noafecta");
                $oArea = new boton("Area", "sndReq('catalogo:areadoc:listado:nuevo','',1)", "noafecta");
            } else {
                $oVerBorra = new boton(gettext('sPCOVer'), "sndReq('documentacion:general:comun:verdocumentosinfila','',1,'" . $aBorra['id'] . "')", "noafecta");
                $oEditar = new boton(gettext('sPCOEditar'), "sndReq('documentossg:general:comun:documento:editadocumentosinfila','',1,'" . $aBorra['tipo'] . separador . $aBorra['id'] . "')", "noafecta");
                $oRevisar = new boton(gettext('sPCORevisar'), "sndReq('documentacion:general:comun:revisardocumento','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
                $oAprobar = new boton(gettext('sPCOAprobar'), "sndReq('documentacion:general:comun:aprobardocumento','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
                $oTareas = new boton(gettext('sPCOTareas'), "sndReq('documentacion:general:comun:asignatarea','',1,'" . $aBorra['id'] . separador . $_SESSION['userid'] . "')", "noafecta");
            }
            $sHtml .= "<tr>";
            //    $sHtml.="<td>";
            //        $sHtml.="<tr>";
            $sHtml .= "<td><span class=\"campo\">" . gettext('sDocumento') . " &nbsp;&nbsp;&nbsp;&nbsp; </span>" . $aBorra['codigo'] . " " . $aBorra['nombre'] . "</td>";
            //$sHtml.="<td>".$aBorra['codigo']." ".$aBorra['nombre']."</td>";
            $sHtml .= "</tr>";

            //Aqui ponemos el estado del borrador
            if ($aBorra['revisado'] != null) {
                //En este caso es que esta revisado, sacamos el nombre del que lo ha hecho
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
                $oBaseDatos->construir_Tablas(array('usuarios'));
                $oBaseDatos->construir_Where(array('usuarios.id=\'' . $aBorra['revisado'] . '\''));
                $oBaseDatos->consulta();
                $aFila = $oBaseDatos->coger_Fila();
                $sRevisionBorra = $aFila[0];

                $sHtml .= "<tr>";
                $sHtml .= "<td><span class=\"campo\">" . gettext('sDocRevisado') . "&nbsp;&nbsp;&nbsp;&nbsp;</span>" . $sRevisionBorra . "</td>";
                //    $sHtml.="<td>".$sRevisionBorra."</td>";
                $sHtml .= "</tr>";

            } else {
                $sHtml .= "<td>" . gettext('sDocNoRevisado') . "<br />";
            }
            $sHtml .= "</td>";

            $sHtml .= "</tr>";
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            //Permisos de VER
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_ver[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aBorra['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oVerBorra->to_Html();
            }
            //Permisos MODIFICAR
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_modificar[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aBorra['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oEditar->to_Html();
                if ($aBorra['tipo'] == iIdProceso) {
                    $sHtml .= $oDoc->to_Html();
                    $sHtml .= $oFlujo->to_Html();
                    $sHtml .= $oArea->to_Html();
                }
            }
            //PERMISOS REVISAR
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_revisar[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aBorra['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oRevisar->to_Html();
            }
            //PERMISOS APROBAR
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_aprobar[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aBorra['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oAprobar->to_Html();
            }
            //PERMISOS TAREAS
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_tareas[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aBorra['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oTareas->to_Html();
            }

            $sHtml .= "</td>";
            $sHtml .= "</tr>";
        } else {
            $sHtml .= "<tr>" . gettext('sNoBorrador') . "</td></tr><br />";
        }

        $sHtml .= "</table><br /><br />";

        //Creamos el boton para el historico
        $oVerHistorico = new boton("Ver Historico", "sndReq('documentacion:general:listado:verhistorico','',1,'" . $sCod . separador . $sNom . "')", "noafecta");

        $sHtml .= "<div class=\"thistorico\">";
        $sHtml .= gettext('sHistorico') . "</div><br />";
        //$sHtml.="</tr><br />";

        $sHtml .= "<table class=\"documento\"><tr>";
        $sHtml .= "<td class=\"campo\">";
        //PERMISOS HISTORICO
        if (is_array($aVigor)) {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            if ($_SESSION['userid'] != 0) {
                $oBaseDatos->construir_Where(array('documentos.perfil_historico[' . $_SESSION['perfil'] . ']=true', 'documentos.id=' . $aVigor['id']));
            }
            $oBaseDatos->consulta();
            if ($aFila = $oBaseDatos->coger_Fila()) {
                $sHtml .= $oVerHistorico->to_Html();
            }
        }
        $sHtml .= "</td>";
        $sHtml .= "</tr>";

        $sHtml .= "</table><br /><br /></div>";
        $sHtml .= $oVolver->to_Html();
        $oBaseDatos->desconexion();
        return $sHtml;

    }

    function procesa_Detalles_MatrizMA()
    {
        $oVolver = new boton(gettext('sPDVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        Config::initialize();
        $sHtml = "<div align='center'><h1>MATRIZ ASPECTOS AMBIENTALES</h1><br /><table border=1>";
        $sHtml .= "<tr>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrNombre');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrTipo');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrImpacto');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrArea');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrCantidad');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrSeveridad');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrFrecuncia');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrSeverEmer');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrProbabilidad');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrSignificante');
        $sHtml .= "</th>";
        $sHtml .= "<th>";
        $sHtml .= gettext('sMatrValoracion');
        $sHtml .= "</th>";
        $sHtml .= "</tr>";

        /**
         * Primero cogemos los aspectos normales (no de emergencia)
         */

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('formula'));
        $oBaseDatos->construir_Tablas(array('formula_aspectos'));
        $oBaseDatos->construir_Where(array('id=1'));
        $oBaseDatos->consulta();

        $aDevolver = $oBaseDatos->coger_Fila();
        $sFormulaMatrizAmbientales = $aDevolver[0];
        if ($_SESSION['areasactivadas']) {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('aspectos.nombre', 'tipo_aspectos.nombre', 'tipo_impactos_idiomas.valor', "areas.nombre as \"" . gettext('sPMArea') . "\"",
                'tipo_magnitud_idiomas.valor', 'tipo_gravedad_idiomas.valor', 'tipo_frecuencia_idiomas.valor', 'pp.significancia',
                "case when pp.significancia<15 then '" . gettext('sPMNoSignificativo') . "' else '" . gettext('sPMSignificativo') . "' end as \"" . gettext('sPMValoracion') . "\"",
            ));
            $oBaseDatos->construir_Tablas(array('aspectos', 'tipo_aspectos', 'tipo_impactos', 'tipo_magnitud', 'tipo_gravedad', 'tipo_frecuencia', 'areas',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_frecuencia, tipo_magnitud, tipo_gravedad ' .
                'where aspectos.frecuencia=tipo_frecuencia.id AND aspectos.magnitud=tipo_magnitud.id AND aspectos.gravedad=tipo_gravedad.id)as pp',
                'tipo_impactos_idiomas', 'tipo_frecuencia_idiomas', 'tipo_gravedad_idiomas', 'tipo_magnitud_idiomas'
            ));
            $oBaseDatos->construir_Where(array('aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.tipo_aspecto<>3', 'aspectos.impacto=tipo_impactos.id',
                'aspectos.magnitud=tipo_magnitud.id', 'aspectos.gravedad=tipo_gravedad.id',
                'aspectos.frecuencia=tipo_frecuencia.id', 'aspectos.id=pp.id',
                'aspectos.area_id=areas.id',
                'aspectos.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia', 'tipo_frecuencia_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_gravedad.id=tipo_gravedad_idiomas.gravedad', 'tipo_gravedad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_magnitud.id=tipo_magnitud_idiomas.magnitud', 'tipo_magnitud_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
        } else {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('aspectos.nombre', 'tipo_aspectos.nombre', 'tipo_impactos_idiomas.valor', 'aspectos.area',
                'tipo_magnitud_idiomas.valor', 'tipo_gravedad_idiomas.valor', 'tipo_frecuencia_idiomas.valor', 'pp.significancia',
                "case when pp.significancia<15 then '" . gettext('sPMNoSignificativo') . "' else '" . gettext('sPMSignificativo') . "' end as \"" . gettext('sPMValoracion') . "\""
            ));
            $oBaseDatos->construir_Tablas(array('aspectos', 'tipo_aspectos', 'tipo_impactos', 'tipo_magnitud', 'tipo_gravedad', 'tipo_frecuencia',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_frecuencia, tipo_magnitud, tipo_gravedad ' .
                'where aspectos.frecuencia=tipo_frecuencia.id AND aspectos.magnitud=tipo_magnitud.id AND aspectos.gravedad=tipo_gravedad.id)as pp',
                'tipo_impactos_idiomas', 'tipo_frecuencia_idiomas', 'tipo_gravedad_idiomas', 'tipo_magnitud_idiomas'
            ));
            $oBaseDatos->construir_Where(array('aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.tipo_aspecto<>3', 'aspectos.impacto=tipo_impactos.id',
                'aspectos.magnitud=tipo_magnitud.id', 'aspectos.gravedad=tipo_gravedad.id',
                'aspectos.frecuencia=tipo_frecuencia.id', 'aspectos.id=pp.id',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia', 'tipo_frecuencia_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_gravedad.id=tipo_gravedad_idiomas.gravedad', 'tipo_gravedad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_magnitud.id=tipo_magnitud_idiomas.magnitud', 'tipo_magnitud_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
        }
        $oBaseDatos->consulta();
        while ($aFila = $oBaseDatos->coger_Fila()) {
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[0];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[1];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[2];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[3];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[4];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[5];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[6];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[7];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[8];
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
        }
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('formula'));
        $oBaseDatos->construir_Tablas(array('formula_aspectos'));
        $oBaseDatos->construir_Where(array('id=2'));
        $oBaseDatos->consulta();

        $aDevolver = $oBaseDatos->coger_Fila();
        $sFormulaMatrizAmbientales = $aDevolver[0];
        $oBaseDatos->iniciar_Consulta('SELECT');
        if ($_SESSION['areasactivadas']) {
            $oBaseDatos->construir_Campos(array('aspectos.nombre',
                'tipo_aspectos.nombre',
                'tipo_impactos_idiomas.valor',
                "areas.nombre as \"" . gettext('sPMArea') . "\"",
                'tipo_severidad_idiomas.valor',
                'tipo_probabilidad_idiomas.valor',
                'pp.significancia',
                "case when pp.significancia<".Config::$iValorRiesgoBajo." then '" .gettext('sPMNoSignificativo').
                "' when pp.significancia<".Config::$iValorRiesgoMedio ."and pp.significancia >=".Config::$iValorRiesgoBajo." then '" .
                gettext('sPMRiesgoBajo') . "' when pp.significancia<".Config::$iValorRiesgoAlto." and".
                " pp.significancia >=".Config::$iValorRiesgoMedio." then '" .
                gettext('sPMRiesgoMedio') ."' else '" . gettext('sPMRiesgoAlto') . "' end as \"" . gettext('sPMValoracion') . "\""
            ));
            $oBaseDatos->construir_Tablas(array('aspectos', 'tipo_aspectos', 'tipo_impactos', 'tipo_probabilidad', 'tipo_severidad', 'areas',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_severidad, tipo_probabilidad ' .
                'where aspectos.severidad=tipo_severidad.id AND aspectos.probabilidad=tipo_probabilidad.id)as pp',
                'tipo_impactos_idiomas', 'tipo_severidad_idiomas', 'tipo_probabilidad_idiomas'
            ));
            $oBaseDatos->construir_Where(array('aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.tipo_aspecto=3', 'aspectos.impacto=tipo_impactos.id',
                'aspectos.severidad=tipo_severidad.id',
                'aspectos.probabilidad=tipo_probabilidad.id', 'aspectos.id=pp.id',
                'aspectos.area_id=areas.id',
                'aspectos.area_id=' . $_SESSION['areausuario'] . ' OR ' . $_SESSION['areausuario'] . '=0',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_severidad.id=tipo_severidad_idiomas.severidad', 'tipo_severidad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad', 'tipo_probabilidad_idiomas.idioma_id=' . $_SESSION['idiomaid']

            ));
        } else {
            $oBaseDatos->construir_Campos(array('aspectos.nombre', 'tipo_aspectos.nombre', 'tipo_impactos_idiomas.valor', 'aspectos.area',
                'tipo_severidad_idiomas.valor', 'tipo_probabilidad_idiomas.valor', 'pp.significancia',
                "case when pp.significancia<".Config::$iValorRiesgoBajo." then '" . gettext('sPMNoSignificativo') .
                "' when pp.significancia<".Config::$iValorRiesgoMedio." and pp.significancia >=".Config::$iValorRiesgoBajo." then '" .
                gettext('sPMRiesgoBajo') . "' when pp.significancia<".Config::$iValorRiesgoAlto
                ." and pp.significancia >=".Config::$iValorRiesgoMedio." then '" .
                gettext('sPMRiesgoMedio') . "' else '" . gettext('sPMRiesgoAlto') . "' end as \"" . gettext('sPMValoracion') . "\""
            ));
            $oBaseDatos->construir_Tablas(array('aspectos', 'tipo_aspectos', 'tipo_impactos', 'tipo_probabilidad', 'tipo_severidad',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_severidad, tipo_probabilidad ' .
                'where aspectos.severidad=tipo_severidad.id AND aspectos.probabilidad=tipo_probabilidad.id)as pp',
                'tipo_impactos_idiomas', 'tipo_severidad_idiomas', 'tipo_probabilidad_idiomas'
            ));
            $oBaseDatos->construir_Where(array('aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.tipo_aspecto=3',
                'aspectos.impacto=tipo_impactos.id',
                'aspectos.severidad=tipo_severidad.id',
                'aspectos.probabilidad=tipo_probabilidad.id', 'aspectos.id=pp.id',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_severidad.id=tipo_severidad_idiomas.severidad', 'tipo_severidad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad', 'tipo_probabilidad_idiomas.idioma_id=' . $_SESSION['idiomaid']

            ));
        }
        $oBaseDatos->consulta();
        while ($aFila = $oBaseDatos->coger_Fila()) {
            $sHtml .= "<tr>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[0];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[1];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[2];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[3];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= "";
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[4];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[5];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[6];
            $sHtml .= "</td>";
            $sHtml .= "<td>";
            $sHtml .= $aFila[7];
            $sHtml .= "</td>";
            $sHtml .= "</tr>";
        }
        $sHtml .= "</table>";
        $sHtml .= $oVolver->to_Html();
        $sHtml .= "</div>";
        return $sHtml;
    }
}