<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * Este es nuestro archivo base para los formularios
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

use \Estilo_Pagina;
use \HTML_Page;
use \boton;

class Forms
{

    /**
     * Funcion para sacar los array de permisos para los documentos
     */
    function crearArrayPermisos()
    {
        $sArrayPermisos = "{";
        for ($iContador = 1; $iContador < iNumeroPerfiles; $iContador++) {
            if ($_SESSION['perfil'] == $iContador) {
                $sArrayPermisos .= "true,";
            } else {
                $sArrayPermisos .= "false,";
            }
        }
        $sArrayPermisos .= "false}";
        return $sArrayPermisos;
    }

    function formulario($sIdentFormulario, $iId=null)
    {

        $oPagina = new HTML_Page();

        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $oPagina->addScript('/javascript/dhtmlgoodies_calendar.js', "text/javascript");
        $oPagina->addScript('/javascript/form.js', "text/javascript");
        $oPagina->addBodyContent("<div id='formulario'>");
        $aSplit = explode(separadorForm, $sIdentFormulario);
        $iPassword = 0;
        if ($aSplit[3] == 'nuevo') {
            $sTipoForm = 'INSERT';
            $sCabecera = gettext('sFormNuevo');
        } else if ($aSplit[3] == 'editar') {
            $sTipoForm = 'UPDATE';
            if (($aSplit['1'] != 'mantenimientoprev') && ($aSplit['1'] != 'mantenimientocorr')) {
                $sCabecera = gettext('sFormEditar');
            } else {
                $sCabecera = gettext('sFormsNuevo');
            }
        }
        if ($aSplit[1] == 'passwordusuario') {
            $sTipoForm = 'UPDATE';
            $sCabecera = gettext('sFormClave');
            //Aqui ponemos el parametro para que le diga al formulario que es para un cambio de pass
            $iPassword = 1;
        }


        switch ($aSplit[1]) {
            // Para la opcion inicio:mensajes:formulario:nuevo
            case 'mensajes':
                if ($_SESSION['admin']) {
                    $Form = new Form_Administracion();
                    $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                    $sCabecera .= gettext('sFMensajeGeneral');
                } else {
                    $Form = new Form_Comun();
                    $aFormulario = $Form->devuelve_Array_Form('mensajeusuario', $sTipoForm, $iId);
                    $sCabecera .= gettext('sFMensaje');
                }

                break;

            // Para la opcion auditorias:programa:nuevo
            case 'programa':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProgramaAuditoria');
                break;

            // Para la opcion auditorias:plan:formulario:planauditoria:nuevo
            case 'plan':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFPlanAuditoria');
                break;

            // Para la opcion formacion:planes:formulario:editar
            case 'planes':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFPlan');
                break;

            //Para la opcion documentacion:tarea:formulario:nuevo
            case 'tarea':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTarea');
                break;

            //Para la opcion recursos:reqpuesto:formulario:nuevo
            case 'reqpuesto':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFRequisitosPuesto');
                break;

            //Para la opcion formacion:fichapersonal:ver
            case 'fichapersonal':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFFichaPersonal');
                break;

            //Para la opcion documentacion:legislacion:formulario:nuevo
            case 'legislacion':
                $Form = new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFLegislacion');
                break;

            case 'centros':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sBotonArea');
                break;

            //Para la opcion mejora:acmejora:formulario:nuevo
            case 'acmejora':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAccMejora');
                break;

            //Para la opcion formacion:datospuesto:formulario:editar:fila
            case 'datospuesto':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $_SESSION['reqpuesto'] = $iId;
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('requisitos_puesto_datos_puesto'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();
                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                    $iId = $aIterador[0];
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFRequisitosPuesto');
                break;

            //Para la opcion formacion:fpdp:formulario:editar:fila
            case 'fpdp':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_datos_personales'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFFichaPersonal');
                break;

            //Para la opcion formacion:fpinc:formulario:editar:fila           
            case 'fpinc':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_incorporacion'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFFichaPersonal');
                break;

            //Para la opcion formacion:fpfor:formulario:editar:fila             
            case 'fpfor':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_formacion_academica'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                } else {
                    $sTipoForm = 'INSERT';
                }
                $sCabecera = "Ficha de Personal Formacion";
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                //$sCabecera= $sFormFicha;
                break;

            //Para la opcion formacion:fppre:formulario:editar:fila         
            case 'fppre':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_preformacion'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFFichaPersonalPreform');
                //$sCabecera=$sFormFicha;
                break;

            //Para la opcion formacion:fpidiomas:formulario:editar:fila    
            case 'fpidiomas':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_idiomas'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                //$sCabecera= $sFormFicha;
                $sCabecera = gettext('sFFichaPersonalIdiomas');
                break;

            //Parala opcion formacion:fpcursos:formulario:editar:fila    
            case 'fpcursos':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFCurso');
                break;

            //Para la opcion formacion:fpft:formulario:editar:fila    
            case 'fpft':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id,ficha');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_formacion_tecnica'));
                $oDb->construir_Where(array('ficha=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['id_ft'] = $aIterador[0];
                    $_SESSION['ficha_ft'] = $aIterador[1];
                    $sTipoForm = 'UPDATE';
                } else {
                    $_SESSION['ficha_ft'] = $iId;
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFFormacionTecnica');
                break;

            //Para la opcion formacion:fpel:formulario:editar:fila             
            case 'fpel':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id,ficha');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_experiencia_laboral'));
                $oDb->construir_Where(array('ficha=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['id_el'] = $aIterador[0];
                    $_SESSION['ficha_el'] = $aIterador[1];
                    $sTipoForm = 'UPDATE';
                } else {
                    $_SESSION['ficha_el'] = $iId;
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFExperienciaLaboral');
                break;

            //Para la opcion formacion:fpcp:formulario:editar:fila             
            case 'fpcp':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id,ficha');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_cambio_perfil'));
                $oDb->construir_Where(array('ficha=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['id_cp'] = $aIterador[0];
                    $_SESSION['ficha_cp'] = $aIterador[1];
                    $sTipoForm = 'UPDATE';
                } else {
                    $_SESSION['ficha_cp'] = $iId;
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFCambioPerfil');
                break;

            //Para la opcion formacion:fpcd:formulario:editar:fila
            case 'fpcd':
                $Form = new Form_Calidad();

                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id,ficha');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('ficha_personal_cambio_departamento'));
                $oDb->construir_Where(array('ficha=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $_SESSION['id_cd'] = $aIterador[0];
                    $_SESSION['ficha_cd'] = $aIterador[1];
                    $sTipoForm = 'UPDATE';
                } else {
                    $_SESSION['ficha_cd'] = $iId;
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFCambioDepartamento');
                break;

            //Para la opcion formacion:competenciasrq:formulario:editar:fila    
            case 'competenciasrq':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $_SESSION['reqpuesto'] = $iId;
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('requisitos_puesto_competencias'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                    $iId = $aIterador[0];
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFReqPuestoComp');
                break;

            //Para la opcion formacion:formacionrq:formulario:editar:fila
            case 'formacionrq':
                $Form = new Form_Calidad();
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $_SESSION['reqpuesto'] = $iId;
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('requisitos_puesto_formacion'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                    $iId = $aIterador[0];
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFReqPuestoFormacion');
                break;

            //Para la opcion formacion:promocionrq:formulario:editar:fila
            case 'promocionrq':
                $Form = new Form_Calidad();
                require_once 'Manejador_Base_Datos.class.php';
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id');
                $_SESSION['reqpuesto'] = $iId;
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('requisitos_puesto_promocion'));
                $oDb->construir_Where(array('id=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $sTipoForm = 'UPDATE';
                    $iId = $aIterador[0];
                } else {
                    $sTipoForm = 'INSERT';
                }
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFRequisitosProm');
                break;

            //Para la opcion formacion:formaciontecnicarq:formulario:editar:fila
            case 'formaciontecnicarq':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFReqPuestoFormTec');
                break;

            //Para la opcion formacion:cursos:formulario:editar:fila
            case 'cursos':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFCurso');
                break;

            //Para la opcion indicadores:indicador:formulario:nuevo
            case 'indicador':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFIndicador');
                break;

            //Para la opcion indicadores:objetivo:formulario:crear:nuevo
            case 'objetivo':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFObjetivo');
                break;

            //Para la opcion aambientales:aspecto:formulario:nuevo
            case 'aspecto':
            case 'aspectoemergencia':
            $Form = new Form_Medio();
            $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAspectoAmbiental');
                break;

            //Para la opcion administracion:usuario:formulario:crear:nuevo y
            //administracion:passwordusuario:formulario:editar:fila
            case 'passwordusuario':
            case 'usuario':
            $Form = new Form_Administracion();
            //Aqui miramos si es editar el usuario o cambiar su password
                if ($aSplit[1] == 'passwordusuario') {
                    
                    $aFormulario = $Form->devuelve_Array_Pass($aSplit[1], $iId);
                    $sCabecera .= gettext('sFUsuario');
                } elseif (($aSplit[3] == 'nuevo') || ($aSplit[3] == 'editar')) {
                    
                    $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                    $sCabecera .= gettext('sFUsuario');
                }
                //echo "contenedor|".print_r($aFormulario);
                break;

            //Para la opcion administracion:perfil:formulario:crear:nuevo
            case 'perfil':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFPerfil');
                break;

            //Para la opcion administracion:area:formulario:crear:nuevo
            case 'area':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFArea');
                break;

            //Para la opcion administracion:mejora:formulario:general:editar
            case 'mejora':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMejora');
                break;

            case 'mejoraidioma':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMejora');
                break;

            //Para la opcion administracion:tipoarea:formulario:crear:nuevo
            case 'tipoarea':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTipoArea');
                break;

            //Para la opcion administracion:preguntasleg:formulario:nuevo
            case 'preguntasleg':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFPreguntasLegislacion');
                break;

            //Para la opcion administracion:impacto:formulario:nuevo
            case 'impacto':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFImpacto');
                break;

            case 'tipoimpidioma':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFImpacto');
                break;

            //Para la opcion
            case 'tipocurso':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFTipoCurso');
                break;

            //Para la opcion formacion:cursoplan:formulario:nuevo:fila
            case 'cursoplan':
                //
                
                /**
                 *    Le añadimos una 'e' al nombre de la accion para que sepa que tiene que editar
                 *    (los cursoplan tienen un trato especial).
                 */
                if ($aSplit[3] == 'editar') {
                    $Form = new Form_Comun();
                    $aFormulario = $Form->devuelve_Array_Form("e" . $aSplit[1], $sTipoForm, $iId);
                } else {
                    $Form = new Form_Comun();
                    $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                }
                $sCabecera .= gettext('sFCursoPlan');
                break;

            //Para la opcion formacion:mensajetodos:formulario:crear:nuevo
            case 'mensajetodos':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMensajeAllUsers');
                break;

            //Para la opcion formacion:cursoplandetalles:formulariio:crear:editar
            case 'cursoplandetalles':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFCursoPlan');
                break;

            //Para la opcion formacion:profesor:formulario:nuevo
            case 'profesor':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProfesor');
                break;

            //Para la opcion formacion:alumno:formulario:crear:nuevo
            case 'alumno':
                $Form = new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAlumno');
                break;

            //Para la opcion proveedores:proveedor:formulario:nuevo
            case 'proveedor':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProveedor');
                break;

            //Para la opcion proveedores:incidenciafila:formulario:nuevo
            case 'incidenciafila':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFIncidencia');
                break;

            //Para la opcion proveedores:contactosfila:formulario:nuevo
            case 'contactosfila':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFContacto');
                break;

            //Para la opcion proveedores:productofila:formulario:nuevo
            case 'productofila':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProducto');
                break;

            //Para la opcion proveedores:contacto:formulario:crear:nuevo
            case 'contacto':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFContacto');
                break;

            //Para la opcion proveedores:producto:formulario:nuevo
            case 'producto':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProducto');
                break;

            //Para la opcion proveedores:incidencia:formulario:crear:nuevo        
            case 'incidencia':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFIncidencia');
                break;

            //Para la opcion equipos:mantenimientocorr:formulario:editar:fila
            case 'mantenimientocorr':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMantCorrec');
                $sTipoForm = 'INSERT';
                break;

            //Para la opcion equipos:mantenimientoprev:formulario:editar:fila
            case 'mantenimientoprev':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMantPrev');
                $sTipoForm = 'INSERT';
                break;

            //Para la opcion administracion:criterio:formulario:crear:nuevo               
            case 'criterio':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFCriterio');
                break;

            //Para la opcion administracion:cliente:formulario:crear:nuevo
            case 'cliente':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFCliente');
                break;

            //Para la opcion auditorias:auditoria:formulario:nuevo
            case 'auditoria':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAuditoria');
                break;

            //Para la opcion auditorias:auditor:formulario:nuevo             
            case 'auditor':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAuditor');
                break;

            //Para la opcion auditorias:informeauditoria:formulario:crear:editar
            case 'informeauditoria':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFInforme');
                break;

            //Para la opcion procesos:catalogo:formulario:nuevo:radio
            case 'proceso':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFProceso');
                break;

            //Para la opcion documentacion:meta:formulario:nuevo
            case 'meta':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFMeta');
                break;

            case 'seguimiento':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFSeguimiento');
                break;

            case 'metaobjetivosindicadores':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFMeta');
                break;

            case 'normativa':
                $Form = new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFNormativa');
                break;

            //Para la opcion catalogo:valor:formulario:nuevo
            case 'valor':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                if ($sTipoForm == "INSERT") {
                    //Sacamos la frecuencia del indicador para poner el numero de repeticiones
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $oBaseDatos->comienza_transaccion();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('tipo_frecuencia_seg.nombre'));
                    $oBaseDatos->construir_Tablas(array('indicadores', 'tipo_frecuencia_seg'));
                    $oBaseDatos->construir_Where(array('indicadores.id=' . $_SESSION['indicador'], 'indicadores.frecuencia_seg=tipo_frecuencia_seg.id'));
                    $oBaseDatos->consulta();
                    $aFrec = $oBaseDatos->coger_Fila();
                    switch ($aFrec[0]) {
                        case 'Semestral':
                            {
                                $aOpciones['repeticiones'] = 2;
                                break;
                            }
                        case 'Mensual':
                            {
                                $aOpciones['repeticiones'] = 12;
                                break;
                            }
                        case 'Trimestral':
                            {
                                $aOpciones['repeticiones'] = 4;
                                break;
                            }
                        default:
                            {
                                $aOpciones['repeticiones'] = 1;
                                break;
                            }
                    }

                    $iParticiones = 12 / $aOpciones['repeticiones'];
                    $iNumeroMes = 1;
                    $iAgno = date("Y");
                    for ($iCont = 0; $iCont < $aOpciones['repeticiones']; $iCont++) {
                        if ($iCont == 0) {
                            $aOpciones['ponervaloresdefecto']['valores:fecha'] = "01/01/" . $iAgno;
                            $iNumeroMes = $iNumeroMes + $iParticiones;
                        } else {
                            $iContador2 = $iCont + 1;
                            $sCampo = "valores" . $iContador2 . ":fecha";
                            if ($iNumeroMes <= 9) $iMes = "0" . $iNumeroMes;
                            else $iMes = $iNumeroMes;
                            $aOpciones['ponervaloresdefecto'][$sCampo] = "01/" . $iMes . "/" . $iAgno;
                            $iNumeroMes = $iNumeroMes + $iParticiones;
                        }
                    }
                }
                $sCabecera .= gettext('sFValor');
                break;

            //Para la opcion catalogo:valorunico:formulario:nuevo
            case 'valorunico':
                $Form=new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form('valor', $sTipoForm, $iId);
                $sCabecera .= gettext('sFValor');
                break;

            case 'horarioauditoria':
                $Form=new Form_Nuevo();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAuditor');
                break;

            //Para la opcion administracion:menu:formulario:nuevo
            case 'menu':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= "Menu";
                $aFormulario = $aFormulario['form'];
                break;

            //Para la opcion administracion:tipodocumento:formulario:nuevo
            case 'tipodocumento':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTipoDocumento');
                break;

            case 'tipodocidioma':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTipoDocumento');
                break;

            //Para la opcion administracion:ayuda:formulario:nuevo
            case 'ayuda':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFAyuda');
                break;

            //Para la opcion administracion:idioma:formulario:nuevo
            case 'idioma':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFIdioma');
                $aFormulario = $aFormulario['form'];
                break;

            //Para la opcion administracion:boton:formulario:nuevo
            case 'boton':
                $Form=new Form_Nuevo();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFBoton');
                $aFormulario = $aFormulario['form'];
                break;

            //Para la opcion administracion:idiomaboton:formulario:editar:fila
            case 'idiomaboton':
                $Form=new Form_Nuevo();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFIdiomaBoton');
                break;

            //Para la opcion administracion:idiomamenu:formulario:editar:fila
            case 'idiomamenu':
                $Form=new Form_Nuevo();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera = gettext('sFIdiomaMenu');
                break;

            //Para la opcion catalogo:contenidoproceso:formulario:editar:fila
            case 'contenidoproceso':
                $Form = new Form_Calidad();
                if ($_SESSION['dentroform']) {
                    unset ($_SESSION['dentroform']);
                } else {
                    $_SESSION['procesoficha'] = $iId;
                    $_SESSION['dentroform'] = true;
                }
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $oBaseDatos->comienza_transaccion();
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('nombre', 'codigo', 'revision'));
                $oBaseDatos->construir_Tablas(array('procesos'));
                $oBaseDatos->construir_Where(array('id=' . $_SESSION['procesoficha']));
                $oBaseDatos->consulta();
                $aProcesos = $oBaseDatos->coger_Fila();
                //Primero metemos el documento si no estaba metido
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id'));
                $oBaseDatos->construir_Tablas(array('documentos'));
                $oBaseDatos->construir_Where(array('nombre=\'' . $aProcesos[0] . '\'', 'codigo=\'' . $aProcesos[1] . '\''));
                $oBaseDatos->consulta();
                if ($aDoc = $oBaseDatos->coger_Fila()) {
                    $_SESSION['docficha'] = $aDoc[0];
                } else {
                    $sArrayPermisos = crearArrayPermisos();
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('nombre', 'codigo', 'tipo_documento', 'revision', 'activo', 'calidad', 'medioambiente', 'estado',
                        'perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar',
                        'perfil_aprobar', 'perfil_historico', 'perfil_tareas'
                    ));
                    $oBaseDatos->construir_Value(array($aProcesos[0], $aProcesos[1], iIdProceso, $aProcesos[2], 't', 't', 't', iBorrador,
                        $sArrayPermisos, $sArrayPermisos, $sArrayPermisos, $sArrayPermisos,
                        $sArrayPermisos, $sArrayPermisos, $sArrayPermisos));
                    $oBaseDatos->construir_Tablas(array('documentos'));
                    $oBaseDatos->consulta();
                    //Sacamos el valor del documento
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('documentos_id_seq'));
                    $oBaseDatos->consulta();
                    $aId = $oBaseDatos->coger_Fila();
                    $_SESSION['docficha'] = $aId[0];
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('proceso', 'documento', 'anejos', 'indicadores'));
                    $oBaseDatos->construir_Value(array($iId, $_SESSION['docficha'], '{}', '{}'));
                    $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('contenido_procesos_id_seq'));
                    $oBaseDatos->consulta();
                    $aId = $oBaseDatos->coger_Fila();
                    $iId = $aId[0];
                }
                $oBaseDatos->termina_transaccion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFContenidoProceso');
                break;

            //Para la opcion catalogo:contenidoproc:formulario:editar
            case 'contenidoproc':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFContenidoProceso');
                break;

            //Para la opcion catalogo:objindicador:formulario:nuevo
            case 'objindicador':
                $Form=new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFObjetivo');
                break;

            case 'mensajeusuario':
                $Form=new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMensaje');
                break;


            case 'tipoamb':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTipoAmbiente');
                break;

            case 'tipoambidioma':
                $Form=new Form_Administracion();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFTipoAmbiente');
                break;

            case 'objetivos':
                $Form=new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFObjetivosGlobales') . ": ";
                break;

            case 'equipo':
                $Form = new Form_Calidad();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFEquipo');
                break;

            case 'magnitud':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMagnitud');
                break;

            case 'magnitudidioma':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFMagnitud');
                break;

            case 'frecuencia':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFFrecuencia');
                break;

            case 'frecuenciaidioma':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFFrecuencia');
                break;

            case 'gravedad':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFGravedad');
                break;

            case 'gravedadidioma':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFGravedad');
                break;

            case 'formula':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= "Formula";
                break;
            case 'severidad':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFSeveridad');
                break;
            case 'severidadidioma':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFSeveridad');
                break;

            case 'probabilidad':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= "Probabilidad";
                break;

            case 'probabilidadidioma':
                $Form=new Form_Medio();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= "Probabilidad";
                break;

            case 'hospitales':
                $Form=new Form_Comun();
                $aFormulario = $Form->devuelve_Array_Form($aSplit[1], $sTipoForm, $iId);
                $sCabecera .= gettext('sFSeveridad');
                $iPassword = 1;
                break;
        }
        $aDatos = array('formulario' => $aFormulario);
        $aOpciones['login'] = $_SESSION['login'];
        $aOpciones['pass'] = $_SESSION['pass'];
        $aOpciones['db'] = $_SESSION['db'];
        $aOpciones['accion'] = $sIdentFormulario;
        if(isset($_SESSION['formid'])) {
            $aOpciones['id'] = $_SESSION['formid'];
        }
        $aOpciones['tipo'] = $sTipoForm;
        $aOpciones['cabecera'] = $sCabecera;
        $aOpciones['password'] = $iPassword;
        $aOpciones['formrespuesta'] = 'inicio:nuevo:formulario:general';
        $aOpciones['id'] = $iId;
        $form = new genera_Formularios($aDatos, $aOpciones);
        try {
            $form->generar();
        } catch (\HTML_QuickForm2_Exception $e) {
            return "Error en formulario:". $e->getTraceAsString();
        }

        //@todo remove false, it is set to prevent validation
        if (false && $form->validate()) {
            $form->process();
        } else {
            //Si el formulario necesita algun tipo de informacion en la cabecera lo ponemos aqui
            if (isset($_SESSION['texto'])) {
                $oPagina->addBodyContent($_SESSION['texto']);
                unset ($_SESSION['texto']);
            }
            $oPagina->addBodyContent($form->__toString());
            $oPagina->addBodyContent("<br /><br /><input class=\"b_activo\"  type=\"button\" value=\"Atras\" onclick=\"parent.atras(-2)\",'',1)\">");

            $oPagina->addBodyContent("</div>");

            $oPagina->addBodyContent("<div id=\"divcalendario\">");
            $oPagina->addBodyContent("</div>");
        }
        return $aFormulario;
    }


    /**
     * Este es el formulario que usamos para el cuestionario de medioambiente
     */

    function formulario_Medio($iIdLegislacion)
    {
        $sNavegador = $_SESSION['navegador'];
        if ($sNavegador == "Microsoft Internet Explorer") {
            $oBoton = new boton(gettext('sFVolver'), "parent.atras(-1)", "sincheck");
        } else {
            $oBoton = new boton(gettext('sFVolver'), "parent.atras(-2)", "sincheck");
        }
        //Aqui guardamos las ids de las preguntas
        $aDeseado = array(0 => "nada");
        //Y aqui los textos de las pregutnas
        $aPreguntas = array();
        $oEstilo = new Estilo_Pagina($_SESSION['ancho'], $_SESSION['alto'], $_SESSION['navegador']);
        $oPagina = new HTML_Page();
        $oPagina->addStyleDeclaration($oEstilo, 'text/css');

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('valor_deseado', 'pregunta', 'id'));
        $oBaseDatos->construir_Tablas(array('preguntas_legislacion_aplicable'));
        $oBaseDatos->construir_Where(array('(legislacion_aplicable=' . $iIdLegislacion . ')', 'activo=true'));
        $oBaseDatos->consulta();

        $oPagina->addBodyContent(">");

        $oPagina->addBodyContent("<p class=\"cuestionario\">" . gettext('sCuestionario') . "</p>");
        $i = 1;
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aPreguntas[] = $aIterador[1];
            $aDeseado[] = $aIterador[0];

            $oPagina->addBodyContent($aIterador[1] . ":<br />");
            $oPagina->addBodyContent("<input type=\"radio\" name=\"" . $i . "\" value=\"t\">" . gettext('sAfirmacion'));
            $oPagina->addBodyContent("<input type=\"radio\" name=\"" . $i . "\" value=\"f\">" . gettext('sNegacion') . "<br />");
            $oPagina->addBodyContent("<br />");
            $i++;
        }

        $_SESSION['preguntas'] = $aPreguntas;
        $_SESSION['deseado'] = $aDeseado;
        $_SESSION['legislacion'] = $iIdLegislacion;

        //

        //$sNavegador = $_SESSION['navegador']; Lo ponemos arriba

        if ($sNavegador == "Microsoft Internet Explorer") {
            $oPagina->addBodyContent("<input class=\"b_activo\" onMouseOver=\"this.className='b_focus'\" " .
                "onMouseOut=\"this.className='b_activo'\" type=\"submit\" VALUE=\"Enviar\">");

            $oPagina->addBodyContent("<input class=\"b_activo\" onMouseOver=\"this.className='b_focus'\" " .
                "onMouseOut=\"this.className='b_activo'\" type=\"reset\" value=\"Limpiar\"><br />");
        } else {
            $oPagina->addBodyContent("<input class=\"b_activo\" type=\"submit\" VALUE=\"Enviar\">");
            $oPagina->addBodyContent("<input class=\"b_activo\" type=\"reset\" value=\"Limpiar\"><br />");
        }

        $oPagina->addBodyContent("</form><br />");
        $oPagina->addBodyContent($oBoton->to_Html());

        return $oPagina->toHTML();
    }
}
