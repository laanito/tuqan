<?php
namespace Tuqan\Classes;
/**
 * Created on 11-nov-2005
 *
* LICENSE see LICENSE.md file
 *

 * @version 0.4.1a
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * Esta clase es la que realiza el procesado de la peticion, devuelve una cadena que
 * consta de las division y los contenidos a incluir en ella.
 */

class Procesador_De_Peticiones
{

    /**
     *    Esta es la cadena html a devolver, sigue la convencion "division|contenido"
     * @access private
     * @var string
     */

    private $sHtml;

    /**
     *    Esta es el array con los parametros para hacer el proceso
     * @access private
     * @var array
     */

    private $aParametros;

    /**
     *    Constructor
     *
     * @access public
     * @param array $aParam
     */

    function __construct($aParam)
    {
        $this->aParametros = $aParam;
    }
    //Fin __construct

    /**
     *       Esta funcion hace el procesamiendo adecuado segun los parametros y guarda el
     *    resultado en el campo sHtml
     *
     * @access public
     */

    function procesar()
    {
        $sAccion = $this->aParametros['accion'];
        $aOpciones = explode(":", $sAccion, 4);
        if ($aOpciones[2]) {
            $sMenu = $aOpciones[0] . ":" . $aOpciones[1] . ":" . $aOpciones[3];
            switch ($aOpciones[2]) {

                case 'ayuda':
                    {
                        $this->sHtml = "divayuda|" . procesa_Ayuda($this->aParametros);
                        break;
                    }

                case 'excel':
                    {
                        $this->sHtml = "contenedor|<iframe id=\"excel\" src=\"crearExcel.php?accion=" .
                            $this->aParametros['accion'] . "\" width=\"100%\" " .
                            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                        break;
                    }

                case 'editor':
                    {
                        switch ($sMenu) {
                            case 'editor:documento:nuevo':
                            case 'editor:documento:editar:fila':
                            case 'editor:documento:editar':
                                {
                                    $this->sHtml = procesa_Editor($this->aParametros);
                                    break;
                                }
                            case 'editor:documento:ver':
                                {
                                    $Comunes = new Procesar_Funciones_Comunes();
                                    $this->sHtml = $Comunes->mostrar_Documento(null, $this->aParametros['iddoc']);
                                    break;
                                }
                            case 'editor:documento:nuevaversion':
                                {
                                    $this->sHtml = procesa_Editor($this->aParametros);
                                    break;
                                }
                        }
                        break;
                    }

                case 'comun':
                    switch ($sMenu) {

                        case 'inicio:mensajes:estadisticas':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Grafica_Mensajes($sMenu, $this->aParametros);
                            break;

                        case 'administracion:menu:estructura':
                            $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"estructura_arbol.php\" width=\"100%\" " .
                                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                            break;

                        case 'administracion:perfil:copiar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Copiar_Perfil($sMenu, $this->aParametros);
                            break;

                        case 'formacion:planes:hacervigente:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->hacerVigente('hacerVigente', $this->aParametros);
                            break;

                        case 'documentacion:manual:cambiar:actualizar':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesar_Permisos_Documentos($this->aParametros);
                            break;

                        case 'administracion:permisos:cambiar:actualizar':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesar_Permisos($this->aParametros);
                            break;

                        case 'administracion:permisobotones:cambiar:permisos':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml =$Comunes->procesar_Permisos_Botones($this->aParametros);
                            break;

                        case 'administracion:hospitales:generar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->generar_Base_Datos($this->aParametros);
                            break;

                        case 'administracion:permisomenu:cambiar:permisos':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesar_Permisos_Menu($this->aParametros);
                            break;

                        case 'catalogo:fichaproceso:ver:radio':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Ficha_Proceso($sAccion, $this->aParametros);
                            break;

                        case 'formacion:inscripcion:baja:fila':
                        case 'formacion:inscripcion:alta:fila':
                        case 'formacion:cursos:curso:baja':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Alta_Baja($sMenu, $this->aParametros);
                            break;

                        case 'documentacion:seguimiento:ver:fila':
                            $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?tipo=objetivoseguimiento&id=".
                                $_SESSION['pagina'][$this->aParametros['numeroDeFila']] . "\" width=\"100%\" ".
                                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                            break;

                        case 'auditorias:adjunto:ver:fila':
                            $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?tipo=adjuntoauditoria&id=".
                                $_SESSION['pagina'][$this->aParametros['numeroDeFila']] . "\" width=\"100%\" ".
                                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                            break;

                        case 'formacion:adjunto:ver:fila':
                            $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?tipo=adjuntohojafirmas&id=".
                                $_SESSION['pagina'][$this->aParametros['numeroDeFila']] . "\" width=\"100%\" ".
                                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                            break;

                        case 'general:general:aform:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "formulario|" . $Comunes->procesa_FilaAForm($sMenu, $this->aParametros);
                            break;

                        case 'indicadores:graficaindicador:ver:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Grafica_Indicadores($sAccion, $this->aParametros);
                            break;

                        case 'formacion:planes:seleccionadocumentoext':
                        case 'auditorias:auditor:seleccionausuario':
                        case 'formacion:planes:seleccionausuario':
                        case 'administracion:usuarios:seleccionaficha':
                        case 'documentacion:legislacion:seleccionaficha':
                        case 'documentacion:legislacion:seleccionaley':
                        case 'administracion:usuarios:seleccionarequisitos':
                        case 'mejora:acmejora:seleccionausuario':
                        case 'inicio:tarea:seleccionausuario':
                            $ProcesaListado=new Procesar_Listados();
                            $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                            break;
                        case 'inicio:tarea:seleccionaindicadores':
                            $ProcesaListado=new Procesar_Listados;
                            $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                            break;

                        case 'recursos:general:nuevo:calendario':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "calendario|" . $Comunes->procesa_Calendario_Mes($this->aParametros);
                            break;

                        case 'catalogo:indicadoresproceso:alta:general':
                        case 'catalogo:documentoproceso:baja:general':
                        case 'catalogo:documentoproceso:alta:general':
                        case 'catalogo:indicadoresproceso:baja:general':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_AltaBaja_DocumentoProceso($sMenu, $this->aParametros);
                            break;

                        case 'inicio:tarea:baja:general':
                        case 'documentacion:meta:baja:general':
                            // modificacion indicadores objetivos
                        case 'indicadores:metaobjetivosindicadores:baja:general':
                            // case 'proveedores:proveedores:baja:general':
                        case 'auditorias:auditor:baja:fila':
                        case 'auditorias:auditoria:baja:general':
                        case 'equipos:listado:baja:general':
                        case 'proveedores:proveedores:baja:general':
                        case 'formacion:profesores:baja:general':
                        case 'formacion:planes:baja:fila':
                        case 'formacion:plantilla_curso:baja:general':
                        case 'formacion:cursos:baja:general':
                        case 'inicio:mensajes:baja:general':
                        case 'administracion:impacto:baja:general':
                        case 'administracion:preguntasleg:baja:general':
                        case 'administracion:documentosformato:baja:general':
                        case 'administracion:documentosformato:alta:general':
                        case 'administracion:tareas:baja:general':
                        case 'administracion:registros:alta:general':
                        case 'administracion:registros:baja:general':
                        case 'administracion:documentos:alta:general':
                        case 'administracion:documentos:baja:general':
                        case 'administracion:adminlegaplicable:baja:general':
                        case 'administracion:adminlegaplicable:alta:general':
                        case 'administracion:usuarios:baja:general':
                        case 'administracion:perfiles:baja:general':
                        case 'administracion:mensajes:baja:general':
                        case 'administracion:requisitos_puesto:alta:general':
                        case 'administracion:requisitos_puesto:baja:general':
                        case 'administracion:ficha_personal:alta:general':
                        case 'administracion:ficha_personal:baja:general':
                        case 'aambientales:aspecto:baja:general':
                        case 'indicadores:objetivo:baja:general':
                        case 'auditorias:programa:baja:general':
                        case 'indicadores:valor:baja:general':
                        case 'catalogo:valores:baja:general':
                        case 'catalogo:objetivos_indicadores:baja:general':

                        case 'administracion:proveedorlistado:baja:general':
                        case 'administracion:proveedorincidencias:baja:general':
                        case 'administracion:proveedorcontactos:baja:general':
                        case 'administracion:proveedorproductos:baja:general':
                        case 'administracion:proveedorlistado:alta:general':
                        case 'administracion:proveedorincidencias:alta:general':
                        case 'administracion:proveedorcontactos:alta:general':
                        case 'administracion:proveedorproductos:alta:general':
                        case 'administracion:equiposlistado:baja:general':
                        case 'administracion:equiposlistado:alta:general':
                        case 'administracion:auditoriaanual:baja:general':
                        case 'administracion:auditoriaanual:alta:general':
                        case 'administracion:auditoriavigor:baja:general':
                        case 'administracion:auditoriavigor:alta:general':
                        case 'administracion:indicadoresobjetivo:baja:general':
                        case 'administracion:indicadoresobjetivo:alta:general':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_AltaBaja_Filas($sMenu, $this->aParametros);
                            break;

                        case 'procesos:catalogo:baja:radio':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_AltaBaja_Filas_Arbol($this->aParametros);
                            break;

                        case 'formacion:confirmaralumno:alta:fila':
                        case 'formacion:confirmaralumno:baja:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->confirma_Alta_Baja($sMenu, $this->aParametros);
                            break;

                        case 'formacion:alumno:baja:general':
                        case 'formacion:alumno:alta:general':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->altabaja_Alumnos($sMenu, $this->aParametros);
                            break;

                        case 'auditoria:programa:copiar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Copiar_Auditorias($sMenu, $this->aParametros);
                            break;

                        case 'catalogo:nuevaversionproceso:nuevo':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesar_NuevaVersionProceso($sMenu, $this->aParametros);
                            break;

                        case 'auditorias:areaauditoria:alta:fila':
                        case 'auditorias:areaauditoria:baja:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_AltaBaja_Areas_Auditorias($sMenu, $this->aParametros);
                            break;

                        case 'auditoria:programa:hacervigente:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->hacerVigente('hacerVigenteauditoria', $this->aParametros);
                            break;

                        case 'auditorias:programa:revision:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->nueva_Revision_Programa($sAccion, $this->aParametros);
                            break;

                        case 'documentacion:general:verdocumento':
                            if (isset ($this->aParametros['id'])) {
                                $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?id=".
                                    $this->aParametros['id'].
                                    "&tipo=documento\" width=\"100%\" height=\"600px\" frameborder=\"0\"  style=\"z-index: 0; visibility: hidden\"><\iframe>";
                            } else {
                                $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?id=".
                                    $_SESSION['pagina'][$this->aParametros['documento']].
                                    "&tipo=documento\" width=\"100%\" height=\"600px\" frameborder=\"0\"  style=\"z-index: 0; visibility: hidden\"><\iframe>";
                            }
                            break;

                        case 'proveedores:producto:revisar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Revision_Producto($sAccion, $this->aParametros);
                            break;

                        case 'proveedores:criterios:alta:general':
                        case 'proveedores:criterios:baja:fila':
                        case 'proveedores:criterios:baja:general':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_AltaBaja_Criterios($sAccion, $this->aParametros);
                            break;

                        case 'proveedores:producto:revisa:general':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Revisa_Producto($this->aParametros);
                            break;

                        case 'documentossg:general:documento:editadocumentosinfila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Editar_Documento($this->aParametros);
                            break;

                        case 'documentacion:general:editarversion':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_EditarVersion_Documento($this->aParametros);
                            break;

                        case 'documentacion:general:revisardocumento':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Revisar_Documento($this->aParametros);
                            break;

                        case 'catalogo:flujograma:nuevo' :
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->subir_Fichero_Flujo($sMenu, $this->aParametros);
                            break;

                        case 'catalogo:documentosproceso:ver:fila':
                        case 'documentacion:general:verdocumentosinfila':
                            if (isset ($this->aParametros['id'])) {
                                $this->sHtml = "contenedor|<iframe id=\"ficheros\" src=\"muestrabinario.php?id=".
                                    $this->aParametros['id'] . "&tipo=documento\" width=\"1%\" height=\"1%\" frameborder=\"0\"><\iframe>";
                            } else {
                                $this->sHtml = "contenedor|<iframe id=\"ficheros\" src=\"muestrabinario.php?id=".
                                    $_SESSION['pagina'][$this->aParametros['documento']] . "&tipo=documento\" width=\"1%\" height=\"1%\" frameborder=\"0\"><\iframe>";
                            }
                            break;

                        case 'documentacion:general:aprobardocumento':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Aprobar_Documento($this->aParametros);
                            break;

                        case 'documentacion:general:asignatarea':
                            $Procesador=new Procesar_Formularios();
                            $_SESSION['origen'] = $this->aParametros['origen'];
                            $_SESSION['documento'] = $this->aParametros['documento'];
                            $this->sHtml = $Procesador->procesa_Formulario('documentacion:tarea:formulario:nuevo');
                            break;

                        case 'documentacion:general:nuevaversion':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_NuevaVersion_Iframe($this->aParametros);
                            break;

                        case 'documentos:general:iframe:nuevaversion':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_NuevaVersion_Documento($this->aParametros);
                            break;

                        case 'documentacion:objetivos:aprobar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Aprobar_Objetivos($this->aParametros);
                            break;

                        // modificacion para botones aprobar y revisar de indicadores objetivos

                        case 'indicadores:objetivo:aprobar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Aprobar_Objetivos_Indicadores($this->aParametros);
                            break;

                        case 'indicadores:objetivo:revisar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Revisar_Objetivos_Indicadores($this->aParametros);
                            break;

                        case 'documentacion:objetivos:revisar:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Revisar_Objetivos($this->aParametros);
                            break;

                        case 'documentacion:objetivos:nuevaversion:fila':
                            $Comunes = new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_nuevaversion_Objetivos($this->aParametros);
                            break;

                        case 'documentacion:objetivos:seguimiento:fila':
                        case 'documentacion:general:ver:fila':
                        case 'documentos:documentope:ver:fila':
                        case 'documentos:planemeramb:ver:fila':
                        case 'documentos:documentopg:ver:fila':
                        case 'documentos:objetivos:ver:fila':
                        case 'documentos:frl:ver:fila':
                        case 'documentos:pambiental:ver:fila':
                        case 'documentos:manual:ver:fila':
                        case 'documentos:vigor:ver:fila':
                        case 'documentos:docformatos:ver:fila':
                        case 'documentos:legislacion:ver:fila':
                        case 'documentos:borrador:ver:fila':
                        case 'documentos:documentoaai:ver:fila':
                        case 'documentos:documentonormativa:ver:fila':
                        case 'documentos:documentoarchivoproceso:ver:fila':
                            if (isset ($this->aParametros['id'])) {
                                echo $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?id=".
                                    $this->aParametros['id'].
                                    "&tipo=documento\" width=\"100%\" height=\"600px\" frameborder=\"0\"  style=\"z-index: 0; visibility: hidden\"><\iframe>";
                                die();
                            } else {
                                $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?id=".
                                    $_SESSION['pagina'][$this->aParametros['documento']].
                                    "&tipo=documento\" width=\"100%\" height=\"600px\" frameborder=\"0\"  style=\"z-index: 0; visibility: hidden\"><\iframe>";
                            }
                            break;

                        case 'documentos:legislacion:fichanodef':
                            $this->sHtml = "alert|" . gettext('sNofichDef');
                            break;

                        case 'administracion:permisos:botones':
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor_derecha|" . $Comunes->procesa_Permisos_Botones($this->aParametros);
                            break;

                        case 'administracion:permisos:menu':
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor_derecha|" . $Comunes->procesa_Permisos_Menu($this->aParametros);
                            break;

                        case 'inicio:tareas:ver:fila':
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Tarea($this->aParametros);
                            break;


                    }
                    break;

                case 'upload':
                    switch ($sMenu) {
                        case 'documentossg:general:nuevo':
                        case 'documentossg:objetivos:crear' :
                        case 'documentacion:manual:ver' :
                        case 'documentacion:politica:ver' :
                        case 'auditorias:adjunto:nuevo' :
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Iframe_Documento($sMenu, $this->aParametros);
                            break;

                        case 'auditorias:iframe:nuevo:adjunto' :
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Adjunto($sMenu, $this->aParametros);
                            break;

                        case 'catalogo:flujograma:iframe' :
                            $Comunes=new Procesar_Funciones_Comunes();
                            $this->sHtml = $Comunes->procesa_Flujograma($sMenu, $this->aParametros);
                            break;
                    }
                    break;

                case 'detalles':
                    switch ($sMenu) {
                        case 'documentacion:borrador:ver:fila':
                        case 'documentacion:documentope:ver:fila':
                        case 'documentacion:planemeramb:ver:fila':
                        case 'documentacion:documentopg:ver:fila':
                        case 'documentacion:documentoarchivoproceso:ver:fila':
                        case 'documentacion:objetivos:ver:fila':
                        case 'documentacion:frl:ver:fila':
                        case 'documentacion:pambiental:ver:fila':
                        case 'documentacion:manual:ver:fila':
                        case 'documentacion:vigor:ver:fila':
                        case 'documentacion:formatos:ver:fila':
                        case 'documentacion:documentoaai:ver:fila':
                        case 'documentos:documentoaai:ver:fila':
                        case 'documentacion:documentonormativa:ver:fila':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_Documento($this->aParametros['documento'], 'documento');
                            break;


                        case 'auditorias:auditoria:ver:fila':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_Auditorias($sAccion, $this->aParametros);
                            break;

                        case 'catalogo:proceso:ver:radio':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_Procesos($sMenu, $this->aParametros);
                            break;

                        case 'equipos:mantenimiento:ver:fila':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_Revision($sAccion, $this->aParametros);
                            break;

                        case 'inicio:documentoid:ver:fila':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_Documento($this->aParametros['tarea'], 'tarea');
                            break;

                        case 'formacion:inscripcion:ver:fila':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles($sMenu, $this->aParametros);
                            break;
                        case 'aambientales:matriz:ver':
                            $Detalles=new Procesar_Detalles();
                            $this->sHtml = "contenedor|" . $Detalles->procesa_Detalles_MatrizMA();
                            break;
                    }
                    break;

                case 'formulario':
                    switch ($sMenu) {

                        case 'inicio:nuevo:general':
                        case 'documentacion:legislacion:ver':
                            $Form=new Forms();
                            $this->sHtml = $Form->formulario($this->aParametros['formulario'], $this->aParametros['id']);
                            break;

                        case 'documentacion:legislacion:nuevo':
                        case 'formacion:planes:nuevo':
                        case 'indicadores:objetivo:nuevo':
                        case 'indicadores:indicador:nuevo':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->procesa_Formulario($sAccion, null);
                            break;

                        case 'catalogo:proceso:editar:radio':
                        case 'catalogo:contenidoproceso:editar:fila':
                        case 'catalogo:contenidoproc:editar':
                        case 'documentacion:legislacion:editar:fila':
                        case 'documentacion:meta:nuevo':

                        case 'documentacion:seguimiento:nuevo':
                        case 'documentacion:seguimiento:editar:fila':
                        case 'indicadores:metaobjetivosindicadores:nuevo':
                        case 'documentacion:meta:editar:fila':

                        case 'indicadores:metaobjetivosindicadores:editar:fila':
                        case 'auditorias:plan:planauditoria:editar':
                        case 'auditorias:plan:editar:fila':
                        case 'auditorias:auditoria:editar:fila':
                        case 'auditorias:auditoria:nuevo':
                        case 'auditorias:auditor:nuevo':
                        case 'auditorias:horarioauditoria:nuevo':
                        case 'auditorias:horarioauditoria:editar:fila':
                        case 'auditorias:informeauditoria:editar:fila':
                        case 'administracion:criterio:nuevo':
                        case 'administracion:cliente:nuevo':
                        case 'administracion:criterio:editar:fila':
                        case 'administracion:cliente:editar:fila':
                        case 'equipos:mantenimientoprev:nuevo':
                        case 'equipos:mantenimientocorr:nuevo':
                        case 'equipos:mantenimientoprev:editar:fila':
                        case 'equipos:mantenimientocorr:editar:fila':
                        case 'equipos:equipo:nuevo':
                        case 'equipos:equipo:crear:editar':
                        case 'equipos:equipo:editar:fila':
                            //    case 'proveedores:incidencia:general:nuevo':
                        case 'proveedores:incidencia:nuevo':
                        case 'proveedores:incidencia:editar:fila':
                        case 'proveedores:producto:nuevo':
                        case 'proveedores:producto:editar:fila':
                        case 'documentacion:objetivos:editar:fila':
                            //case 'proveedores:contacto:crear:nuevo':
                        case 'proveedores:contacto:nuevo':
                            //case 'proveedores:contacto:crear:editar':
                        case 'proveedores:contacto:editar:fila':
                        case 'administracion:centros:nuevo':
                        case 'administracion:centros:editar:fila':
                        case 'proveedores:productofila:editar:fila':
                        case 'proveedores:contactosfila:editar:fila':
                        case 'proveedores:proveedor:editar:fila':
                        case 'proveedores:proveedor:nuevo':
                        case 'proveedores:incidenciafila:nuevo':
                        case 'proveedores:incidenciafila:editar:fila':
                        case 'proveedores:contactosfila:nuevo':
                        case 'proveedores:productofila:nuevo':
                        case 'administracion:gravedad:editar:fila':
                        case 'administracion:severidad:editar:fila':
                        case 'administracion:tipocurso:nuevo':
                        case 'administracion:tipocurso:editar:fila':
                        case 'administracion:impacto:editar:fila':
                        case 'administracion:tipoimpidioma:editar:fila':
                        case 'administracion:impacto:nuevo':
                        case 'administracion:tipoimpidioma:nuevo':
                        case 'administracion:idiomamenu:nuevo':
                        case 'administracion:preguntasleg:editar:fila':
                        case 'administracion:preguntasleg:nuevo':
                        case 'administracion:tipoamb:editar:fila':
                        case 'administracion:tipoambidioma:editar:fila':
                        case 'administracion:tipoamb:nuevo':
                        case 'administracion:tipoambidioma:nuevo':
                        case 'administracion:tipoarea:editar:fila':
                        case 'administracion:tipoarea:nuevo':
                        case 'administracion:mejora:nuevo':
                        case 'administracion:mejoraidioma:nuevo':
                        case 'administracion:mejora:editar:fila':
                        case 'administracion:mejoraidioma:editar:fila':
                        case 'administracion:tipodocumento:nuevo':
                        case 'administracion:tipodocidioma:nuevo':
                        case 'administracion:ayuda:nuevo':
                        case 'administracion:idioma:nuevo':
                        case 'administracion:tipodocumento:editar:fila':
                        case 'administracion:tipodocidioma:editar:fila':
                        case 'administracion:ayuda:editar:fila':
                        case 'administracion:passwordusuario:editar:fila':
                        case 'administracion:usuario:editar:fila':
                        case 'administracion:perfil:editar:fila':
                        case 'administracion:area:editar:fila':
                        case 'administracion:mensajes:editar:fila':
                        case 'administracion:mensajes:nuevo':
                        case 'administracion:area:nuevo':
                        case 'administracion:perfil:nuevo':
                        case 'administracion:usuario:nuevo':
                        case 'administracion:boton:nuevo':
                        case 'administracion:boton:editar:fila':
                        case 'administracion:idiomaboton:editar:fila':
                        case 'administracion:idiomamenu:editar:fila':
                        case 'aambientales:aspecto:editar:fila':
                        case 'aambientales:aspectoemergencia:editar:fila';
                        case 'aambientales:aspecto:nuevo':
                        case 'aambientales:aspectoemergencia:nuevo';
                        case 'administracion:menu:nuevo':
                        case 'administracion:menu:editar:fila':
                        case 'administracion:magnitud:editar:fila':
                        case 'administracion:magnitudidioma:editar:fila':
                        case 'administracion:frecuencia:editar:fila':
                        case 'administracion:frecuenciaidioma:editar:fila':
                        case 'administracion:gravedadidioma:editar:fila':
                        case 'administracion:probabilidadidioma:editar:fila':
                        case 'administracion:probabilidad:editar:fila':
                        case 'administracion:severidadidioma:editar:fila':
                        case 'administracion:magnitud:nuevo':
                        case 'administracion:magnitudidioma:nuevo':
                        case 'administracion:frecuencia:nuevo':
                        case 'administracion:frecuenciaidioma:nuevo':
                        case 'administracion:gravedad:nuevo':
                        case 'formacion:formaciontecnicarq:nuevo':
                        case 'administracion:gravedadidioma:nuevo':
                        case 'administracion:probabilidadidioma:nuevo':
                        case 'administracion:severidad:nuevo':
                        case 'administracion:severidadidioma:nuevo':
                        case 'administracion:probabilidad:nuevo':
                        case 'administracion:formula:editar:fila':
                        case 'indicadores:objetivo:editar:fila':
                        case 'indicadores:indicador:editar:fila':
                        case 'indicadores:valor:nuevo':
                        case 'indicadores:valor:editar:fila':
                        case 'indicadores:valorunico:nuevo':
                        case 'formacion:cursos:nuevo':
                        case 'formacion:planes:editar:fila':
                        case 'formacion:cursos:editar:fila':
                        case 'formacion:cursoplan:nuevo:fila':
                        case 'formacion:mensajetodos:crear:nuevo':
                        case 'formacion:cursoplan:editar:fila':
                        case 'formacion:cursoplandetalles:editar:fila':
                        case 'formacion:profesor:editar:fila':
                        case 'inicio:mensajes:nuevo':
                        case 'auditorias:programa:editar:fila':
                        case 'auditorias:programa:nuevo':
                        case 'auditorias:plan:planauditoria:nuevo':
                        case 'auditorias:auditoria:crear:nuevo':
                        case 'formacion:fichapersonal:editar:fila':
                        case 'formacion:fichapersonal:nuevo':
                        case 'formacion:reqpuesto:editar:fila':
                        case 'formacion:reqpuesto:nuevo':
                        case 'formacion:datospuesto:editar:fila':
                        case 'formacion:fpdp:editar:fila':
                        case 'formacion:fpinc:editar:fila':
                        case 'formacion:fpfor:editar:fila':
                        case 'administracion:hospitales:nuevo':
                        case 'administracion:hospitales:editar:fila':
                        case 'formacion:fppre:editar:fila':
                        case 'formacion:fpidiomas:editar:fila':
                        case 'formacion:fpcursos:editar:fila':
                        case 'formacion:fpft:editar:fila':
                        case 'formacion:fpel:editar:fila':
                        case 'formacion:fpcp:editar:fila':
                        case 'formacion:fpcd:editar:fila':
                        case 'formacion:competenciasrq:editar:fila':
                        case 'formacion:formacionrq:editar:fila':
                        case 'formacion:promocionrq:editar:fila':
                        case 'formacion:formaciontecnicarq:editar:fila':
                        case 'catalogo:valor:nuevo':
                        case 'catalogo:valor:editar:fila':
                        case 'catalogo:valorunico:nuevo':
                        case 'catalogo:objindicador:nuevo':
                        case 'catalogo:objindicador:editar:fila':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->procesa_Formulario($sAccion, $this->aParametros);
                            break;

                        case 'mejora:acmejora:editar:fila':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->procesa_Formulario_Accmejora($sAccion, $this->aParametros);
                            break;

                        case 'formacion:profesor:nuevo':
                        case 'formacion:alumno:crear:nuevo':
                        case 'mejora:acmejora:nuevo':
                        case 'catalogo:proceso:nuevo:radio':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->procesa_Formulario($sAccion, null);
                            break;

                        case 'documentacion:frlvigor:nuevo':
                        case 'documentacion:documentonormativavigor:nuevo':
                        case 'documentacion:frl:nuevo':

                        case 'documentacion:documentope:nuevo':

                        case 'documentacion:planemeramb:nuevo':
                        case 'documentacion:documentopg:nuevo':
                        case 'documentacion:documentoarchivoproceso:nuevo':
                        case 'documentacion:manual:nuevo':
                        case 'documentacion:pambiental:nuevo':
                        case 'documentacion:formatos:nuevo':
                        case 'documentacion:documentoaai:nuevo':
                        case 'documentacion:documentonormativa:nuevo':
                        case 'auditorias:adjunto:nuevo:fila' :
                        case 'documentacion:seguimiento:nuevo:fila' :
                        case 'formacion:adjunto:nuevo:fila':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->subir_Fichero_Documento($this->aParametros);
                            break;

                        case 'documentacion:objetivos:nuevo':
                            $Procesador=new Procesar_Formularios();
                            $this->sHtml = $Procesador->procesa_Formulario($sAccion, null);
                            break;

                        case 'documentacion:formulariomedio:nuevo':
                            $Form=new Forms();
                            $this->sHtml = $Form->formulario_Medio($this->aParametros['idlegislacion']);
                            break;

                        case 'documentos:cuestionario:nuevo:fila':
                            $ProcesaListado =new Procesar_Listados();
                            $this->sHtml = $ProcesaListado->procesa_Ver_Cuestionario($this->aParametros);
                            break;

                    }
                    break;
                // Si tenemos un listado como opción
                case 'listado' :
                    {
                        switch ($sMenu) {
                            //case 'inicio:mensajes:':

                            case 'inicio:mensajes:inicial':
                                {
                                    $ProcesaListado=new Procesar_Listados();
                                    $this->sHtml = $ProcesaListado->crea_Menu_Superior($sAccion) . "|";
                                    $this->sHtml .= "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                                    break;
                                }
                            case 'inicio:mensajes:ver' :
                                $ProcesaListado=new Procesar_Listados();
                                $this->sHtml .= "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                                break;

                            case 'equipos:planmantenimientoid:nuevo':
                            case 'equipos:planmantenimiento:ver:fila':
                            case 'equipos:planmantenimiento:nuevo:fila':
                            case 'equipos:revision:ver':
                            case 'equipos:listado:ver':
                            case 'proveedores:incidencias:ver:fila':
                            case 'proveedores:contactos:ver:fila':
                            case 'proveedores:productos:ver:fila':
                            case 'proveedores:productos:productoshistorico:fila':
                            case 'proveedores:criterio:nuevo':
                            case 'proveedores:productos:criterios:fila':
                            case 'proveedores:listado:ver':
                            case 'proveedores:incidencias:ver':
                            case 'proveedores:contactos:ver':
                            case 'proveedores:productos:ver':
                            case 'proveedores:phomologados:ver':
                            case 'formacion:planes:ver:fila':
                            case 'mejora:listado:ver' :
                            case 'formacion:inscripcion:ver' :
                            case 'formacion:planes:ver' :
                            case 'formacion:cursos:ver' :
                            case 'formacion:fichapersonal:ver' :
                            case 'formacion:reqpuesto:ver' :
                            case 'formacion:inscripcion:asistentes:fila':
                            case 'inicio:historicomensajes:ver' :
                            case 'inicio:tareas:ver' :
                            case 'auditorias:programa:ver' :
                            case 'auditorias:plan:ver' :
                            case 'auditorias:areasauditoria:ver:fila':
                            case 'auditorias:equipoauditor:ver:fila':
                            case 'auditorias:areaauditoria:nuevo':
                            case 'indicadores:indicadores:ver' :
                            case 'indicadores:objetivos:ver' :
                            case 'indicadores:valoresindicador:ver:fila':
                            case 'documentacion:manual:ver' :
                            case 'documentacion:documentonormativa:ver':
                            case 'documentacion:pg:ver' :
                            case 'documentacion:procesoarchivo:ver' :
                            case 'documentacion:pe:ver' :

                            case 'documentacion:planamb:ver' :
                            case 'documentacion:docvigor:ver' :
                            case 'documentacion:frl:ver' :
                            case 'documentacion:aai:ver':
                            case 'documentacion:docborrador:ver' :
                            case 'documentacion:registros:ver' :
                            case 'documentacion:listadoregistros:ver:fila':
                            case 'documentacion:docformatos:ver' :
                            case 'documentacion:normativa:ver':
                            case 'documentacion:metas:ver:fila':

                            case 'documentacion:objetivos:seguimiento:fila':
                            case 'indicadores:objetivos:vermetas:fila':
                            case 'documentos:registros:listarfila:fila' :
                            case 'formacion:inscripcion:asistentes' :
                            case 'formacion:reqpuesto:formaciontecnicarq:fila':
                            case 'auditoria:programa:ver:fila':
                            case 'documentacion:general:verhistorico':
                            case 'formacion:inscripcion:ver:fila:fila':
                            case 'formacion:planes:plan:nuevo':
                            case 'formacion:verAsistentesCurso:nuevo:fila':
                            case 'formacion:verProfesores:ver:fila':
                            case 'catalogo:indicadores:ver:radio':
                            case 'catalogo:documentosproceso:ver':
                            case 'catalogo:documentoproceso:nuevo':
                            case 'catalogo:areadoc:nuevo':
                            case 'catalogo:indicadoresproceso:nuevo':
                            case 'catalogo:valoresindicador:editar:fila':
                            case 'catalogo:objetivosindicador:editar:fila':

                            case 'editor:documentos:ver':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                                break;

                            case 'recursos:fichapersonal:pdf:fila':
                            case 'formacion:reqpuesto:pdf:fila' :
                                $this->sHtml = "contenedor|<iframe id=\"pdf\" src=\"creaFicha.php?id=" . $_SESSION['pagina'][$this->aParametros['numeroDeFila']] . "&tipo=requisito\" width=\"100%\" height=\"600px\"" . " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                                break;


                            case 'catalogo:verdocumentoprocesosinfila:ver':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Ficha_Proceso($sMenu, $this->aParametros, 'id');
                                break;


                            case 'catalogo:verdocumentoprocesosinfilahistorial:ver:fila':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Ficha_Proceso($sMenu, $this->aParametros);
                                break;

                            case 'catalogo:verdocumentoprocesosinfilaborrador:ver:fila':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Ficha_Proceso($sMenu, $this->aParametros);
                                break;

                            case 'indicadores:graficaindicador:ver:grafica':
                            case 'catalogo:graficaindicador:ver:fila':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = $Comunes->procesa_Grafica_Indicadores($sAccion, $this->aParametros);
                                break;

                            case 'documentos:historicocuestionario:nuevo:fila':
                            case 'aambientales:revision:ver' :
                            case 'aambientales:revisionemergencia:ver':
                            case 'aambientales:matriz:ver' :
                            case 'documentacion:legislacion:ver' :
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado_Medio($sMenu, $this->aParametros);
                                break;

                            case 'administracion:mensajes:ver' :
                            case 'administracion:perfiles:ver' :
                            case 'administracion:usuarios:ver' :
                            case 'administracion:tareas:ver' :
                            case 'administracion:tiposareas:ver' :
                            case 'administracion:documentossg:ver' :
                            case 'administracion:registros:ver' :
                            case 'administracion:normativa:ver' :
                            case 'documentacion:politica:ver' :
                            case 'documentacion:objetivos:ver' :
                            case 'administracion:tipomejora:ver' :
                            case 'administracion:mejoraidioma:idioma:fila':
                            case 'administracion:tiposamb:ver' :
                            case 'administracion:tipoambidioma:idioma:fila':
                            case 'administracion:legaplicable:ver' :
                            case 'administracion:tiposimp:ver' :
                            case 'administracion:tipoimpidioma:idioma:fila':
                            case 'administracion:tipo_cursos:ver':
                            case 'administracion:preguntasleg:nuevo:fila':
                            case 'administracion:criterios:ver':
                            case 'administracion:clientes:ver':
                            case 'administracion:tipodocumento:nuevo':
                            case 'administracion:tipodocidioma:idioma:fila':
                            case 'administracion:ayuda:nuevo':
                            case 'administracion:menus:nuevo':
                            case 'administracion:aspectos:frecuencia':
                            case 'administracion:frecuenciaidioma:idioma:fila':
                            case 'administracion:aspectos:magnitud':
                            case 'administracion:magnitudidioma:idioma:fila':
                            case 'administracion:aspectos:gravedad':
                            case 'administracion:gravedadidioma:idioma:fila':
                            case 'administracion:aspectos:formula':
                            case 'administracion:aspectos:probabilidad':
                            case 'administracion:severidadidioma:idioma:fila':
                            case 'administracion:probabilidadidioma:idioma:fila':
                            case 'administracion:aspectos:severidad':
                            case 'administracion:modulos:nuevo':
                            case 'administracion:centros:nuevo':

                            case 'administracion:proveedores:ver':
                            case 'administracion:proveedores:incidencia':
                            case 'administracion:proveedores:contacto':
                            case 'administracion:proveedores:producto':
                            case 'administracion:equipos:ver':
                            case 'administracion:auditoriaanual:ver':
                            case 'administracion:auditoriavigor:ver':
                            case 'administracion:indicadoresobjetivo:ver':

                                $ProcesaListado=new Procesar_Listados;
                                    $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado_Adm($sMenu, $this->aParametros);
                                break;

                            case 'administracion:hospitales:nuevo':
                                if (($_SESSION['empresa'] == 'Hospital La Candelaria')) {
                                    $ProcesaListado=new Procesar_Listados;
                                    $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado_Adm($sMenu, $this->aParametros);
                                } else {
                                    $this->sHtml = "contenedor|" . gettext('sErrorNoICS');
                                }
                                break;

                            case 'administracion:permisosdocumento:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado_Permisos($sAccion, $this->aParametros);
                                break;

                            case 'documentacion:formatos:verpermisos:fila':
                            case 'documentacion:frl:verpermisos:fila':
                            case 'documentacion:documentoaai:verpermisos:fila':
                            case 'documentacion:documentope:verpermisos:fila':

                            case 'documentacion:planemeramb:verpermisos:fila':
                            case 'documentacion:documentopg:verpermisos:fila':
                            case 'documentacion:documentoarchivoproceso:verpermisos:fila':
                            case 'documentacion:pambiental:verpermisos:fila':
                            case 'documentacion:manual:verpermisos:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" .
                                    $ProcesaListado->procesa_Listado_Permisos_Documentos($sAccion, $this->aParametros);
                                break;

                            case 'mejora:mejorapdf:nuevo:fila':
                                $this->sHtml = "contenedor|<iframe id=\"pdf\" src=\"creaFicha.php?id=" .
                                    $_SESSION['pagina'][$this->aParametros['numeroDeFila']] .
                                    "&tipo=mejora\" width=\"100%\" height=\"600px\"" .
                                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                                break;

                            case 'indicadores:objetivos:verpdf:fila':
                                $this->sHtml = "contenedor|<iframe id=\"pdf\" src=\"creaFicha.php?id=" .
                                    $_SESSION['pagina'][$this->aParametros['numeroDeFila']] .
                                    "&tipo=objetivos\" width=\"100%\" height=\"600px\"" .
                                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                                break;

                            case 'documentacion:objetivos:verpdf:fila':
                                $this->sHtml = "contenedor|<iframe id=\"pdf\" src=\"creaFicha.php?id=" .
                                    $_SESSION['pagina'][$this->aParametros['numeroDeFila']] .
                                    "&tipo=objetivos_generales\" width=\"100%\" height=\"600px\"" .
                                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                                break;


                            case 'catalogo:areadoc:ver:fila':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = $Comunes->asignar_Area($this->aParametros);
                                break;

                            case 'catalogo:proceso:vermatriz:radio':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Matriz_Procesos($sAccion, $this->aParametros);
                                break;

                            case 'mejora:accmejora:verificar:fila' :
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = $Comunes->procesa_Verificar_Mejora($sMenu, $this->aParametros);
                                break;

                            case 'mejora:cerraraccmejora:nuevo:fila' :
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = $Comunes->procesa_Cerrar_Mejora($sMenu, $this->aParametros);
                                break;

                            case 'auditorias:horarioauditoria:ver:fila':
                            case 'administracion:idiomas:nuevo':
                            case 'administracion:menu:verbotones:fila':
                            case 'administracion:idiomas:ver:fila':
                            case 'administracion:idiomasboton:ver:fila':
                            case 'formacion:alumno:nuevo':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado_Nuevo($sMenu, $this->aParametros);
                                break;

                            case 'proveedores:proveedor:ver:fila' :
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_Proveedor($this->aParametros);
                                break;

                            case 'equipos:calendario:ver':
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "|contenedor|" . $Comunes->procesa_Calendario($this->aParametros);
                                break;

                            case 'equipos:equipo:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_Equipo($sAccion, $this->aParametros);
                                break;

                            case 'proveedores:producto:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_Producto($this->aParametros);
                                break;

                            case 'proveedores:contacto:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_Contacto($this->aParametros);
                                break;

                            case 'proveedores:incidencia:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_Incidencia($this->aParametros);
                                break;

                            case 'inicio:mensajes:ver:fila' :
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Mensaje($this->aParametros);
                                break;

                            case 'documentos:preguntashistorico:ver:fila':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_HistoricoPreguntas($this->aParametros);
                                break;

                            case 'documentos:preguntashistoricoimprimir:ver':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Ver_HistoricoPreguntasImprimir($this->aParametros);
                                break;


                            case 'documentacion:legislacion:verley:fila':
                                $this->sHtml = "contenedor|<iframe id=\"arbol\" src=\"muestrabinario.php?id=" . $_SESSION['pagina'][$this->aParametros['numeroDeFila']] . "&tipo=ley\" width=\"100%\" frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
                                break;

                            case 'indicadores:indicadores:vermatriz' :
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = $Comunes->procesa_Matriz_Indicadores($sAccion, $this->aParametros);
                                break;

                            case 'formacion:planes:seleccionadocumentoext':
                            case 'auditorias:auditor:seleccionausuario':
                            case 'formacion:planes:seleccionausuario':
                            case 'administracion:usuarios:seleccionaficha':
                            case 'documentacion:legislacion:seleccionaficha':
                            case 'documentacion:legislacion:seleccionaley':
                            case 'administracion:usuarios:seleccionarequisitos':
                            case 'mejora:acmejora:seleccionausuario':
                            case 'inicio:tarea:seleccionausuario':
                                $ProcesaListado=new Procesar_Listados;
                                $this->sHtml = "contenedor|" . $ProcesaListado->procesa_Listado($sMenu, $this->aParametros);
                                break;
                        }
                    }
                case 'arbol' :
                    {
                        switch ($sMenu) {
                            case 'procesos:general:ver:arbol_procesos':
                                $arbol = new Procesar_Arbol();
                                $this->sHtml = $arbol->procesa_Arbol_Procesos($sMenu, $this->aParametros);
                                break;

                            case 'administracion:usuarios:selecciona:verPerfil':
                                $arbol = new Procesar_Arbol();

                                $this->sHtml = $arbol->procesa_arbol('verpermisos', $this->aParametros['numeroDeFila'], $sAccion);
                                break;

                            case 'administracion:perfil_doc:editar:fila':
                                $arbol = new Procesar_Arbol();

                                $this->sHtml = $arbol->procesa_arbol('perfil_doc', $this->aParametros['userid'], $sAccion);
                                break;

                            case 'administracion:perfiles:editar:fila':
                                $arbol = new Procesar_Arbol();
                                $this->sHtml = $arbol->procesa_arbol('permisos', $this->aParametros['userid'], $sAccion);
                                break;

                            case 'procesos:catalogos:ver':
                                $arbol = new Procesar_Arbol();

                                $this->sHtml = $arbol->procesa_arbol('procesos:catalogos', NULL, $sAccion);
                                break;
                            case 'administracion:modulos:permisos:fila':
                                $arbol = new Procesar_Arbol();

                                $this->sHtml = $arbol->procesa_arbol('permisosusuarios', $this->aParametros, $sAccion);
                                break;

                        }
                        break;
                    }
            } // switch
        }// fin if
        else {
            switch ($aOpciones[0]) {
                case 'inicio':
                    $this->sHtml = "contenedor|" . gettext('sPInicio');
                    break;

                case 'documentacion':
                    $this->sHtml = "contenedor|" . gettext('sPDocumentacion');
                    break;

                case 'mejora':
                    $this->sHtml = "contenedor|" . gettext('sPAM');
                    break;

                case 'formacion':
                    $this->sHtml = "contenedor|" . gettext('sPFormacion');
                    break;

                case 'auditorias':
                    $this->sHtml = "contenedor|" . gettext('sPAuditoria');
                    break;

                case 'indicadores':
                    $this->sHtml = "contenedor|" . gettext('sPIndicadores');
                    break;

                case 'maspectos':
                    $this->sHtml = "contenedor|" . gettext('sPAmbientales');
                    break;

                case 'proveedores':
                    $this->sHtml = "contenedor|" . gettext('sPProveedores');
                    break;

                case 'administracion':
                    $this->sHtml = "contenedor|" . gettext('sPAplicacion');
                    break;

                case 'procesos':
                    $this->sHtml = "contenedor|" . gettext('sPProcesos');
                    break;

                case 'equipos':
                    $this->sHtml = "contenedor|" . gettext('sPEquipos');
                    break;

                case 'logout':
                    session_unset();
                    session_destroy();
                    $this->sHtml = "contenedor|logout";
                    break;
            }
        }
        if ($sAccion != 'logout') {
            $this->sHtml.="|titulo|";
            $titulo = new Titulos();
            $this->sHtml .= $titulo->getTitulo($sAccion);
        }
        return $this->sHtml;
    }
    //Fin procesar


    /**
     *    Esta funcion nos devuelve el codigo en la forma "division|contenido"
     *
     * @access public
     * @return string
     */

    function devolver()
    {
        return $this->sHtml;
    }
    //Fin devolver

}
