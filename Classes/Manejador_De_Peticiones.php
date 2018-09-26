<?php
namespace Tuqan\Classes;
/**
 * Created on 11-nov-2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 * Este es la clase que recibe el codigo de las peticiones y obtiene los parametros para
 * que un objeto procesador de peticiones gestione la peticion, preparando los datos convenientemente
 */

/** Cambiado por Javier Martinez el 12/06/2006 */
class Manejador_De_Peticiones
{

    /**
     *    Esta es la peticion en curso
     * @access private
     * @var string
     */

    private $sCodigo;

    /**
     * Datos adicionales para la peticion en curso
     * @access private
     * @var array
     */

    private $aDatos;

    /**
     * Manejador_De_Peticiones constructor.
     * @param $sPeticion
     * @param $aDatos
     */
    function __construct($sPeticion, $aDatos)
    {
        $this->sCodigo = $sPeticion;
        $this->aDatos = $aDatos;
    }

    //Fin __construct

    /**
     *    Esto nos devuelve los parametros necesarios para el procesamiento
     * 
     * @return array
     */

    public function devuelve_Parametros()
    {
        global $iDebug;
        $aOpciones = explode(":", $this->sCodigo, 5);

        // Si el tercer campo de la URL esta definido entonces tenemos un formulario, un listado,
        // un arbol o upload de ficheros.
        if ($aOpciones[2]) {
            if (isset ($aOpciones[4])) {
                $sMenu = $aOpciones[0] . ":" . $aOpciones[1] . ":" . $aOpciones[3] . ":" . $aOpciones[4];
            }
            else {
                $sMenu = $aOpciones[0] . ":" . $aOpciones[1] . ":" . $aOpciones[3];
            }
            switch ($aOpciones[2]) {
                /*
                 *  Si mandamos un formulario
                 * */

                case 'ayuda':
                    {
                        $Ayuda = new Manejador_Ayuda();
                        $aParametros = $Ayuda->prepara_Ayuda($sMenu);
                        break;
                    }

                case 'excel':
                    {
                        $aParametros['accion'] = $this->sCodigo;
                        break;
                    }

                case 'editor':
                    {
                        switch ($sMenu) {
                            case 'editor:documento:nuevo':
                                {
                                    $Editor = new Manejador_Editor();
                                    $aParametros = $Editor->prepara_Editor($this->sCodigo);
                                    break;
                                }
                            case 'editor:documento:editar:fila':
                                {
                                    $Editor = new Manejador_Editor();
                                    $aParametros = $Editor->prepara_Editor_Editar($this->sCodigo, $this->aDatos);
                                    break;
                                }
                        }
                        break;
                    }

                case 'comun':

                    switch ($sMenu) {

                        case 'inicio:mensajes:estadisticas':
                        case 'administracion:menu:estructura':
                            $Comunes = new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Menu($this->sCodigo, null);
                            break;

                        case 'general:busqueda:nuevo:listado':
                            $Listados = new Manejador_Listados();
                            $aParametros = $Listados->prepara_Listado($this->aDatos);
                            break;

                        case 'catalogo:fichaproceso:ver:radio':
                            $Listados = new Manejador_Listados();
                            $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;
                        case 'documentacion:objetivos:nuevaversion:fila':
                        case 'auditorias:adjunto:ver:fila':

                        case 'documentacion:objetivos:seguimiento:fila':
                        case 'documentacion:seguimiento:ver:fila':
                        case 'formacion:adjunto:ver:fila':
                        case 'administracion:perfil:copiar:fila':
                            $aParametros['accion'] = $this->sCodigo;
                            $aParametros['numeroDeFila'] = $this->aDatos[0];
                            break;

                        case 'documentacion:formatos:cambiar:actualizar':

                        case 'documentacion:frl:cambiar:actualizar':

                        case 'documentacion:documentoaai:cambiar:actualizar':

                        case 'documentacion:documentope:cambiar:actualizar':

                        case 'documentacion:documentopg:cambiar:actualizar':

                        case 'documentacion:pambiental:cambiar:actualizar':

                        case 'documentacion:manual:cambiar:actualizar':

                        case 'administracion:permisos:cambiar:actualizar':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Permisos($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'general:general:aform:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_MandarAFormulario($this->sCodigo, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:general:verdocumentosinfila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_DocumentoSinFila($this->aDatos, $this->sCodigo);
                            break;

                        case 'auditorias:auditor:seleccionausuario':
                        case 'formacion:planes:seleccionadocumentoext':
                        case 'formacion:planes:seleccionausuario':
                        case 'administracion:usuarios:seleccionaficha':
                        case 'documentacion:legislacion:seleccionaley':
                        case 'documentacion:legislacion:seleccionaficha':
                        case 'administracion:usuarios:seleccionarequisitos':
                        case 'mejora:acmejora:seleccionausuario':
                        case 'inicio:tarea:seleccionausuario':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Listado_InicialSeleccionar($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'inicio:tarea:seleccionaindicadores':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Listado_InicialSeleccionar($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'recursos:general:nuevo:calendario':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Calendario_Mes($this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'indicadores:graficaindicador:ver:grafica':
                        case 'catalogo:indicadores:ver:grafica':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_Grafica($sMenu, $this->aDatos);
                            break;

                        case 'catalogo:nuevaversionproceso:nuevo':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_NuevaVersion_DocumentoProceso($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'formacion:inscripcion:baja:fila':
                        case 'formacion:inscripcion:alta:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Alta_Baja($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;


                        case 'inicio:tarea:baja:general':
                        case 'documentacion:meta:baja:general':
                        case 'administracion:tareas:baja:general':
                        case 'indicadores:metaobjetivosindicadores:baja:general':
                        case 'auditorias:auditor:baja:fila':
                        case 'auditorias:areaauditoria:alta:fila':
                        case 'auditorias:areaauditoria:baja:fila':
                        case 'auditorias:auditoria:baja:general':
                        case 'proveedores:criterios:alta:general':
                        case 'proveedores:criterios:baja:fila':
                        case 'equipos:listado:baja:general':
                        case 'proveedores:producto:revisa:general':
                        case 'proveedores:proveedores:baja:general':
                        case 'proveedores:criterios:baja:general':
                        case 'formacion:profesores:baja:general':
                        case 'formacion:planes:baja:fila':
                        case 'formacion:plantilla_curso:baja:general':
                        case 'formacion:alumno:alta:general':
                        case 'formacion:alumno:baja:general':
                        case 'formacion:cursos:baja:general':
                        case 'inicio:mensajes:baja:general':
                        case 'administracion:impacto:baja:general':
                        case 'administracion:preguntasleg:baja:general':
                        case 'administracion:documentosformato:baja:general':
                        case 'administracion:documentosformato:alta:general':
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
                        case 'catalogo:documentoproceso:alta:general':
                        case 'catalogo:documentoproceso:baja:general':
                        case 'catalogo:indicadoresproceso:alta:general':
                        case 'catalogo:indicadoresproceso:baja:general':
                        case 'catalogo:valores:baja:general':
                        case 'catalogo:objetivos_indicadores:baja:general':
                        case 'procesos:catalogo:baja:radio':

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

                            $Comunes= new Manejador_Funciones_Comunes();

                            $aParametros = $Comunes->prepara_AltaBaja_Filas($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'formacion:confirmaralumno:alta:fila':
                        case 'formacion:confirmaralumno:baja:fila':
                        case 'formacion:cursos:curso:baja':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Alta_Baja($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'proveedores:producto:revisar:fila':
                        case 'auditorias:programa:revision:fila':
                        case 'formacion:planes:hacervigente:fila':
                        case 'auditoria:programa:hacervigente:fila':
                        case 'auditoria:programa:copiar:fila':
                        case 'indicadores:graficaindicador:ver:fila':
                        case 'catalogo:flujograma:nuevo':
                        case 'documentacion:objetivos:aprobar:fila':
                        case 'documentacion:objetivos:revisar:fila':
                        case 'indicadores:objetivo:aprobar:fila':
                        case 'indicadores:objetivo:revisar:fila':
                        case 'administracion:hospitales:generar:fila':
                            $Listados = new Manejador_Listados();
                            $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:general:verdocumento':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_DocumentoSinFila($this->aDatos, $this->sCodigo);
                            break;

                        case 'documentossg:general:documento:editadocumentosinfila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            unset($_SESSION['tipodoc']);
                            unset($_SESSION['iddoc']);
                            unset($_SESSION['idtipo']);
                            $aParametros = $Comunes->prepara_Editar_DocumentoSinFila($this->aDatos, $this->sCodigo);
                            break;

                        case 'documentos:general:iframe:nuevaversion':
                        case 'documentacion:general:editarversion':
                            $aParametros = $this->aDatos;
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:general:aprobardocumento':
                        case 'documentacion:general:revisardocumento':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_RevisarAprobar_Documento($this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:general:asignatarea':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Tarea_Documento($this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:general:nuevaversion':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_NuevaVersion_Documento($this->aDatos, $this->sCodigo);
                            break;

                        case 'documentos:documentope:ver:fila':

                        case 'documentos:planemeramb:ver:fila':
                        case 'documentos:documentopg:ver:fila':
                        case 'documentos:documentoarchivoproceso:ver:fila':
                        case 'documentos:objetivos:ver:fila':
                        case 'documentos:frl:ver:fila':
                        case 'documentos:pambiental:ver:fila':
                        case 'documentos:manual:ver:fila':
                        case 'documentos:vigor:ver:fila':
                        case 'documentos:docformatos:ver:fila':
                        case 'documentos:borrador:ver:fila':
                        case 'documentos:documentoaai:ver:fila':
                        case 'documentos:documentonormativa:ver:fila':
                        case 'documentacion:general:ver:fila':
                        case 'catalogo:documentosproceso:ver:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_Documento($this->aDatos, $this->sCodigo);
                            break;
                        case 'documentos:legislacion:ver:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_Legislacion($sMenu, $this->aDatos);
                            break;

                        case 'administracion:permisos:botones':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Datos_Permisos_Botones($this->aDatos[0], $this->sCodigo);
                            break;

                        case 'administracion:permisos:menu':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Datos_Permisos_Menu($this->aDatos[0], $this->sCodigo);
                            break;

                        case 'administracion:permisobotones:cambiar:permisos':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Permisos_Botones($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'administracion:permisomenu:cambiar:permisos':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Permisos_Menu($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'inicio:tareas:ver:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_Fila($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;


                    }
                    break;

                case 'upload':
                    switch ($sMenu) {
                        case 'documentossg:general:nuevo':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Iframe_Documento($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        /*    case 'auditorias:iframe:nuevo:adjunto':
                                $Listados = new Manejador_Listados();
                                $aParametros= prepara_Ver_Fila_Comun($sMenu,$this->aDatos);
                                $aParametros['accion']=$this->sCodigo;
                                break;*/

                        case 'catalogo:flujograma:iframe':
                            $Listados = new Manejador_Listados();
                            $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;
                    }
                    break;

                case 'detalles':
                    switch ($sMenu) {
                        case 'documentacion:borrador:ver:fila':
                        case 'documentacion:documentope:ver:fila':

                        case 'documentacion:planemeramb:ver:fila':
                        case 'documentacion:documentopg:ver:fila':

                        case 'documentacion:pambiental:ver:fila':
                        case 'documentacion:manual:ver:fila':
                        case 'documentacion:vigor:ver:fila':
                        case 'documentacion:formatos:ver:fila':
                        case 'documentacion:documentonormativa:ver:fila':
                        case 'documentacion:documentoarchivoproceso:ver:fila':
                        case 'documentos:documentoaai:ver:fila':
                        case 'documentacion:frl:ver:fila':
                            $Detalles = new Manejador_Detalles();
                            $aParametros = $Detalles->prepara_Detalles_Documento($this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'auditorias:auditoria:ver:fila':
                        case 'documentacion:objetivos:ver:fila':
                            //case 'documentacion:frl:ver:fila':
                        case 'equipos:mantenimiento:ver:fila':
                            $Comunes= new Manejador_Funciones_Comunes();
                            $aParametros = $Comunes->prepara_Ver_Fila($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'catalogo:proceso:ver:radio':
                            $Listados = new Manejador_Listados();
                            $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'inicio:documentoid:ver:fila':
                            $Detalles = new Manejador_Detalles();
                            $aParametros = $Detalles->prepara_Detalles_DocumentoId($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'formacion:inscripcion:ver:fila':
                            $Detalles = new Manejador_Detalles();
                            $aParametros = $Detalles->prepara_Detalles($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'aambientales:matriz:ver':
                            {
                                $Comunes= new Manejador_Funciones_Comunes();
                                $aParametros = $Comunes->prepara_Menu($this->sCodigo, NULL);
                                break;
                            }
                    }
                    break;


                case 'formulario':
                    switch ($sMenu) {

                        case 'inicio:nuevo:general':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario($this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:formulariomedio:nuevo':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Medio($this->sCodigo, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:legislacion:nuevo':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_Comun($this->sCodigo);
                            break;

                        case 'indicadores:valor:editar:fila':
                        case 'documentacion:legislacion:editar:fila':
                        case 'auditorias:plan:planauditoria:editar':
                        case 'auditorias:auditoria:editar:fila':
                        case 'proveedores:proveedor:editar:fila':
                        case 'auditorias:plan:editar:fila':
                        case 'auditorias:informeauditoria:editar:fila':
                        case 'auditorias:horarioauditoria:editar:fila':
                        case 'administracion:criterio:editar:fila':
                        case 'administracion:cliente:editar:fila':
                        case 'administracion:idiomaboton:editar:fila':
                        case 'administracion:idiomamenu:editar:fila':
                        case 'equipos:mantenimientoprev:editar:fila':
                        case 'equipos:mantenimientocorr:editar:fila':
                        case 'equipos:equipo:crear:editar':
                        case 'equipos:equipo:editar:fila':
                        case 'proveedores:incidencia:editar:fila':
                        case 'proveedores:producto:editar:fila':
                        case 'proveedores:contacto:editar:fila':
                        case 'proveedores:productofila:editar:fila':
                        case 'proveedores:contactosfila:editar:fila':
                        case 'proveedores:incidenciafila:editar:fila':
                        case 'formacion:profesor:editar:fila':
                        case 'formacion:cursoplan:editar:fila':
                        case 'formacion:cursoplandetalles:editar:fila':
                        case 'administracion:tipocurso:editar:fila':
                        case 'administracion:impacto:editar:fila':
                        case 'administracion:tipoimpidioma:editar:fila':
                        case 'administracion:preguntasleg:editar:fila':
                        case 'administracion:tipoamb:editar:fila':
                        case 'administracion:tipoambidioma:editar:fila':
                        case 'administracion:tipoarea:editar:fila':
                        case 'administracion:mejora:editar:fila':
                        case 'administracion:mejoraidioma:editar:fila':
                        case 'administracion:passwordusuario:editar:fila':
                        case 'administracion:usuario:editar:fila':
                        case 'administracion:perfil:editar:fila':
                        case 'administracion:area:editar:fila':
                        case 'administracion:mensajes:editar:fila':
                        case 'administracion:menu:editar:fila':
                        case 'administracion:boton:editar:fila':
                        case 'administracion:tipodocumento:editar:fila':
                        case 'administracion:tipodocidioma:editar:fila':
                        case 'administracion:ayuda:editar:fila':
                        case 'aambientales:aspectoemergencia:editar:fila':
                        case 'aambientales:aspecto:editar:fila':
                        case 'administracion:magnitud:editar:fila':
                        case 'administracion:magnitudidioma:editar:fila':
                        case 'administracion:frecuencia:editar:fila':
                        case 'administracion:frecuenciaidioma:editar:fila':
                        case 'administracion:gravedad:editar:fila':
                        case 'administracion:gravedadidioma:editar:fila':
                        case 'administracion:probabilidad:editar:fila':
                        case 'administracion:probabilidadidioma:editar:fila':
                        case 'administracion:severidad:editar:fila':
                        case 'administracion:severidadidioma:editar:fila':
                        case 'administracion:formula:editar:fila':
                        case 'indicadores:objetivo:editar:fila':
                        case 'indicadores:indicador:editar:fila':
                        case 'formacion:planes:editar:fila':
                        case 'formacion:cursos:editar:fila':
                        case 'formacion:datospuesto:editar:fila':
                        case 'formacion:reqpuesto:editar:fila':
                        case 'formacion:fichapersonal:editar:fila':
                        case 'mejora:acmejora:editar:fila':
                        case 'formacion:fpdp:editar:fila':
                        case 'formacion:fpinc:editar:fila':
                        case 'formacion:fpfor:editar:fila':
                        case 'formacion:fppre:editar:fila':
                        case 'formacion:fpidiomas:editar:fila':
                        case 'formacion:fpcursos:editar:fila':
                        case 'formacion:fpft:editar:fila':
                        case 'formacion:fpel:editar:fila':
                        case 'formacion:fpcp:editar:fila':
                        case 'formacion:fpcd:editar:fila':
                        case 'documentacion:objetivos:editar:fila':
                        case 'formacion:competenciasrq:editar:fila':
                        case 'formacion:formacionrq:editar:fila':
                        case 'formacion:promocionrq:editar:fila':
                        case 'formacion:formaciontecnicarq:editar:fila':
                        case 'documentacion:meta:editar:fila':
                        case 'indicadores:metaobjetivosindicadores:editar:fila':
                        case 'documentacion:seguimiento:editar:fila':
                        case 'administracion:centros:editar:fila':
                        case 'catalogo:valor:editar:fila':
                        case 'catalogo:objindicador:editar:fila':
                        case 'administracion:hospitales:editar:fila':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_Editar_Comun($this->sCodigo, $this->aDatos);
                            break;

                        case 'inicio:mensajes:nuevo':
                        case 'auditorias:programa:nuevo':
                        case 'auditorias:plan:planauditoria:nuevo':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_Calidad($sMenu);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'auditorias:programa:editar:fila':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_Editar_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentos:cuestionario:nuevo:fila':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Cuestionario($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'formacion:cursoplan:nuevo:fila':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Agregar_Fila_Comun($sMenu, $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'catalogo:proceso:editar:radio':
                        case 'catalogo:contenidoproc:editar':
                        case 'catalogo:contenidoproceso:editar:fila':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_EditarId('editar:contenidoproc', $this->aDatos);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'documentacion:frlvigor:nuevo':
                        case 'documentacion:frl:nuevo':
                        case 'documentacion:documentonormativavigor:nuevo':
                        case 'documentacion:documentope:nuevo':

                        case 'documentacion:planemeramb:nuevo':
                        case 'documentacion:documentopg:nuevo':
                        case 'documentacion:documentoarchivoproceso:nuevo':
                        case 'documentacion:manual:nuevo':
                        case 'documentacion:pambiental:nuevo':
                        case 'documentacion:formatos:nuevo':
                        case 'documentacion:documentoaai:nuevo':
                        case 'documentacion:documentonormativa:nuevo':
                        case 'formacion:adjunto:nuevo:fila':
                        case 'documentacion:seguimiento:nuevo:fila':
                        case 'auditorias:adjunto:nuevo:fila':
                            $Form = new Manejador_Formularios();
                            unset($_SESSION['tipodoc']);
                            unset($_SESSION['iddoc']);
                            $aParametros = $Form->prepara_Nuevo_Documento($sMenu, $this->aDatos, $this->sCodigo);
                            break;

                        case 'catalogo:proceso:nuevo:radio':
                            $Form = new Manejador_Formularios();
                            $_SESSION['padre'] = $this->aDatos[0];
                            $aParametros = $Form->prepara_Formulario_Inicial_Comun($sMenu);
                            $aParametros['accion'] = $this->sCodigo;
                            break;

                        case 'administracion:magnitud:nuevo':
                        case 'administracion:magnitudidioma:nuevo':
                        case 'administracion:frecuencia:nuevo':
                        case 'administracion:frecuenciaidioma:nuevo':
                        case 'administracion:gravedad:nuevo':
                        case 'administracion:gravedadidioma:nuevo':
                        case 'administracion:severidad:nuevo':
                        case 'administracion:severidadidioma:nuevo':
                        case 'administracion:probabilidad:nuevo':
                        case 'administracion:probabilidadidioma:nuevo':
                        case 'auditorias:auditor:nuevo':
                        case 'auditorias:auditoria:nuevo':
                        case 'formacion:formaciontecnicarq:nuevo':
                        case 'auditorias:horarioauditoria:nuevo':
                        case 'administracion:criterio:nuevo':
                        case 'administracion:cliente:nuevo':
                        case 'administracion:mejora:nuevo':
                        case 'administracion:mejoraidioma:nuevo':
                        case 'equipos:mantenimientoprev:nuevo':
                        case 'equipos:mantenimientocorr:nuevo':
                        case 'equipos:equipo:nuevo':
                        case 'administracion:hospitales:nuevo':
                        case 'proveedores:producto:nuevo':
                        case 'proveedores:contacto:nuevo':
                        case 'proveedores:proveedor:nuevo':
                        case 'proveedores:incidencia:nuevo':
                        case 'proveedores:incidenciafila:nuevo':
                        case 'proveedores:contactosfila:nuevo':
                        case 'proveedores:productofila:nuevo':
                        case 'formacion:profesor:nuevo':
                        case 'administracion:idiomamenu:nuevo':
                        case 'formacion:alumno:crear:nuevo':
                        case 'formacion:mensajetodos:crear:nuevo':
                        case 'formacion:planes:nuevo':
                        case 'administracion:tipocurso:nuevo':
                        case 'administracion:impacto:nuevo':
                        case 'administracion:tipoimpidioma:nuevo':
                        case 'administracion:preguntasleg:nuevo':
                        case 'administracion:tipoamb:nuevo':
                        case 'administracion:tipoambidioma:nuevo':
                        case 'administracion:tipoarea:nuevo':
                        case 'administracion:mensajes:nuevo':
                        case 'administracion:area:nuevo':
                        case 'administracion:perfil:nuevo':
                        case 'administracion:idioma:nuevo':
                        case 'administracion:usuario:nuevo':
                        case 'administracion:tipodocumento:nuevo':
                        case 'administracion:tipodocidioma:nuevo':
                        case 'administracion:ayuda:nuevo':
                        case 'administracion:menu:nuevo':
                        case 'administracion:boton:nuevo':
                        case 'aambientales:aspecto:nuevo':
                        case 'aambientales:aspectoemergencia:nuevo';
                        case 'indicadores:objetivo:nuevo':
                        case 'indicadores:indicador:nuevo':
                        case 'indicadores:valor:nuevo':
                        case 'indicadores:valorunico:nuevo':
                        case 'formacion:cursos:nuevo':
                        case 'formacion:fichapersonal:nuevo':
                        case 'formacion:reqpuesto:nuevo':
                        case 'mejora:acmejora:nuevo':
                        case 'documentacion:meta:nuevo':

                        case 'documentacion:seguimiento:nuevo':
                        case 'administracion:centros:nuevo':
                        case 'indicadores:metaobjetivosindicadores:nuevo':
                        case 'catalogo:valor:nuevo':
                        case 'catalogo:valorunico:nuevo':
                        case 'catalogo:objindicador:nuevo':
                        case 'documentacion:objetivos:nuevo':
                            $Form = new Manejador_Formularios();
                            $aParametros = $Form->prepara_Formulario_Inicial_Comun($sMenu);
                            $aParametros['accion'] = $this->sCodigo;
                            break;
                    }
                    break;

                // Si tenemos un listado como opción
                case 'listado':
                    {
                        $_SESSION['ultimolistado'][] = $this->sCodigo;
                        $_SESSION['ultimolistadodatos'][] = $this->aDatos;
                        $_SESSION['paginaanterior'][] = $_SESSION['pagina'];
                        switch ($sMenu) {
                            case 'auditorias:areaauditoria:nuevo':
                            case 'equipos:revision:ver':
                            case 'equipos:listado:ver':
                            case 'proveedores:listado:ver':
                            case 'proveedores:incidencias:ver':
                            case 'proveedores:contactos:ver':
                            case 'proveedores:productos:ver':
                            case 'proveedores:phomologados:ver':
                            case 'mejora:listado:ver':
                            case 'inicio:tareas:ver':
                            case 'inicio:mensajes:ver':
                            case 'inicio:mensajes:inicial':
                            case 'formacion:cursos:ver':
                            case 'formacion:inscripcion:ver':
                            case 'formacion:planes:ver':
                            case 'auditorias:programa:ver':
                            case 'auditorias:plan:ver':
                            case 'indicadores:indicadores:ver':
                            case 'indicadores:objetivos:ver':
                            case 'documentacion:manual:ver':
                            case 'documentacion:pg:ver':
                            case 'documentacion:procesoarchivo:ver':
                            case 'documentacion:pe:ver':

                            case 'documentacion:planamb:ver':
                            case 'documentacion:docvigor:ver':
                            case 'documentacion:frl:ver':
                            case 'documentacion:docborrador:ver':
                            case 'documentacion:registros:ver':
                            case 'documentacion:docformatos:ver':
                            case 'documentacion:normativa:ver':
                            case 'documentacion:documentonormativa:ver':
                            case 'documentacion:aai:ver':

                            case 'administracion:aspectos:magnitud':
                            case 'administracion:aspectos:gravedad':
                            case 'administracion:aspectos:frecuencia':
                            case 'administracion:aspectos:formula':
                            case 'administracion:aspectos:probabilidad':
                            case 'administracion:aspectos:severidad':
                            case 'administracion:modulos:nuevo':
                            case 'administracion:centros:nuevo':

                            case 'editor:documentos:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'catalogo:areadoc:nuevo':
                            case 'catalogo:documentoproceso:nuevo':
                            case 'catalogo:indicadoresproceso:nuevo':
                            case 'equipos:planmantenimientoid:nuevo':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Sec($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'aambientales:revision:ver':
                            case 'documentacion:legislacion:ver':
                            case 'aambientales:revisionemergencia:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Medio($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'equipos:calendario:ver':
                                $Comunes= new Manejador_Funciones_Comunes();
                                $aParametros = $Comunes->prepara_Calendario_Anual($sMenu, $this->aDatos, $this->sCodigo);
                                //$aParametros['accion']=$this->sCodigo;
                                break;

                            case 'inicio:historicomensajes:ver':
                            case 'formacion:planes:plan:nuevo':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Comun($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:preguntasleg:nuevo:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila_Adm($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'documentacion:general:verhistorico':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Historico($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'documentos:historicocuestionario:nuevo:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila_Medio($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'documentos:preguntashistorico:ver:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_HistoricoPreguntas($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'documentos:preguntashistoricoimprimir:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_HistoricoPreguntasImprimir($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:mensajes:ver':
                            case 'administracion:perfiles:ver':
                            case 'administracion:usuarios:ver':
                            case 'administracion:tareas:ver':
                            case 'administracion:tiposareas:ver':
                            case 'administracion:documentossg:ver':
                            case 'administracion:registros:ver':
                            case 'administracion:normativa:ver':
                            case 'administracion:tipomejora:ver':
                            case 'administracion:tiposamb:ver':
                            case 'administracion:tipodocumento:ver':
                            case 'administracion:ayuda:ver':
                            case 'administracion:legaplicable:ver':
                            case 'administracion:tiposimp:ver':
                            case 'administracion:tipo_cursos:ver':
                            case 'administracion:criterios:ver':
                            case 'administracion:clientes:ver':
                            case 'administracion:tipodocumento:nuevo':
                            case 'administracion:ayuda:nuevo':
                            case 'administracion:menus:nuevo':
                            case 'documentacion:politica:ver':
                            case 'documentacion:objetivos:ver':
                            case 'administracion:hospitales:nuevo':
                            case 'administracion:proveedores:ver':
                            case 'administracion:proveedores:incidencia':
                            case 'administracion:proveedores:contacto':
                            case 'administracion:proveedores:producto':
                            case 'administracion:equipos:ver':
                            case 'administracion:auditoriaanual:ver':
                            case 'administracion:auditoriavigor:ver':
                            case 'administracion:indicadoresobjetivo:ver':

                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Adm($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'auditorias:equipoauditor:ver:fila':
                            case 'auditorias:areasauditoria:ver:fila':
                            case 'auditoria:programa:ver:fila':
                            case 'proveedores:productos:ver:fila':
                            case 'proveedores:contactos:ver:fila':
                            case 'proveedores:incidencias:ver:fila':
                            case 'proveedores:productos:criterios:fila':
                            case 'proveedores:productos:productoshistorico:fila':
                            case 'equipos:planmantenimiento:ver:fila':
                            case 'equipos:planmantenimiento:nuevo:fila':
                            case 'formacion:inscripcion:asistentes:fila':
                            case 'formacion:verProfesores:ver:fila':
                            case 'formacion:verAsistentesCurso:nuevo:fila':
                            case 'formacion:planes:ver:fila':
                            case 'documentacion:metas:ver:fila':

                            case 'documentacion:objetivos:seguimiento:fila':
                            case 'indicadores:valoresindicador:ver:fila':
                            case 'catalogo:indicadores:ver:radio':
                            case 'catalogo:documentosproceso:ver':
                            case 'catalogo:valoresindicador:editar:fila':
                            case 'catalogo:objetivosindicador:editar:fila':
                            case 'indicadores:objetivos:vermetas:fila':
                            case 'documentos:registros:listarfila:fila':
                            case 'administracion:tipodocidioma:idioma:fila':
                            case 'administracion:tipoimpidioma:idioma:fila':
                            case 'administracion:tipoambidioma:idioma:fila':
                            case 'administracion:mejoraidioma:idioma:fila':
                            case 'administracion:magnitudidioma:idioma:fila':
                            case 'administracion:gravedadidioma:idioma:fila':
                            case 'administracion:frecuenciaidioma:idioma:fila':
                            case 'administracion:probabilidadidioma:idioma:fila':
                            case 'administracion:severidadidioma:idioma:fila':
                            case 'formacion:reqpuesto:formaciontecnicarq:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'documentacion:listadoregistros:ver:fila':
                                unset ($_SESSION['directo']);
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'formacion:fichapersonal:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, array(1, "directo"));
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'formacion:reqpuesto:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, array(2, "directo"));
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'indicadores:indicadores:vermatriz':
                            case 'indicadores:objetivos:verpdf:fila':
                            case 'documentacion:objetivos:verpdf:fila':
                            case 'mejora:mejorapdf:nuevo:fila':
                            case 'mejora:accmejora:verificar:fila':
                            case 'mejora:cerraraccmejora:nuevo:fila':
                            case 'equipos:equipo:ver:fila':
                            case 'proveedores:producto:ver:fila':
                            case 'proveedores:contacto:ver:fila':
                            case 'proveedores:incidencia:ver:fila':
                            case 'proveedores:proveedor:ver:fila':

                            case 'documentacion:legislacion:verley:fila':
                            case 'recursos:fichapersonal:pdf:fila':
                            case 'formacion:reqpuesto:pdf:fila':
                            case 'inicio:mensajes:ver:fila':
                                //    case 'catalogo:fichaproceso:ver:radio':
                            case 'catalogo:proceso:vermatriz:radio':
                            case 'catalogo:areadoc:ver:fila':
                            case 'catalogo:graficaindicador:ver:fila':
                            case 'catalogo:verdocumentoprocesosinfila:ver':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'catalogo:verdocumentoprocesosinfilaborrador:ver:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'catalogo:verdocumentoprocesosinfilahistorial:ver:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'auditorias:horarioauditoria:ver:fila':
                            case 'administracion:menu:verbotones:fila':
                            case 'administracion:idiomas:ver:fila':
                            case 'administracion:idiomasboton:ver:fila':
                            case 'formacion:alumno:nuevo':
                            case 'administracion:permisosdocumento:ver:fila':
                            case 'proveedores:criterio:nuevo':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila_Nuevo($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:idiomas:nuevo':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Nuevo($sMenu, $this->aDatos, $this->sCodigo);
                                $aParametros['accion'] = $this->sCodigo;
                                break;
                            case ':listarfila:fila':
                                unset ($_SESSION['directo']);
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, $this->aDatos);
                                $sMenu = $aOpciones[0] . ":" . $aOpciones[1] . ":" . $aOpciones[2] . ":" . $aOpciones[3];
                                $aParametros['accion'] = $sMenu;
                                break;
                            case 'formacion:inscripcion:ver:fila:fila':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Listado_Inicial_Fila($sMenu, $this->aDatos);
                                $sMenu = $aOpciones[0] . ":" . $aOpciones[1] . ":" . $aOpciones[2] . ":" . $aOpciones[3];
                                $aParametros['accion'] = $this->sCodigo;
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
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Datos_Permisos_Documentos($sMenu, $this->aDatos, $this->sCodigo);
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
                                $Comunes= new Manejador_Funciones_Comunes();
                                $aParametros = $Comunes->prepara_Listado_InicialSeleccionar($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;
                        }
                        break;
                    }
                    break;

                case 'arbol':
                    {
                        switch ($sMenu) {
                            case 'administracion:usuarios:selecciona:verPerfil':
                                $Listados = new Manejador_Listados();
                                $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:usuarios:ver_permiso':
                                //    require_once 'pruebaarbol.php';
                                //    $sHtml=pinta_perfil($this->aDatos[0]);
                                require_once 'arbol_permisos.php';
                                $sHtml = arbol_permisos_verPerfil($this->aDatos[0]);
                                echo $sHtml;
                                break;

                            case 'procesos:general:ver:arbol_procesos':
                                $Comunes= new Manejador_Funciones_Comunes();
                                $aParametros = $Comunes->prepara_Menu($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:general:ver:arbol_permiso':
                                require_once 'pruebaarbol.php';
                                $sHtml = pinta_arbol($this->aDatos[0]);
                                echo $sHtml;
                                break;

                            case 'administracion:general:ver:arbol_perfil_doc':
                                require_once 'arbol_documentos.php';
                                $sHtml = pinta_arbol($this->aDatos[0], false);
                                echo $sHtml;
                                break;

                            case 'administracion:modulos:permisos':
                                require_once 'arbol_permisos.php';
                                $sHtml = arbol_permisos($this->aDatos[0]);
                                echo $sHtml;
                                break;

                            case 'procesos:catalogos:ver':
                                $Comunes= new Manejador_Funciones_Comunes();
                                unset ($_SESSION['ultimolistado']);
                                unset ($_SESSION['ultimolistadodatos']);
                                unset ($_SESSION['paginaanterior']);
                                $_SESSION['ultimolistado'][] = $this->sCodigo;
                                $_SESSION['ultimolistadodatos'][] = $this->aDatos;
                                $_SESSION['paginaanterior'][] = $_SESSION['pagina'];
                                $aParametros = $Comunes->prepara_Arbol($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:perfil_doc:editar:fila':
                            case 'administracion:perfiles:editar:fila':
                                $Comunes= new Manejador_Funciones_Comunes();
                                $aParametros = $Comunes->prepara_Arbol($sMenu, $this->aDatos);
                                $aParametros['accion'] = $this->sCodigo;
                                break;

                            case 'administracion:modulos:permisos:fila':
                                {
                                    $Listados = new Manejador_Listados();
                                    $aParametros = $Listados->prepara_Ver_Fila_Comun($sMenu, $this->aDatos);
                                    $aParametros['accion'] = $this->sCodigo;
                                    break;
                                }
                        }
                        break;
                    }
            }
        } else {
            switch ($aOpciones[0]) {
                /*
                 *  Si mandamos un formulario
                 * */
                case 'inicio':
                case 'mejora':
                case 'formacion':
                case 'documentacion':
                case 'auditorias':
                case 'indicadores':
                case 'maspectos':
                case 'proveedores':
                case 'equipos':
                case 'procesos':
                case 'administracion':
                case 'logout':
                    $Comunes= new Manejador_Funciones_Comunes();
                    $aParametros = $Comunes->prepara_Menu($this->sCodigo, NULL);
                    $aParametros['accion'] = $aOpciones[0];
                    break;
            }
        }

        return $aParametros;
        //Fin devuelve_Parametros
    }

}

