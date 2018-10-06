<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * Este es nuestro archivo base para los formularios comunes
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

class Form_Comun
{

    /**
     *     Esta funcion toma un manejador de DB y nos saca el array para construir un desplegable en el
     *     generador de formularios
     * @param $oBaseDatos
     * @param $aCampos
     * @param $aTablas
     * @param null $aWheres
     * @param bool $bNinguno
     * @return mixed
     */
    function sacar_Datos_Select(Manejador_Base_Datos $oBaseDatos, $aCampos, $aTablas, $aWheres = null, $bNinguno = false)
    {
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos($aCampos);
        $oBaseDatos->construir_Tablas($aTablas);
        $oBaseDatos->construir_Where($aWheres);
        $oBaseDatos->consulta();

        /**
         *     Devolvemos el array a meter para crear el select, en la key metemos las claves primarias
         */
        if ($bNinguno != false) {
            $aPerfiles['null'] = '[Ninguno]';
        }
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aCadena = str_split($aIterador[1], 48);
            if (count($aCadena) > 1) {
                $aCadena[0] .= '...';
            }
            $aPerfiles[$aIterador[0]] = $aCadena[0];
        }
        return $aPerfiles;
    }

    function devuelve_Array_Form($sFormulario, $sTipoForm, $iId)
    {
        switch ($sFormulario) {
            case 'mensajeusuario':
                if (!isset($sUsuarioDetectado)) {
                    $sUsuarioDetectado = "";
                }
                $aFormulario = array('mensajes' => array(array('etiqueta' => gettext('sFCOContenido') . ': ', 'columna' => 'contenido'),
                    array('etiqueta' => gettext('sFCODestinatario') . ': ', 'columna' => 'destinatario', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuarioDetectado, 'action' => 'parent.sndReq(\'mejora:acmejora:comun:seleccionausuario\',\'\',1,\'mensajes:destinatario\')')),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'origen', 'hidden' => $_SESSION['userid']),
                    array('etiqueta' => gettext('sFCOTitulo') . ': ', 'columna' => 'titulo'),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['mensajes']['id'] = $iId;
                }
                break;

            case 'plan':
                $aFormulario = array('plan_formacion' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCODescripcion') . ': ', 'columna' => 'descripcion'),
                    // array('etiqueta'=>'Calidad: ', 'columna'=> 'calidad', 'check'=>'si'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'medioambiente', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['plan_formacion']['id'] = $iId;
                }
                break;

            case 'planes':
                $aFormulario = array('plan_formacion' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCODescripcion') . ': ', 'columna' => 'descripcion'),
                    // array('etiqueta'=>'Calidad: ', 'columna'=> 'calidad', 'check'=>'si'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'medioambiente', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['plan_formacion']['id'] = $iId;
                }
                break;

            case 'cursos':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipos_cursos');
                $aTiposCurso = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null, true);


                $aFormulario = array('plantilla_curso' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCOTipoCurso') . ': ', 'columna' => 'tipo', 'select' => $aTiposCurso),
                    array('etiqueta' => gettext('sFCOObjetivos') . ': ', 'columna' => 'objetivos'),
                    array('etiqueta' => gettext('sFCOContenidos') . ': ', 'columna' => 'contenido'),
                    array('etiqueta' => gettext('sFCONumeroHoras') . ': ', 'columna' => 'num_horas'),
                    array('etiqueta' => gettext('sFCOMaterialNecesario') . ': ', 'columna' => 'material_necesario'),
                    array('etiqueta' => gettext('sFCOMaterialSum') . ': ', 'columna' => 'material_suministrado'),
                    array('etiqueta' => gettext('sFCOActivo') . ': ', 'columna' => 'activo'),
                    //        array('etiqueta'=>'Calidad: ', 'columna'=> 'calidad', 'check'=>'si'),
                    array('etiqueta' => '', 'columna' => 'medioambiente', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['plantilla_curso']['id'] = $iId;
                }
                break;

            case 'cursoplan':
                /**
                 * Tenemos el id del plan donde guardarlo en sesion, el id del curso por parametro, con la id del curso
                 * (plantilla), sacamos los datos necesarios para copiarlos al curso añadido
                 */

                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'tipo', 'objetivos', 'contenido', 'num_horas', 'material_necesario', 'material_suministrado', 'activo', 'nombre');
                $aTablas = array('plantilla_curso');

                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos($aCampos);
                $oBaseDatos->construir_Tablas($aTablas);
                $oBaseDatos->construir_Where(array('(id=\'' . $iId . '\')'));

                $oBaseDatos->consulta();
                $aDatos = $oBaseDatos->coger_Fila();
                $oBaseDatos->desconexion();
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_estados_curso');
                $aTiposEstadosCurso = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                $aFormulario = array('cursos' => array(array('etiqueta' => gettext('sFCOFechaPrev') . ': ', 'columna' => 'fecha_prevista'),
                    array('etiqueta' => gettext('sFCOResponsable') . ': ', 'columna' => 'responsable', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => "", 'action' => 'parent.sndReq(\'formacion:planes:comun:seleccionausuario\',\'\',1,\'cursos:responsable\')')),
                    // array('etiqueta'=> 'Hoja Firmas: ','columna' => 'hoja_firmas','hidden'=>'null',
                    // 'boton'=> array('label'=>'Seleccionar','valor'=> "",'action'=>'parent.sndReq(\'formacion:planes:comun:seleccionadocumentoext\',\'\',1,\'cursos:hoja_firmas\')')),
                    array('etiqueta' => gettext('sFCOLugar') . ': ', 'columna' => 'lugar'),
                    array('etiqueta' => gettext('sFCOEstado') . ': ', 'columna' => 'estado', 'select' => $aTiposEstadosCurso),
                    array('etiqueta' => '', 'columna' => 'plan', 'hidden' => $_SESSION['planId']),
                    array('etiqueta' => '', 'columna' => 'nombre', 'hidden' => $aDatos[8]),
                    array('etiqueta' => '', 'columna' => 'tipo', 'hidden' => $aDatos[1]),
                    array('etiqueta' => '', 'columna' => 'objetivos', 'hidden' => $aDatos[2]),
                    array('etiqueta' => '', 'columna' => 'contenido', 'hidden' => $aDatos[3]),
                    array('etiqueta' => '', 'columna' => 'num_horas', 'hidden' => $aDatos[4]),
                    array('etiqueta' => '', 'columna' => 'material_necesario', 'hidden' => $aDatos[5]),
                    array('etiqueta' => '', 'columna' => 'material_suministrado', 'hidden' => $aDatos[6]),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => $aDatos[7]),
                    //   array('etiqueta'=>'Calidad: ', 'columna'=> 'calidad', 'check'=>'si'),
                    array('etiqueta' => '', 'columna' => 'medioambiente', 'hidden' => 't')
                )
                );
                break;

            case 'ecursoplan':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                $oBaseDatos->construir_Tablas(array('cursos', 'usuarios us1'));
                $oBaseDatos->construir_Where(array('(cursos.id=\'' . $iId . '\')', 'us1.id=cursos.responsable'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $sUsuario = $aIterador[0];
                }
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
                $oBaseDatos->construir_Tablas(array('cursos', 'documentos'));
                $oBaseDatos->construir_Where(array('(cursos.id=\'' . $iId . '\')', 'documentos.id=cursos.hoja_firmas'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $sDocumento = $aIterador[0];
                }
                $aFormulario = array('cursos' => array(array('etiqueta' => gettext('sFCOFechaPrev') . ': ', 'columna' => 'fecha_prevista'),
                    array('etiqueta' => gettext('sFCOResponsable') . ': ', 'columna' => 'responsable', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuario, 'action' => 'parent.sndReq(\'formacion:planes:comun:seleccionausuario\',\'\',1,\'cursos:responsable\')')),
                    array('etiqueta' => gettext('sFCOHojaFirmas') . ': ', 'columna' => 'hoja_firmas', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sDocumento, 'action' => 'parent.sndReq(\'formacion:planes:comun:seleccionadocumentoext\',\'\',1,\'cursos:hoja_firmas\')')),
                    array('etiqueta' => gettext('sFCOLugar') . ': ', 'columna' => 'lugar'),
                )
                );
                $aFormulario['cursos']['id'] = $iId;
                break;

            case 'cursoplandetalles':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_estados_curso');
                $aTiposEstadosCurso = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                $aFormulario = array('cursos' => array(array('etiqueta' => gettext('sFCOEstado') . ': ', 'columna' => 'estado', 'select' => $aTiposEstadosCurso),
                    array('etiqueta' => gettext('sFCOFechaReal') . ': ', 'columna' => 'fecha_realizacion'),
                    array('etiqueta' => gettext('sFCODuracion') . ': ', 'columna' => 'num_horas'),
                    array('etiqueta' => gettext('sFCOObjetivos') . ': ', 'columna' => 'objetivos'),
                    array('etiqueta' => gettext('sFCOContenido') . ': ', 'columna' => 'contenido'),
                    array('etiqueta' => gettext('sFCOMaterialNecesario') . ': ', 'columna' => 'material_necesario'),
                    array('etiqueta' => gettext('sFCOMaterialSum'), ': ', 'columna' => 'material_suministrado'),
                    array('etiqueta' => gettext('sFCOObserva') . ': ', 'columna' => 'observaciones')
                )

                );
                $aFormulario['cursos']['id'] = $iId;
                break;

            case 'profesor':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('profesores', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(profesores.id=\'' . $iId . '\')', 'us1.id=profesores.usuario_interno'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sUsuarioInterno = $aIterador[0];
                    }
                }
                $aFormulario = array('profesores' => array(
                    array('etiqueta' => gettext('sFCOUsuarioInt') . ': ', 'columna' => 'usuario_interno', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuarioInterno, 'action' => 'parent.sndReq(\'formacion:planes:comun:seleccionausuario\',\'\',1,\'profesores:usuario_interno\')')),
                    array('etiqueta' => gettext('sFCOEmpresa') . ': ', 'columna' => 'empresa'),
                    array('etiqueta' => '', 'columna' => 'curso', 'hidden' => $_SESSION['curso']),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => 'Interno: ', 'columna' => 'interno')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['profesores']['id'] = $iId;
                }
                break;

            case 'alumno':
                /*$oBaseDatos = new Manejador_Base_Datos($_SESSION['login'],$_SESSION['pass'],$_SESSION['db']);
                 $aCampos=array('id','nombre');
                 $aTablas=array('usuarios');
                 $aUsuarios = $this->sacar_Datos_Select($oBaseDatos,$aCampos,$aTablas);*/
                $aFormulario = array('alumnos' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'usuario', 'hidden' => 'null',
                    'boton' => array('label' => 'Seleccionar', 'valor' => "", 'action' => 'parent.sndReq(\'selecciona:usuario\',\'\',1,\'alumnos:usuario\')')),
                    //array('etiqueta'=>'Usuario: ', 'columna'=>'usuario', 'select'=>$aUsuarios),
                    array('etiqueta' => '', 'columna' => 'curso', 'hidden' => $_SESSION['curso']),
                    array('etiqueta' => '', 'columna' => 'inscrito', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'verificado', 'hidden' => 't')
                )
                );
                break;

            case 'mensajetodos':
                $aFormulario = array('mensajes' => array(array('etiqueta' => gettext('sFCOTitulo') . ': ', 'columna' => 'titulo'),
                    array('etiqueta' => gettext('sFCOContenido') . ': ', 'columna' => 'contenido'),
                    array('etiqueta' => '', 'columna' => 'destinatario', 'hidden' => '0'),
                    array('etiqueta' => '', 'columna' => 'origen', 'hidden' => $_SESSION['userid']),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                break;

            case 'tarea':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $sUsuario = "";
                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(tareas.id=\'' . $iId . '\')', 'us1.id=tareas.usuario_destino'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sUsuario = $aIterador[0];
                    }
                }
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_tarea');
                $aTareas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);


                $aFormulario = array('tareas' => array(array('etiqueta' => '', 'columna' => 'usuario_origen', 'hidden' => $_SESSION['origen']),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'documento', 'hidden' => $_SESSION['documento']),
                    array('etiqueta' => gettext('sFCOUsuario') . ': ', 'columna' => 'usuario_destino', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuario,
                            'action' => 'parent.sndReq(\'inicio:tarea:comun:seleccionausuario\',\'\',1,\'tareas:usuario_destino\')')),
                    array('etiqueta' => gettext('sFCOAccion') . ': ', 'columna' => 'accion', 'select' => $aTareas),
                    array('etiqueta' => gettext('sFCOComentarios') . ': ', 'columna' => 'descripcion')
                )
                );
                unset ($_SESSION['origen']);
                unset ($_SESSION['documento']);
                break;

            case 'acmejora':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('tipo_acciones.id', 'tipo_acciones_idiomas.valor');
                $aTablas = array('tipo_acciones', 'tipo_acciones_idiomas');
                $aWhere = array('tipo_acciones.id=tipo_acciones_idiomas.mejora', 'tipo_acciones_idiomas.idioma_id=' . $_SESSION['idiomaid']);
                $aTipos = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWhere);

                $aAud = array();
                if (is_array($aTipos)) {
                    foreach ($aTipos as $sKey => $sValue) {
                        if ($sValue == "Auditorias") {
                            $aCampos = array('auditorias.id', 'auditorias.descripcion');
                            $aTablas = array('auditorias', 'programa_auditoria');
                            $aWheres = array('auditorias.programa=programa_auditoria.id', 'programa_auditoria.vigente=\'t\'', 'auditorias.activo=\'t\'');
                            $aAuditorias = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);
                            $aAud[$sKey] = $aAuditorias;
                        } else {
                            $aAud[$sKey]['null'] = "Ninguna";
                        }
                    }
                }

                $aCampos = array('id', 'nombre');
                $aTablas = array('clientes');
                $aWhere = null;
                $aClientes = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWhere, true);

                //Sacamos los datos para enseñar en los campos de seleccion si estamos haciendo update
                $sUsuarioImplantacion = "";
                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('auditoria'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora'));
                    $oBaseDatos->construir_Where(array('(acciones_mejora.id=\'' . $iId . '\')'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $_SESSION['formaud'] = $aIterador[0];
                    }

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(acciones_mejora.id=\'' . $iId . '\')',
                        'us1.id=acciones_mejora.usuario_detectado'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(acciones_mejora.id=\'' . $iId . '\')', 'us1.id=acciones_mejora.usuario_implantacion'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sUsuarioImplantacion = $aIterador[0];
                    }
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(acciones_mejora.id=\'' . $iId . '\')', 'us1.id=acciones_mejora.usuario_verifica'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('us1.nombre||\' \'||us1.primer_apellido||\' \'||us1.segundo_apellido'));
                    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
                    $oBaseDatos->construir_Where(array('(acciones_mejora.id=\'' . $iId . '\')', 'us1.id=acciones_mejora.usuario_cerrado'));
                    $oBaseDatos->consulta();
                }

                //@TODO queda el tema de auditorias si el tipo de accion lo requiere
                if ($_SESSION['areasactivadas']) {

                    $aFormulario = array('acciones_mejora' => array(array('etiqueta' => gettext('sFCOTipo') . ': ', 'columna' => 'tipo', 'hierselect' => array($aTipos, $aAud)),
                        array('etiqueta' => gettext('sFCOCliente') . ': ', 'columna' => 'cliente', 'select' => $aClientes),
                        array('etiqueta' => gettext('sFCOFecha') . ': ', 'columna' => 'fecha'),
                        array('etiqueta' => gettext('sFCOArea') . ': ', 'columna' => 'area_id', 'hidden' => $_SESSION['areausuario']),
                        array('etiqueta' => gettext('sFCODetectadaPor') . ': ', 'columna' => 'usuario_detectado', 'hidden' => 'null',
                            'boton' => array('label' => 'Seleccionar', 'valor' => gettext('sUsuarioDetectado'), 'action' => 'parent.sndReq(\'mejora:acmejora:comun:seleccionausuario\',\'\',1,\'acciones_mejora:usuario_detectado\')')),
                        array('etiqueta' => gettext('sFCODescripcion') . ': ', 'columna' => 'descripcion'),
                        array('etiqueta' => gettext('sFCOAnalisis') . ': ', 'columna' => 'analisis'),
                        array('etiqueta' => gettext('sFCOReqTratamiento') . ': ', 'columna' => 'requiere_tratamiento'),
                        array('etiqueta' => gettext('sFCOTratInmediato') . ': ', 'columna' => 'tratamiento'),
                        array('etiqueta' => gettext('sFCOAccionCorrecPrev') . ': ', 'columna' => 'accion_preventiva'),
                        array('etiqueta' => gettext('sFCOResponsableImp') . ': ', 'columna' => 'usuario_implantacion', 'hidden' => 'null',
                            'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuarioImplantacion, 'action' => 'parent.sndReq(\'mejora:acmejora:comun:seleccionausuario\',\'\',1,\'acciones_mejora:usuario_implantacion\')')),
                        array('etiqueta' => gettext('sFCOFechaImp') . ': ', 'columna' => 'fecha_implantacion'),
                        array('etiqueta' => gettext('sFCOTratInmediato') . ': ', 'columna' => 'plazo'),
                        array('etiqueta' => gettext('sFCOObserva') . ': ', 'columna' => 'observaciones'),
                        array('etiqueta' => gettext('sFCOCoste') . ': ', 'columna' => 'coste'))
                    );
                } else {
                    $aFormulario = array('acciones_mejora' => array(array('etiqueta' => gettext('sFCOTipo') . ': ', 'columna' => 'tipo', 'hierselect' => array($aTipos, $aAud)),
                        array('etiqueta' => gettext('sFCOCliente') . ': ', 'columna' => 'cliente', 'select' => $aClientes),
                        array('etiqueta' => gettext('sFCOFecha') . ': ', 'columna' => 'fecha'),
                        array('etiqueta' => gettext('sFCOArea') . ': ', 'columna' => 'area'),
                        array('etiqueta' => gettext('sFCODetectadaPor') . ': ', 'columna' => 'usuario_detectado', 'hidden' => 'null',
                            'boton' => array('label' => 'Seleccionar', 'valor' => gettext('sUsuarioDetectado'), 'action' => 'parent.sndReq(\'mejora:acmejora:comun:seleccionausuario\',\'\',1,\'acciones_mejora:usuario_detectado\')')),
                        array('etiqueta' => gettext('sFCODescripcion') . ': ', 'columna' => 'descripcion'),
                        array('etiqueta' => gettext('sFCOAnalisis') . ': ', 'columna' => 'analisis'),
                        array('etiqueta' => gettext('sFCOReqTratamiento') . ': ', 'columna' => 'requiere_tratamiento'),
                        array('etiqueta' => gettext('sFCOTratInmediato') . ': ', 'columna' => 'tratamiento'),
                        array('etiqueta' => gettext('sFCOAccionCorrecPrev') . ': ', 'columna' => 'accion_preventiva'),
                        array('etiqueta' => gettext('sFCOResponsableImp') . ': ', 'columna' => 'usuario_implantacion', 'hidden' => 'null',
                            'boton' => array('label' => 'Seleccionar', 'valor' => $sUsuarioImplantacion, 'action' => 'parent.sndReq(\'mejora:acmejora:comun:seleccionausuario\',\'\',1,\'acciones_mejora:usuario_implantacion\')')),
                        array('etiqueta' => gettext('sFCOFechaImp') . ': ', 'columna' => 'fecha_implantacion'),
                        array('etiqueta' => gettext('sFCOTratInmediato') . ': ', 'columna' => 'plazo'),
                        //    array('etiqueta'=> 'Verificado Por: ','columna' => 'usuario_verifica', 'hidden'=>'null',
                        //        'boton'=> array('label'=>'Seleccionar','valor'=> $sUsuarioVerifica,'action'=>'parent.sndReq(\'selecciona:usuario\',\'\',1,\'acciones_mejora:usuario_verifica\')')),
                        //    array('etiqueta'=> 'Fecha Verificacion: ','columna' => 'fecha_verifica'),
                        array('etiqueta' => gettext('sFCOObserva') . ': ', 'columna' => 'observaciones'),
                        array('etiqueta' => 'Cost: ', 'columna' => 'coste'))
                        //    array('etiqueta'=> 'Cerrada: ','columna' => 'cerrada'),
                        //    array('etiqueta'=> 'Cerrada Por: ','columna' => 'usuario_cerrado', 'hidden'=>'null',
                        //           'boton'=> array('label'=>'Seleccionar','valor'=> $sUsuarioCierre,'action'=>'parent.sndReq(\'selecciona:usuario\',\'\',1,\'acciones_mejora:usuario_cerrado\')')),
                        //    array('etiqueta'=> 'Fecha Cierre: ','columna' => 'fecha_cierre'))
                    );
                }

                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['acciones_mejora']['id'] = $iId;
                }
                break;

            case 'objetivos':
                $aFormulario = array('objetivos_globales' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'nombre')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['objetivos_globales']['id'] = $iId;
                }
                break;

            case 'hospitales':
                {
                    $aFormulario = array('hospitales' => array(array('etiqueta' => gettext('sFCONombre') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => 'Password: ', 'columna' => 'password'),
                        array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                    )
                    );
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['hospitales']['id'] = $iId;
                    }
                    break;
                }
        }
        return $aFormulario;
    }

}