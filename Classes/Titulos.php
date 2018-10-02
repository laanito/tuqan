<?php
namespace Tuqan\Classes;

/**
* LICENSE see LICENSE.md file
 *
 * Este archivo contiene la informacion de la barra de titulos
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 0.1.2a
 */

Class Titulos {
    
    private $aTitulos;
    
    public function __construct()
    {
        $this->aTitulos = array('qnovainicial' => gettext('sTInicio'),
            'inicio:mensajes:listado:inicial' => gettext('sInicio'),
            'inicio:nuevo:formulario:general' => gettext('sInicio'),
            'inicio' => gettext('sInicio'),
            'documentacion' => gettext('sTDocumentacion'),
            'proveedores' => gettext('sTProveedores'),
            'mejora' => gettext('sTAccionesMejora'),
            'formacion' => gettext('sTFormacion'),
            'equipos' => gettext('sTEquipos'),
            'manual' => gettext('sTManual'),
            'auditorias' => gettext('sTAuditorias'),
            'procesos' => gettext('sTProcesos'),
            'indicadores' => gettext('sTIndicadores'),
            'logout' => gettext('sTLogout'),
            'mlogout' => gettext('sTLogout2'),
            'inicio:mensajes:listado:ver' => gettext('sTMensajes'),
            'inicio:mensajes:formulario:nuevo' => gettext('sTNMensajes'),
            'inicio:mensajes:comun:estadisticas' => gettext('sTEstMensajes'),
            'inicio:mensajes:comun:baja:general' => gettext('sTBajaMensajes'),
            'inicio:mensajes:listado:ver:fila' => gettext('sTVerMensajes'),
            'inicio:historicomensajes:listado:ver' => gettext('sTVerHistorico'),

            'inicio:tareas:listado:ver' => gettext('sTTareas'),
            'inicio:tareas:comun:ver:fila' => gettext('sTVerTareas'),
            'inicio:documentoid:detalles:ver:fila' => gettext('sTTareasDoc'),

            //Proveedores
            'proveedores:listado:listado:ver' => gettext('sPListado'),
            'proveedores:proveedor:formulario:nuevo' => gettext('sPNuevo'),
            'proveedores:proveedor:formulario:editar:fila' => gettext('sPEditar'),
            'proveedores:proveedores:comun:baja:general' => gettext('sPBaja'),
            'proveedores:productos:listado:ver:fila' => gettext('sPProductos'),
            'proveedores:producto:formulario:nuevo' => gettext('sPProducNuevo'),
            'proveedores:producto:formulario:editar:fila' => gettext('sPProducEditar'),
            'proveedores:productos:listado:criterios:fila' => gettext('sPProducCriterios'),
            'proveedores:criterio:listado:nuevo' => gettext('sPProductCriteNuevo'),
            'proveedores:producto:comun:revisar:fila' => gettext('sPProductCriteRevisar'),
            'proveedores:productos:listado:productoshistorico:fila' => gettext('sPProductCriteHistorico'),

            'proveedores:contactos:listado:ver:fila' => gettext('sPContactos'),
            'proveedores:contacto:formulario:nuevo' => gettext('sPContactoNuevo'),
            'proveedores:contacto:listado:ver:fila' => gettext('sPContactoVer'),
            'proveedores:contacto:formulario:editar:fila' => gettext('sPContactoEditar'),

            'proveedores:incidencias:listado:ver:fila' => gettext('sPIncidencias'),
            'proveedores:incidencia:formulario:nuevo' => gettext('sPIncidenNuevo'),
            'proveedores:incidencia:formulario:editar:fila' => gettext('sPIncidenEditar'),
            'proveedores:incidencia:listado:general:ver' => gettext('sPIncidenVer'),

            'proveedores:incidencias:listado:ver' => gettext('sPIncidencias'),
            'proveedores:incidenciafila:formulario:nuevo' => gettext('sPIncidenNuevo'),
            'proveedores:incidenciafila:formulario:editar:fila' => gettext('sPIncidenEditar'),
            'proveedores:incidencia:listado:ver:fila' => gettext('sPIncidenVer'),

            'proveedores:contactos:listado:ver' => gettext('sPContactos'),
            'proveedores:contactosfila:formulario:nuevo' => gettext('sPContactoNuevo'),
            'proveedores:contactosfila:formulario:editar:fila' => gettext('sPContactoEditar'),

            'proveedores:productos:listado:ver' => gettext('sPProductos'),
            'proveedores:productofila:formulario:nuevo' => gettext('sPProducNuevo'),
            'proveedores:productofila:formulario:editar:fila' => gettext('sPProducEditar'),

            'proveedores:phomologados:listado:ver' => gettext('sPProducHomologados'),
            'proveedores:proveedor:listado:crear:ver' => gettext('sPProducHomoVer'),


            //Equipos

            'equipos:listado:listado:ver' => gettext('sEquiListado'),
            'equipos:equipo:formulario:nuevo' => gettext('sEquiNuevo'),
            'equipos:equipo:listado:ver:fila' => gettext('sEquiVer'),
            'equipos:equipo:formulario:editar:fila' => gettext('sEquiEditar'),
            'equipos:planmantenimiento:listado:nuevo:fila' => gettext('sEquiPlanMantenimiento'),
            'equipos:mantenimientoprev:formulario:nuevo' => gettext('sEquiPlanManPreNuevo'),
            'equipos:mantenimientocorr:formulario:nuevo' => gettext('sEquiPlanManCorrNuevo'),
            'equipos:mantenimiento:detalles:ver:fila' => gettext('sEquiPlanManVer'),
            'equipos:mantenimientoprev:formulario:editar:fila' => gettext('sEquiManPreEditar'),
            'equipos:mantenimientocorr:formulario:editar:fila' => gettext('sEquiManCorrEditar'),

            'equipos:revision:listado:ver' => gettext('sEquiRevisiones'),
            'equipos:planmantenimiento:listado:ver:fila' => gettext('sEquiPlanManListado'),

            'equipos:calendario:listado:ver' => gettext('sEquiCalendario'),


            //Procesos

            'catalogo:proceso:formulario:nuevo:radio' => gettext('sProcNuevo'),
            'catalogo:proceso:formulario:editar:radio' => gettext('sProcEditar'),
            'catalogo:proceso:detalles:ver:radio' => gettext('sProcDetalles'),
            'catalogo:fichaproceso:listado:ver:radio' => gettext('sProcFicha'),
            'catalogo:proceso:listado:vermatriz:radio' => gettext('sProcMatriz'),
            'procesos:catalogo:comun:baja:radio' => gettext('sProcBaja'),
            'catalogo:indicadores:listado:ver:radio' => gettext('sProcIndiciadores'),
            'catalogo:indicadoresproceso:listado:nuevo' => gettext('sProcIndNuevo'),
            'catalogo:verdocumentoprocesosinfila:listado:ver' => gettext('sProcDetVer'),
            'catalogo:contenidoproc:formulario:editar' => gettext('sProcDetEditar'),
            'catalogo:documentosproceso:listado:ver' => gettext('sProdDetDoc'),
            'catalogo:documentoproceso:listado:nuevo' => gettext('sProdDetDocListado'),
            'catalogo:flujograma:comun:nuevo' => gettext('sProdDetFlujo'),
            'catalogo:areadoc:listado:nuevo' => gettext('sProdDetArea'),
            'documentacion:general:comun:revisardocumento' => gettext('sProdDetRev'),
            'documentacion:general:comun:aprobardocumento' => gettext('sProdDetAprobar'),

            'catalogo:fichaproceso:comun:ver:radio' => gettext('sProdVerFicha'),
            'catalogo:valoresindicador:listado:editar:fila' => gettext('sProdIndValores'),
            'catalogo:valor:formulario:nuevo' => gettext('sProdIndValoresIns'),
            'catalogo:valorunico:formulario:nuevo' => gettext('sProdIndValIns'),
            'catalogo:valor:formulario:editar:fila' => gettext('sProdIndValEditar'),
            'catalogo:objetivosindicador:listado:editar:fila' => gettext('sProdIndObj'),
            'catalogo:objindicador:formulario:nuevo' => gettext('sProdIndObjNuevo'),
            'catalogo:objindicador:formulario:editar:fila' => gettext('sProdIndObjEditar'),
            'catalogo:graficaindicador:listado:ver:fila' => gettext('sProdIndObjGrafica'),
            'catalogo:indicadoresproceso:comun:baja:general' => gettext('sProdIndEliminar'),


            //Documentacion
            'documentacion:manual:listado:ver' => gettext('sTManual'),
            'documentacion:general:listado:verhistorico' => gettext('sTDocVerHistorico'),
            'documentacion:general:comun:asignatarea' => gettext('sTDocGeneralAsigTarea'),
            'documentacion:documentopg:listado:verpermisos:fila' => gettext('sTDocPgPermisos'),
            'documentacion:pe:listado:ver' => gettext('sTDocPoListado'),
            'documentacion:documentope:formulario:nuevo' => gettext('sTDocPoNuevo'),
            'documentacion:documentope:detalles:ver:fila' => gettext('sTDocPoDetalles'),
            'documentacion:documentope:listado:verpermisos:fila' => gettext('sTDocPoPermisos'),
            'documentacion:docvigor:listado:ver' => gettext('sTDocVigorListado'),
            'documentacion:vigor:detalles:ver:fila' => gettext('sTDocVigorDetalles'),
            'documentacion:docborrador:listado:ver' => gettext('sTDocBorradorListado'),
            'documentacion:borrador:detalles:ver:fila' => gettext('sTDocBorradorDetalles'),
            'documentacion:documentoaai:formulario:nuevo' => gettext('sTDocAAINuevo'),
            'documentos:documentoaai:detalles:ver:fila' => gettext('sTDocAAIDetalles'),
            'documentacion:documentoaai:listado:verpermisos:fila' => gettext('sTDocAAIPermisos'),
            'documentacion:planamb:listado:ver' => gettext('sTDocPlanambListado'),
            'documentacion:planemeramb:formulario:nuevo' => gettext('sTDocPlanemerambNuevo'),
            'documentacion:planemeramb:detalles:ver:fila' => gettext('sTDocPlanemerambVer'),
            'documentacion:planemeramb:listado:verpermisos:fila' => gettext('sTDocPlanemerambPermisos'),
            'documentacion:frl:listado:ver' => gettext('sTDocFRLListado'),
            'documentacion:aai:listado:ver' => gettext('sTDocAAIListado'),
            'documentacion:manual:listado:verpermisos:fila' => gettext('sTDocVerPermisos'),
            'documentacion:manual:formulario:nuevo' => gettext('sTNuevoManual'),
            'documentos:manual:comun:ver:fila' => gettext('sTVerManual'),
            'documentacion:manual:detalles:ver:fila' => gettext('sTVerDetallesManual'),
            'documentossg:general:comun:documento:editadocumentosinfila' => gettext('sTVerDetallesEditarManual'),
            'documentacion:pambiental:formulario:nuevo' => gettext('sTDocPoliticaNuevo'),
            'documentacion:pambiental:detalles:ver:fila' => gettext('sTDocPoliticaVer'),
            'documentacion:pambiental:listado:verpermisos:fila' => gettext('sTDocPoliticaPermisos'),
            'documentacion:objetivos:formulario:nuevo' => gettext('sTDocObjNuevo'),
            'documentacion:objetivos:formulario:editar:fila' => gettext('sTDocObjEditar'),
            'documentacion:objetivos:listado:seguimiento:fila' => gettext('sTDocObjSeguimiento'),
            'documentacion:seguimiento:formulario:nuevo' => gettext('sTDocObjSeguimientoNuevo'),
            'documentacion:pg:listado:ver' => gettext('sTDocPgListado'),
            'documentacion:general:comun:nuevaversion' => gettext('sTDocPgNueVersion'),
            'documentacion:objetivos:comun:revisar:fila' => gettext('sTDocSgRevision'),
            'documentacion:objetivos:comun:aprobar:fila' => gettext('sTDocSgAprobar'),
            'documentacion:metas:listado:ver:fila' => gettext('sTDocMetas'),
            'documentacion:meta:formulario:nuevo' => gettext('sTDocMetasNueva'),
            'documentacion:objetivos:comun:nuevaversion:fila' => gettext('sTDocSgNuevaVersion'),
            'documentacion:legislacion:listado:verley:fila' => gettext('sTDocLegLisVerLey'),
            'documentacion:listadoregistros:listado:ver:fila' => gettext('sTDocLegRegList'),

            'documentacion:documentopg:formulario:nuevo' => gettext('sTDocPgNuevo'),
            'documentacion:documentopg:detalles:ver:fila' => gettext('sTDocPgVer'),
            'documentacion:progaudit' => gettext('sTProgAudit'),
            'documentacion:procesos' => gettext('sTDocProceso'),
            'documentacion:listadoregistros' => gettext('sTListRegistro'),
            'documentacion:externa' => gettext('sTDocExterna'),
            'documentacion:politica:listado:ver' => gettext('sTDocPolitica'),
            'documentacion:objetivos:listado:ver' => gettext('sTDocObjetivos'),
            'documentacion:legislacion:listado:ver' => gettext('sTDocLegisList'),
            'documentacion:documentonormativa:formulario:nuevo' => gettext('sTDocLegisNuevo'),
            'documentacion:normativa:listado:ver' => gettext('sTDocNormList'),
            'documentacion:registros:listado:ver' => gettext('sTDocAsoRegList'),
            'documentacion:docformatos:listado:ver' => gettext('sTDocAsoFormList'),
            'documentacion:frl:formulario:nuevo' => gettext('sTDocFrlNuevo'),
            'documentacion:frl:detalles:ver:fila' => gettext('sTDocFrlDetalles'),
            'documentacion:frl:listado:verpermisos:fila' => gettext('sTDocFrlPermisos'),
            'documentacion:legislacion:formulario:nuevo' => gettext('sTDocLegNuevo'),
            'documentacion:legislacion:formulario:editar:fila' => gettext('sTDocLegEditar'),
            'documentos:cuestionario:formulario:nuevo:fila' => gettext('sTDocLegCuestNuevo'),
            'documentacion:documentonormativa:detalles:ver:fila' => gettext('sTDocLegNormVer'),
            'documentacion:formatos:formulario:nuevo' => gettext('sTDocAsoFormNuevo'),
            'documentacion:formatos:detalles:ver:fila' => gettext('sTDocAsoFormDetalles'),
            'documentacion:formatos:listado:verpermisos:fila' => gettext('sTDocAsoFormPermisos'),

            //acc.mejora
            'mejora:listado:listado:ver' => gettext('sTMejoraListado'),
            'mejora:acmejora:formulario:nuevo' => gettext('sTMejoraNuevo'),
            'mejora:acmejora:formulario:editar:fila' => gettext('sTMejoraEditar'),
            'mejora:acmejora:comun:seleccionausuario' => gettext('sTMejoraSelecUsuario'),

            //formacion
            'formacion:fichapersonal:listado:ver' => gettext('sTFormFichListado'),
            'formacion:fichapersonal:formulario:nuevo' => gettext('sTFormFichaNuevo'),
            'formacion:fichapersonal:formulario:editar:fila' => gettext('sTFormFichaEditar'),
            'formacion:fpdp:formulario:editar:fila' => gettext('sTFormFichaDatosPer'),
            'formacion:fpinc:formulario:editar:fila' => gettext('sTFormFichaInc'),
            'formacion:fpfor:formulario:editar:fila' => gettext('sTFormFichaFor'),
            'formacion:fppre:formulario:editar:fila' => gettext('sTFormFichaPreForm'),
            'formacion:fpidiomas:formulario:editar:fila' => gettext('sTFormFichaIdiomas'),
            'formacion:fpcursos:formulario:editar:fila' => gettext('sTFormFichaCursos'),
            'formacion:fpft:formulario:editar:fila' => gettext('sTFormFichaFormacionTec'),
            'formacion:fpel:formulario:editar:fila' => gettext('sTFormFichaExp'),
            'formacion:fpcp:formulario:editar:fila' => gettext('sTFormFichaCambPerfil'),
            'formacion:fpcd:formulario:editar:fila' => gettext('sTFormFichaCambDept'),
            'formacion:reqpuesto:formulario:nuevo' => gettext('sTFormReqPuestoNuevo'),
            'formacion:reqpuesto:listado:ver' => gettext('sTFormReqpuestoListado'),
            'formacion:cursos:listado:ver' => gettext('sTFormCursosListado'),
            'formacion:cursos:formulario:nuevo' => gettext('sTFormCursosNuevo'),
            'formacion:cursos:formulario:editar:fila' => gettext('sTFormCursosEditar'),
            'formacion:inscripcion:listado:asistentes:fila' => gettext('sTFormInscAsistentes'),
            'formacion:inscripcion:detalles:ver:fila' => gettext('sTFormInscDetalles'),
            'formacion:inscripcion:comun:alta:fila' => gettext('sTFormInscAlta'),
            'formacion:inscripcion:listado:ver' => gettext('sTFormInscripcionListado'),
            'formacion:planes:listado:ver' => gettext('sTFormPlanesListado'),
            'formacion:planes:formulario:nuevo' => gettext('sTFormPlanesNuevo'),
            'formacion:planes:formulario:editar:fila' => gettext('sTFormPlanesEditar'),
            'formacion:planes:listado:ver:fila' => gettext('sTFormPlanesCursosPlan'),
            'formacion:planes:listado:plan:nuevo' => gettext('sTFormPlanesCursosPlanNuevo'),
            'formacion:cursoplan:formulario:nuevo:fila' => gettext('sTFormPlanesCursosPlanNuevoAgregar'),
            'formacion:mensajetodos:formulario:crear:nuevo' => gettext('sTFormPlanesCursosPlanNuevoMsj'),
            'formacion:cursoplan:formulario:editar:fila' => gettext('sTFormPlanesCursosPlanEditar'),
            'formacion:cursoplandetalles:formulario:editar:fila' => gettext('sTFormPlanesCursosPlanDetalles'),
            'formacion:verAsistentesCurso:listado:nuevo:fila' => gettext('sTFormPlanesCursosPlanAsist'),
            'formacion:alumno:listado:nuevo' => gettext('sTFormPlanesCursosPlanAsistAlumNuevo'),
            'formacion:verProfesores:listado:ver:fila' => gettext('sTFormPlanesCursosPlanProfesores'),
            'formacion:profesor:formulario:nuevo' => gettext('sTFormPlanesCursosPlanProfeNuevo'),
            'formacion:profesor:formulario:editar:fila' => gettext('sTFormPlanesCursosPlanProfeEditar'),
            'formacion:adjunto:formulario:nuevo:fila' => gettext('sTFormPlanesCursosPlanSubir'),

            //indicadores
            'indicadores:indicadores:listado:ver' => gettext('sTIndListado'),
            'indicadores:indicador:formulario:nuevo' => gettext('sTIndNuevo'),
            'indicadores:indicadores:listado:vermatriz' => gettext('sTIndMatriz'),
            'indicadores:graficaindicador:comun:ver:fila' => gettext('sTIndGrafica'),
            'indicadores:graficaindicador:comun:ver:grafica' => gettext('sTIndGraficaVer'),
            'indicadores:indicador:formulario:editar:fila' => gettext('sTIndEditar'),
            'indicadores:valoresindicador:listado:ver:fila' => gettext('sTIndValores'),
            'indicadores:valor:formulario:nuevo' => gettext('sTIndInsValores'),
            'indicadores:valorunico:formulario:nuevo' => gettext('sTIndInsUnValor'),
            'indicadores:valor:formulario:editar:fila' => gettext('sTIndEditarValor'),
            'indicadores:objetivos:listado:ver' => gettext('sTIndObjListado'),
            'indicadores:objetivo:formulario:nuevo' => gettext('sTIndObjNuevo'),
            'indicadores:objetivo:formulario:editar:fila' => gettext('sTIndObjEditar'),
            'indicadores:objetivos:listado:vermetas:fila' => gettext('sTIndObjMetas'),
            'indicadores:objetivo:comun:revisar:fila' => gettext('sTIndObjRevisar'),
            'indicadores:objetivo:comun:aprobar:fila' => gettext('sTIndObjAprobar'),

            //aambientales
            'aambientales:revision:listado:ver' => gettext('sTAambRevListado'),
            'aambientales:aspecto:formulario:nuevo' => gettext('sTAambNuevo'),
            'aambientales:aspecto:formulario:editar:fila' => gettext('sTAambEditar'),
            'aambientales:revisionemergencia:listado:ver' => gettext('sTAambRevemerListado'),
            'aambientales:aspectoemergencia:formulario:nuevo' => gettext('sTAambEmerNuevo'),
            'aambientales:aspectoemergencia:formulario:editar:fila' => gettext('sTAambEmerEditar'),
            'aambientales:matriz:detalles:ver' => gettext('sTAmbMatrizVer'),

            //administracion
            'administracion' => gettext('sTAdmin'),
            'administracion:usuarios:listado:ver' => gettext('sTAdminUsuarioListado'),
            'administracion:usuario:formulario:nuevo' => gettext('sTAdminUsuarioNuevo'),
            'administracion:usuarios:comun:seleccionaficha' => gettext('sTAdminUsuarioFicha'),
            'administracion:usuarios:comun:seleccionarequisitos' => gettext('sTAdminUsuarioReq'),
            'administracion:usuario:formulario:editar:fila' => gettext('sTAdminUsuarioEditar'),
            'administracion:passwordusuario:formulario:editar:fila' => gettext('sTAdminUsuarioPass'),

            'administracion:perfiles:listado:ver' => gettext('sTAdminPerfListado'),
            'administracion:perfil:formulario:nuevo' => gettext('sTAdminPerfNuevo'),
            'administracion:perfil:formulario:editar:fila' => gettext('sTAdminPerfEditar'),
            'administracion:perfil:comun:copiar:fila' => gettext('sTAdminPerfCopiar'),

            'administracion:mensajes:listado:ver' => gettext('sTAdminMsjList'),
            'administracion:mensajes:formulario:nuevo' => gettext('sTAdminMsjNuevo'),

            'administracion:tareas:listado:ver' => gettext('sTAdminTareasList'),

            'administracion:menus:listado:nuevo' => gettext('sTAdminMenusList'),
            'administracion:menu:comun:estructura' => gettext('sTAdminMenuEstruc'),
            'administracion:menu:formulario:nuevo' => gettext('sTAdminMenuNuevo'),
            'administracion:menu:listado:verbotones:fila' => gettext('sTAmindMenuBotones'),
            'administracion:boton:formulario:nuevo' => gettext('sTAdminMenuBotonesNuevo'),
            'administracion:boton:formulario:editar:fila' => gettext('sTAdminMenuBotonesEditar'),
            'administracion:idiomasboton:listado:ver:fila' => gettext('sTAdminMenuBotonesIdioma'),
            'administracion:idiomaboton:formulario:editar:fila' => gettext('sTAdminMenuBotonesIdiomaEditar'),
            'administracion:idiomas:listado:ver:fila' => gettext('sTAdminMenuIdioma'),
            'administracion:idiomamenu:formulario:editar:fila' => gettext('sTAdminMenuIdiomaEditar'),
            'administracion:menu:formulario:editar:fila' => gettext('sTAdminMenuEditar'),

            'administracion:idiomas:listado:nuevo' => gettext('sTAdminIdiomaList'),
            'administracion:idioma:formulario:nuevo' => gettext('sTAdminIdiomaNuevo'),

            'administracion:documentossg:listado:ver' => gettext('sTAdminDocSGList'),

            'administracion:registros:listado:ver' => gettext('sTAdminRegistrosList'),

            'administracion:normativa:listado:ver' => gettext('sTAdminNormativaList'),

            'administracion:tipomejora:listado:ver' => gettext('sTAdminTipomejoraList'),
            'administracion:mejora:formulario:nuevo' => gettext('sTAdminTipomejoraNuevo'),
            'administracion:mejora:formulario:editar:fila' => gettext('sTAdminTipomejoraEditar'),

            'administracion:tiposareas:listado:ver' => gettext('sTAdminTipoareasList'),
            'administracion:tipoarea:formulario:nuevo' => gettext('sTAdminTipoareasNuevo'),
            'administracion:tipoarea:formulario:editar:fila' => gettext('sTAdminTipoareasEditar'),

            'administracion:tiposamb:listado:ver' => gettext('sTAdminTiposambList'),
            'administracion:tipoamb:formulario:nuevo' => gettext('sTAdminTiposambNuevo'),
            'administracion:tipoamb:formulario:editar:fila' => gettext('sTAdminTiposambEditar'),

            'administracion:legaplicable:listado:ver' => gettext('sTAdminLegList'),
            'administracion:preguntasleg:listado:nuevo:fila' => gettext('sTAdminLegListPreg'),
            'administracion:preguntasleg:formulario:nuevo' => gettext('sTAdminLegListPregNueva'),
            'administracion:preguntasleg:comun:baja:general' => gettext('sTAdminLegListPregBaja'),

            'administracion:tiposimp:listado:ver' => gettext('sTAdminTiposimpList'),
            'administracion:impacto:formulario:nuevo' => gettext('sTAdminTiposimpNuevo'),
            'administracion:impacto:formulario:editar:fila' => gettext('sTAdminTiposimpEditar'),

            'administracion:tipo_cursos:listado:ver' => gettext('sTAdminFormCurList'),
            'administracion:tipocurso:formulario:nuevo' => gettext('sTAdminFormCurNuevo'),
            'administracion:tipocurso:formulario:editar:fila' => gettext('sTAdminFormCurEditar'),

            'administracion:tipodocumento:listado:nuevo' => gettext('sTAdminTipoDocList'),
            'administracion:tipodocumento:formulario:nuevo' => gettext('sTAdminTipoDocNuevo'),
            'administracion:tipodocumento:formulario:editar:fila' => gettext('sTAdminTipoDocEditar'),
            'administracion:permisosdocumento:listado:ver:fila' => gettext('sTAdminTipoDocPermisos'),

            'administracion:aspectos:listado:magnitud' => gettext('sTAdminAspecMagList'),
            'administracion:magnitud:formulario:nuevo' => gettext('sTAdminAspecMagNuevo'),
            'administracion:magnitud:formulario:editar:fila' => gettext('sTAdminAspecMagEditar'),

            'administracion:aspectos:listado:gravedad' => gettext('sTAdminAspecGravList'),
            'administracion:gravedad:formulario:nuevo' => gettext('sTAdminAspecGravNuevo'),
            'administracion:gravedad:formulario:editar:fila' => gettext('sTAdminAspecGravEditar'),

            'administracion:aspectos:listado:frecuencia' => gettext('sTAdminAspecFreList'),
            'administracion:frecuencia:formulario:nuevo' => gettext('sTAdminAspecFreNuevo'),
            'administracion:frecuencia:formulario:editar:fila' => gettext('sTAdminAspecFreEditar'),

            'administracion:aspectos:listado:formula' => gettext('sTAdminAspecFormList'),
            'administracion:formula:formulario:editar:fila' => gettext('sTAdminAspecFormEditar'),

            'administracion:aspectos:listado:probabilidad' => gettext('sTAdminAspecProbList'),
            'administracion:probabilidad:formulario:nuevo' => gettext('sTAdminAspecProbNuevo'),
            'administracion:probabilidad:formulario:editar:fila' => gettext('sTAdminAspecProbEditar'),

            'administracion:aspectos:listado:severidad' => gettext('sTAdminAspecSeverList'),
            'administracion:severidad:formulario:nuevo' => gettext('sTAdminAspecServNuevo'),
            'administracion:severidad:formulario:editar:fila' => gettext('sTAdminAspecServEditar'),

            'administracion:ayuda:listado:nuevo' => gettext('sTAdminAyudaList'),
            'administracion:ayuda:formulario:nuevo' => gettext('sTAdminAyudaNuevo'),
            'administracion:ayuda:formulario:editar:fila' => gettext('sTAdminAyudaEditar'),

            'administracion:modulos:listado:nuevo' => gettext('sTAdminMod'),

            //Auditorias
            'auditorias:programa:listado:ver' => gettext('sTAudiListado'),
            'auditorias:programa:formulario:nuevo' => gettext('sTAudiNuevo'),
            'auditorias:programa:formulario:editar:fila' => gettext('sTAudiEditar'),
            'auditoria:programa:comun:hacervigente:fila' => gettext('sTAudiVigente'),
            'auditoria:programa:comun:copiar:fila' => gettext('sTAudiCopiar'),
            'auditoria:programa:listado:ver:fila' => gettext('sTAudiVer'),
            'auditorias:auditoria:formulario:editar:fila' => gettext('sTAudiAudiEditar'),
            'auditorias:auditoria:detalles:ver:fila' => gettext('sTAudiAudiDetalles'),
            'auditorias:plan:formulario:editar:fila' => gettext('sTAudiAudiPlan'),
            'auditorias:equipoauditor:listado:ver:fila' => gettext('sTAudiAudiEquipoAudi'),
            'auditorias:adjunto:formulario:nuevo:fila' => gettext('sTAudiAudiAdjunto'),
            'auditorias:adjunto:comun:ver:fila' => gettext('sTAudiAudiVerAdjunto'),
            'auditorias:horarioauditoria:listado:ver:fila' => gettext('sTAudiAudiHorario'),
            'auditorias:horarioauditoria:formulario:nuevo' => gettext('sTAudiAudiHorarioNuevo'),
            'auditorias:horarioauditoria:formulario:editar:fila' => gettext('sTAudiAudiHorarioEditar'),
            'auditorias:informeauditoria:formulario:editar:fila' => gettext('sTAudiAudiInforme'),
            'auditorias:programa:comun:revision:fila' => gettext('sTAudiAudiRevision'),
            'auditorias:plan:listado:ver' => gettext('sTAudiVigor'),
            'auditorias:auditoria:formulario:nuevo' => gettext('sTAudiVigorNuevo'),
        );
    }

    /**
     * @param $action
     * @return mixed
     */
    public function getTitulo($action) {
        return $this->aTitulos[$action];
    }
}
