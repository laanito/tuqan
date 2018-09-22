<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * Este es nuestro archivo base para los formularios
 *
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 */

/**
 *     Esta funcion toma un manejador de DB y nos saca el array para construir un desplegable en el
 *     generador de formularios
 *
 * @access private
 * @param object $oBaseDatos Nuestro manejador de base datos
 * @param array $aCampos Nuestro array de campos
 * @param array $aTablas Nuestro array de tablas
 * @param array $aWheres Nuestro array de wheres
 * @return array
 */



class Form_Calidad
{

    function sacar_Datos_Select(Manejador_Base_Datos &$oBaseDatos, $aCampos, $aTablas, $aWheres = null, $aOrders = null, $bNinguno = false)
    {
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos($aCampos);
        $oBaseDatos->construir_Tablas($aTablas);
        $oBaseDatos->construir_Where($aWheres);
        $oBaseDatos->construir_Order($aOrders);
        $oBaseDatos->consulta();

        /**
         *     Devolvemos el array a meter para crear el select, en la key metemos las claves primarias
         */

        if ($bNinguno != false) {
            $aPerfiles['null'] = '[Ninguno]';
        }
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aCadena = explode(48, $aIterador[1]);
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
            //PROVEEDORES
            case 'proveedor':
                $aFormulario = array('proveedores' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('formDir') . ': ', 'columna' => 'direccion'),
                    array('etiqueta' => gettext('formTel') . ': ', 'columna' => 'telefono'),
                    array('etiqueta' => gettext('formWeb') . ': ', 'columna' => 'web'),
                    array('etiqueta' => gettext('formCif') . ': ', 'columna' => 'cif'),
                    array('etiqueta' => gettext('formFechHomo') . ': ', 'columna' => 'fecha_homologacion'),
                    array('etiqueta' => gettext('formUltRev') . ': ', 'columna' => 'ultima_revision'),
                    array('etiqueta' => gettext('formFechDesho') . ': ', 'columna' => 'fecha_deshomologacion'),
                    array('etiqueta' => gettext('formAct') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['proveedores']['id'] = $iId;
                }
                break;

            case 'incidencia':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'descripcion');
                $aTablas = array('acciones_mejora');
                $aMejora = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null, null, true);
                $aMejora['null'] = 'Ninguna';
                $aFormulario = array('incidencias' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('formCod') . ': ', 'columna' => 'codigo'),
                    array('etiqueta' => gettext('formFech') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => gettext('formNumPed') . ': ', 'columna' => 'no_pedido'),
                    array('etiqueta' => gettext('formCom') . ': ', 'columna' => 'comentario'),
                    array('etiqueta' => gettext('formAccMej') . ': ', 'columna' => 'accion_mejora', 'select' => $aMejora),
                    array('etiqueta' => '', 'columna' => 'proveedor', 'hidden' => $_SESSION['proveedor']),
                    array('etiqueta' => gettext('formAct') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['incidencias']['id'] = $iId;
                }
                break;

            case 'incidenciafila':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('proveedores');
                $aProveedores = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aCampos = array('id', 'descripcion');
                $aTablas = array('acciones_mejora');
                $aMejora = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null, null, true);
                $aFormulario = array('incidencias' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('formCod') . ': ', 'columna' => 'codigo'),
                    array('etiqueta' => gettext('formFech') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => gettext('formNumPed') . ': ', 'columna' => 'no_pedido'),
                    array('etiqueta' => gettext('formCom') . ': ', 'columna' => 'comentario'),
                    array('etiqueta' => gettext('formAccMej') . ': ', 'columna' => 'accion_mejora', 'select' => $aMejora),
                    array('etiqueta' => gettext('formProv') . ': ', 'columna' => 'proveedor', 'select' => $aProveedores),
                    array('etiqueta' => gettext('formAct') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['incidencias']['id'] = $iId;
                }
                break;

            case 'contacto':
                $aFormulario = array('contactos_proveedores' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('formPriTel') . ': ', 'columna' => 'telefono1'),
                    array('etiqueta' => gettext('formSegTel') . ': ', 'columna' => 'telefono2'),
                    array('etiqueta' => gettext('sFCFAX') . ': ', 'columna' => 'fax'),
                    array('etiqueta' => gettext('sFCMovil') . ': ', 'columna' => 'movil'),
                    array('etiqueta' => gettext('sFCActivo') . ': ', 'columna' => 'activo'),
                    array('etiqueta' => '', 'columna' => 'proveedor', 'hidden' => $_SESSION['proveedor'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['contactos_proveedores']['id'] = $iId;
                }
                break;

            case 'contactosfila':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('proveedores');
                $aProveedores = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('contactos_proveedores' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('formPriTel') . ': ', 'columna' => 'telefono1'),
                    array('etiqueta' => gettext('formSegTel') . ': ', 'columna' => 'telefono2'),
                    array('etiqueta' => gettext('sFCFAX') . ': ', 'columna' => 'fax'),
                    array('etiqueta' => gettext('sFCMovil') . ': ', 'columna' => 'movil'),
                    array('etiqueta' => gettext('sFCActivo') . ': ', 'columna' => 'activo'),
                    array('etiqueta' => gettext('formProv'), 'columna' => 'proveedor', 'select' => $aProveedores)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['contactos_proveedores']['id'] = $iId;
                }
                break;

            case 'producto':
                $aFormulario = array('productos' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCHomolog') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFCActivo') . ': ', 'columna' => 'activo'),
                    array('etiqueta' => '', 'columna' => 'proveedor', 'hidden' => $_SESSION['proveedor'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['productos']['id'] = $iId;
                }
                break;


            case 'productofila':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('proveedores');
                $aProveedores = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('productos' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCHomolog') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFCActivo') . ': ', 'columna' => 'activo'),
                    array('etiqueta' => gettext('formProv'), 'columna' => 'proveedor', 'select' => $aProveedores)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['productos']['id'] = $iId;
                }
                break;

            //PROCESOS

            case 'proceso':
                if ($sTipoForm == 'INSERT') {
                    $aFormulario = array('procesos' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => gettext('sFCCodigo') . ': ', 'columna' => 'codigo'),
                        array('etiqueta' => '', 'columna' => 'padre', 'hidden' => $_SESSION['padre']),
                        array('etiqueta' => '', 'columna' => 'revision', 'hidden' => '1.0.0')
                    )
                    );
                }
                if ($sTipoForm == 'UPDATE') {
                    //Aqui al campo hidden le damos un valor para que el generador lo cree, si estamos editando luego el generador
                    //le pone el valor correcto
                    $aFormulario = array('procesos' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => gettext('sFCCodigo') . ': ', 'columna' => 'codigo'),
                        array('etiqueta' => '', 'columna' => 'padre', 'hidden' => "padre")
                    )
                    );
                    $aFormulario['procesos']['id'] = $iId;
                }
                break;

            case 'contenidoproceso':
                $aFormulario = array('contenido_procesos' => array(array('etiqueta' => gettext('sFCProveedor') . ': ', 'columna' => 'proveedor'),
                    array('etiqueta' => gettext('sFCEntradas') . ': ', 'columna' => 'entradas'),
                    array('etiqueta' => gettext('sFCPropi') . ': ', 'columna' => 'propietario'),
                    array('etiqueta' => gettext('sFCSalidas') . ': ', 'columna' => 'salidas'),
                    array('etiqueta' => gettext('sFCCliente') . ': ', 'columna' => 'cliente'),
                    array('etiqueta' => gettext('sFCDocu') . ': ', 'columna' => 'doc_asociada'),
                    array('etiqueta' => gettext('sFCRegistro') . ': ', 'columna' => 'registros'),
                    array('etiqueta' => gettext('sFCIndica') . ': ', 'columna' => 'indicaciones'),
                    array('etiqueta' => gettext('sFCInst') . ': ', 'columna' => 'instalaciones_ambiente'),
                    array('etiqueta' => '', 'columna' => 'proceso', 'hidden' => $_SESSION['procesoficha']),
                    array('etiqueta' => '', 'columna' => 'documento', 'hidden' => $_SESSION['docficha'])
                )
                );
                $aFormulario['contenido_procesos']['id'] = $iId;
                $_SESSION['dentroform'];
                break;

            case 'contenidoproc':
                //Una vez tenemos el id adecuado procedemos a editar
                $aFormulario = array('contenido_procesos' => array(array('etiqueta' => gettext('sFCProveedor') . ': ', 'columna' => 'proveedor'),
                    array('etiqueta' => gettext('sFCEntradas') . ': ', 'columna' => 'entradas'),
                    array('etiqueta' => gettext('sFCPropi') . ': ', 'columna' => 'propietario'),
                    array('etiqueta' => gettext('sFCSalidas') . ': ', 'columna' => 'salidas'),
                    array('etiqueta' => gettext('sFCCliente') . ': ', 'columna' => 'cliente'),
                    array('etiqueta' => gettext('sFCDocu') . ': ', 'columna' => 'doc_asociada'),
                    array('etiqueta' => gettext('sFCRegistro') . ': ', 'columna' => 'registros'),
                    array('etiqueta' => gettext('sFCIndica') . ': ', 'columna' => 'indicaciones'),
                    array('etiqueta' => gettext('sFCInst') . ': ', 'columna' => 'instalaciones_ambiente'),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['contenido_procesos']['id'] = $iId;
                }
                break;

            //EQUIPOS

            case 'equipo':
                $aFormulario = array('equipos' => array(array('etiqueta' => gettext('sFCNControl') . ': ', 'columna' => 'numero'),
                    array('etiqueta' => 'S/N: ', 'columna' => 'numero_serie'),
                    array('etiqueta' => gettext('sFCDesc') . ': ', 'columna' => 'descripcion'),
                    array('etiqueta' => gettext('sFCModelo') . ': ', 'columna' => 'modelo'),
                    array('etiqueta' => gettext('sFCFabricant') . ': ', 'columna' => 'fabricante'),
                    array('etiqueta' => gettext('sFCUbica') . ': ', 'columna' => 'ubicacion'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => gettext('sFCTipo') . ': ', 'columna' => 'ver_interna', 'bool' => array('Verificacion Interna', 'Calibracion')),
                    array('etiqueta' => gettext('sFCMantCada') . ': ', 'columna' => 'mantenimiento_cada'),
                    array('etiqueta' => gettext('sFCDiasMant') . ': ', 'columna' => 'dias', 'bool' => array('Dias', 'Meses')),
                    array('etiqueta' => gettext('sFCFueraUso') . ': ', 'columna' => 'fuera_uso', 'check' => 'si'),
                    array('etiqueta' => gettext('sFCFechaFueraServ') . ': ', 'columna' => 'fecha_fuera'),
                    array('etiqueta' => gettext('sFCCausa') . ': ', 'columna' => 'causa')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['equipos']['id'] = $iId;
                }
                break;

            case 'meta':
                $aFormulario = array('metas_objetivos' => array(array('etiqueta' => '', 'columna' => 'objetivo_id', 'hidden' => $_SESSION['objetivosglobales']),
                    array('etiqueta' => gettext('sFCNumero') . ': ', 'columna' => 'numero_meta'),
                    array('etiqueta' => gettext('sFCPlanAccion') . ': ', 'columna' => 'plan_accion'),
                    array('etiqueta' => gettext('sFCFechaConsec') . ': ', 'columna' => 'fecha_consecucion'),
                    array('etiqueta' => gettext('sFCResponsable') . ': ', 'columna' => 'responsable'),
                    array('etiqueta' => gettext('sFCRecursos') . ': ', 'columna' => 'recursos')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['metas_objetivos']['id'] = $iId;
                }
                break;

            case 'seguimiento':
                $aFormulario = array('seguimientos' => array(array('etiqueta' => '', 'columna' => 'objetivos', 'hidden' => $_SESSION['objetivo']),
                    array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => gettext('sFCObservaciones') . ': ', 'columna' => 'observaciones'),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['seguimientos']['id'] = $iId;
                }
                break;

            case 'metaobjetivosindicadores':
                $aFormulario = array('metas_indicadores' => array(array('etiqueta' => '', 'columna' => 'objetivo_id', 'hidden' => $_SESSION['objetivosindicadores']),
                    array('etiqueta' => gettext('sFCNumero') . ': ', 'columna' => 'numero_meta'),
                    array('etiqueta' => gettext('sFCPlanAccion') . ': ', 'columna' => 'plan_accion'),
                    array('etiqueta' => gettext('sFCFechaConsec') . ': ', 'columna' => 'fecha_consecucion'),
                    array('etiqueta' => gettext('sFCResponsable') . ': ', 'columna' => 'responsable'),
                    array('etiqueta' => gettext('sFCRecursos') . ': ', 'columna' => 'recursos')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['metas_indicadores']['id'] = $iId;
                }
                break;

            case 'mantenimientoprev':
                if ($sTipoForm = "INSERT") {
                    $iId = $_SESSION['equipo'];
                }
                //Sacamos la fecha prevista
                $aFecha = null;
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('mantenimiento_cada', 'dias'));
                $oBaseDatos->construir_Tablas(array('equipos'));
                $oBaseDatos->construir_Where(array('id=\'' . $iId . '\''));
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
                    $oBaseDatos->construir_Where(array(('equipo=\'' . $iId . '\''), ('tipo=\'preventivo\'')));
                    $oBaseDatos->consulta();
                    if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                        $aFecha = $aIteradorInterno;
                    }
                    $_SESSION['texto'] = gettext('sMantProx') . ":" . $aFecha[0] . "/" . $aFecha[1] . "/" . $aFecha[2];
                }

                $aFormulario = array('mantenimientos' => array(array('etiqueta' => gettext('sFCFechaRealiz') . ': ', 'columna' => 'fecha_realiza'),
                    array('etiqueta' => gettext('sFCComent') . ': ', 'columna' => 'comentarios'),
                    array('etiqueta' => '', 'columna' => 'tipo', 'hidden' => 'preventivo'),
                    array('etiqueta' => '', 'columna' => 'motivos', 'hidden' => 'No procede'),
                    array('etiqueta' => '', 'columna' => 'equipo', 'hidden' => $iId),
                    array('etiqueta' => '', 'columna' => 'fecha_prevista', 'hidden' => $aFecha)
                )
                );
                break;

            case 'mantenimientocorr':
                if ($sTipoForm = "INSERT") {
                    $iId = $_SESSION['equipo'];
                }
                $aFormulario = array('mantenimientos' => array(array('etiqueta' => gettext('sFCFechaRealiz') . ': ', 'columna' => 'fecha_realiza'),
                    array('etiqueta' => gettext('sFCComent') . ': ', 'columna' => 'comentarios'),
                    array('etiqueta' => gettext('sFCMotivosMant') . ': ', 'columna' => 'motivos'),
                    array('etiqueta' => '', 'columna' => 'tipo', 'hidden' => 'correctivo'),
                    array('etiqueta' => '', 'columna' => 'equipo', 'hidden' => $iId)
                )
                );
                break;
            //AUDITORIAS
            case 'objetivo':
                require_once 'Manejador_Base_Datos.class.php';
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('indicadores.id', 'indicadores.nombre');
                $aTablas = array('indicadores', 'objetivos');
                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos($aCampos);
                    $oBaseDatos->construir_Tablas($aTablas);
                    $oBaseDatos->construir_Where(array('(indicadores.id=objetivos.indicadores)', '(objetivos.id=\'' . $iId . '\')'));
                    $oBaseDatos->consulta();
                }

                $aFormulario = array('objetivos' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCIndicadores') . ': ', 'columna' => 'indicadores', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => gettext('sIndicadores'),
                            'action' => 'parent.sndReq(\'inicio:tarea:comun:seleccionaindicadores\',\'\',1,\'objetivos:indicadores\')')),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'estado', 'hidden' => '2')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['objetivos']['id'] = $iId;
                }
                break;

            case 'valor':
                $aFormulario = array('valores' => array(array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'indicador', 'hidden' => $_SESSION['indicador']),
                    array('etiqueta' => '', 'columna' => 'proceso', 'hidden' => $_SESSION['contenido_proceso'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['valores']['id'] = $iId;
                }
                break;

            case 'objindicador':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('objetivos');
                $aObjetivos = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('objetivos_indicadores' => array(array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor_objetivo'),
                    array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha_objetivo'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => gettext('sFCObjetivo') . ': ', 'columna' => 'objetivo', 'select' => $aObjetivos),
                    array('etiqueta' => '', 'columna' => 'indicador', 'hidden' => $_SESSION['indicador']),
                    array('etiqueta' => '', 'columna' => 'proceso', 'hidden' => $_SESSION['contenido_proceso'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['objetivos_indicadores']['id'] = $iId;
                }
                break;

            case 'indicador':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_frecuencia_seg');
                $aOrders = array('id');
                $aFrecuencia = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null, $aOrders, true);

                $aCampos = array('id', 'nombre');
                $aTablas = array('objetivos');
                $aOrders = array('id');
                $aObjetivos = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null, $aOrders);

                if ($_SESSION['areasactivadas']) {
                    $aFormulario = array('indicadores' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => gettext('sFCDefinicion') . ': ', 'columna' => 'definicion'),
                        array('etiqueta' => gettext('sFCValorInicial') . ': ', 'columna' => 'valor_inicial'),
                        array('etiqueta' => gettext('sFCVObjetivo') . ':', 'columna' => 'valor_objetivo'),
                        array('etiqueta' => gettext('sFCValorTolerante') . ': ', 'columna' => 'valor_tolerable'),
                        array('etiqueta' => gettext('sFCValorTolerante') . ' 2: ', 'columna' => 'valor_tolerable2'),
                        array('etiqueta' => gettext('sFCFrecuenciaSeg') . ': ', 'columna' => 'frecuencia_seg', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFCResponsableSeg') . ': ', 'columna' => 'responsable_seguimiento'),
                        array('etiqueta' => gettext('sFCFrecAnalisis') . ': ', 'columna' => 'frecuencia_ana', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFCRespAnalisis') . ': ', 'columna' => 'responsable_analisis'),
                        array('etiqueta' => gettext('sFCTecnica') . ': ', 'columna' => 'tecnica'),
                        array('etiqueta' => gettext('sFCVarControl') . ': ', 'columna' => 'variables_control'),
                        array('etiqueta' => gettext('sFCGeneraObj') . '', 'columna' => 'objetivo', 'select' => $aObjetivos),
                        array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                        array('etiqueta' => '', 'columna' => 'area_id', 'hidden' => $_SESSION['areausuario'])
                    )
                    );
                } else {
                    $aFormulario = array('indicadores' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => gettext('sFCDefinicion') . ': ', 'columna' => 'definicion'),
                        array('etiqueta' => gettext('sFCValorInicial') . ': ', 'columna' => 'valor_inicial'),
                        array('etiqueta' => gettext('sFCVObjetivo') . ':', 'columna' => 'valor_objetivo'),
                        array('etiqueta' => gettext('sFCValorTolerante') . ': ', 'columna' => 'valor_tolerable'),
                        array('etiqueta' => gettext('sFCValorTolerante') . ' 2: ', 'columna' => 'valor_tolerable2'),
                        array('etiqueta' => gettext('sFCFrecuenciaSeg') . ': ', 'columna' => 'frecuencia_seg', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFCResponsableSeg') . ': ', 'columna' => 'responsable_seguimiento'),
                        array('etiqueta' => gettext('sFCFrecAnalisis') . ': ', 'columna' => 'frecuencia_ana', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFCRespAnalisis') . ': ', 'columna' => 'responsable_analisis'),
                        array('etiqueta' => gettext('sFCTecnica') . ': ', 'columna' => 'tecnica'),
                        array('etiqueta' => gettext('sFCVarControl') . ': ', 'columna' => 'variables_control'),
                        array('etiqueta' => gettext('sFCGeneraObj') . '', 'columna' => 'objetivo', 'select' => $aObjetivos),
                        array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                    )
                    );
                }
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['indicadores']['id'] = $iId;
                }
                break;

            // Para la opcion auditorias:programa:nuevo
            case 'programa':
                $aFormulario = array('programa_auditoria' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => '', 'columna' => 'revision', 'hidden' => '1.0.0'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['programa_auditoria']['id'] = $iId;
                }
                break;

            case 'auditoria':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_estado_auditoria');
                $aTipos = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('auditorias' => array(array('etiqueta' => gettext('sFCDesc') . ': ', 'columna' => 'descripcion'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones'),
                    array('etiqueta' => '', 'columna' => 'programa', 'hidden' => $_SESSION['progauditoria']),
                    array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => gettext('sFCEstado') . ': ', 'columna' => 'estado', 'select' => $aTipos),
                    array('etiqueta' => gettext('sFCAreas') . ': ', 'columna' => 'areas', 'hidden' => '0'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['auditorias']['id'] = $iId;
                }
                break;

            case 'planauditoria':
                $aFormulario = array('auditorias' => array(array('etiqueta' => gettext('sFCNombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCResponsableCalidad') . ': ', 'columna' => 'responsable_de_calidad'),
                    array('etiqueta' => gettext('sFCAlcance') . ': ', 'columna' => 'alcance'),
                    array('etiqueta' => gettext('sFCFechaRealiz') . ': ', 'columna' => 'fecha_realiza'),
                    array('etiqueta' => gettext('sFCIdioma') . ': ', 'columna' => 'idioma_auditoria'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['auditorias']['id'] = $iId;
                }
                break;

            // Para la opcion case auditorias:plan:formulario:planauditoria:nuevo
            case 'plan':
                $aFormulario = array('auditorias' => array(array('etiqueta' => gettext('sFCNombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCCalidad') . ': ', 'columna' => 'responsable_de_calidad'),
                    array('etiqueta' => gettext('sFCAlcance') . ': ', 'columna' => 'alcance'),
                    array('etiqueta' => gettext('sFCFechaRealiz') . ': ', 'columna' => 'fecha_realiza'),
                    array('etiqueta' => gettext('sFCIdioma') . ': ', 'columna' => 'idioma_auditoria'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['auditorias']['id'] = $iId;
                }
                break;

            case 'auditor':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('usuarios');
                $aUsuarios = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('auditores' => array(array('etiqueta' => gettext('sFCUsuarioInt') . ': ', 'columna' => 'usuario_interno', 'select' => $aUsuarios, 'hidden' => 'null',
                    'boton' => array('label' => 'Seleccionar', 'valor' => '', 'action' => 'parent.sndReq(\'auditorias:auditor:comun:seleccionausuario\',\'\',1,\'auditores:usuario_interno\')')),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => '', 'columna' => 'auditoria', 'hidden' => $_SESSION['auditoria']),
                    'checkbox' => array('etiqueta' => 'Interno: ', 'marcado' => 0, 'datos' => array('auditores:usuario_interno', 'auditores:nombre'))
                )
                );
                break;

            case 'informeauditoria':
                //Comprobamos is hay acciones mejora para esta auditoria
                $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'descripcion');
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array('acciones_mejora'));
                $oDb->construir_Where(array('auditoria=' . $iId));
                $oDb->consulta();

                if ($aIterador = $oDb->coger_Fila()) {
                    $bVerMejora = true;
                }
                if ($bVerMejora) {
                    $aFormulario = array('auditorias' => array(array('etiqueta' => gettext('sFCLugar') . ': ', 'columna' => 'lugar_informe'),
                        array('etiqueta' => gettext('sFCFechaInforme') . ': ', 'columna' => 'fecha_informe'),
                        array('etiqueta' => gettext('$sFCConclusiones') . ': ', 'columna' => 'conclusiones_informe'),
                        array('etiqueta' => gettext('$sFCRecomendaciones') . ': ', 'columna' => 'recomendaciones_informe', 'boton' => array('label' => 'Ver no conformidades', 'valor' => '', 'action' => 'parent.sndReq(\'auditoria:accmejora\',\'\',1,' . $iId . ')'))
                    )
                    );
                } else {
                    $aFormulario = array('auditorias' => array(array('etiqueta' => gettext('sFCLugar') . ': ', 'columna' => 'lugar_informe'),
                        array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha_informe'),
                        array('etiqueta' => gettext('sFCFechaRealizAudi') . ': ', 'columna' => 'fecha_realiza'),
                        array('etiqueta' => gettext('$sFCConclusiones') . ': ', 'columna' => 'conclusiones_informe'),
                    )
                    );
                }
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['auditorias']['id'] = $iId;
                }
                break;

            case 'fichapersonal':
                $aFormulario = array('ficha_personal' => array(
                    array('etiqueta' => gettext('sFCCodigo') . ': ', 'columna' => 'codigo'),
                    array('etiqueta' => gettext('sFCNombreFicha') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCFechaCreacion') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => gettext('sFCRevision') . ': ', 'columna' => 'revision')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal']['id'] = $iId;
                }
                break;

            case 'fpdp':
                $aFormulario = array('ficha_personal_datos_personales' => array(
                    array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('$sFCApellidos') . ': ', 'columna' => 'apellidos'),
                    array('etiqueta' => gettext('sFCFechaNacim') . ': ', 'columna' => 'fecha_nac'),
                    array('etiqueta' => gettext('$sFCLocalidad') . ':', 'columna' => 'localidad'),
                    array('etiqueta' => gettext('$sFCProvincia') . ': ', 'columna' => 'provincia'),
                    array('etiqueta' => gettext('$formDir') . ': ', 'columna' => 'direccion'),
                    array('etiqueta' => gettext('$formTel') . ': ', 'columna' => 'telefono'),
                    array('etiqueta' => gettext('$sFCPobResidencia') . ': ', 'columna' => 'poblacion'),
                    array('etiqueta' => gettext('$sFCProvResidencia') . ': ', 'columna' => 'provincia_residencia'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones'),
                    array('etiqueta' => gettext('$sFCVehiculopropio') . ': ', 'columna' => 'vehiculo_propio'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                $aFormulario['ficha_personal_datos_personales']['id'] = $iId;
                break;

            case 'fpfor':
                $aFormulario = array('ficha_personal_formacion_academica' => array(
                    array('etiqueta' => gettext('$sFCTitulosOfi') . ': ', 'columna' => 'titulos_oficiales'),
                    array('etiqueta' => gettext('sFCFechafin') . ': ', 'columna' => 'fecha_fin'),
                    array('etiqueta' => gettext('sFCCentro') . ': ', 'columna' => 'centro'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                $aFormulario['ficha_personal_formacion_academica']['id'] = $iId;
                break;

            case 'fpinc':
                $aFormulario = array('ficha_personal_incorporacion' => array(
                    array('etiqueta' => gettext('sFCEmpresa') . ': ', 'columna' => 'empresa'),
                    array('etiqueta' => gettext('sFCFechaIncor') . ': ', 'columna' => 'fecha_incorporacion'),
                    array('etiqueta' => gettext('sFCPerfil') . ': ', 'columna' => 'perfil'),
                    array('etiqueta' => gettext('sFCDepartamento') . '', 'columna' => 'departamento'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                $aFormulario['ficha_personal_incorporacion']['id'] = $iId;
                break;

            case 'fppre':
                $aFormulario = array('ficha_personal_preformacion' => array(
                    array('etiqueta' => gettext('sFCCurso1') . ': ', 'columna' => 'curso1'),
                    array('etiqueta' => gettext('sFCCurso2') . ': ', 'columna' => 'curso2'),
                    array('etiqueta' => gettext('sFCCurso3') . ': ', 'columna' => 'curso3'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                $aFormulario['ficha_personal_preformacion']['id'] = $iId;
                break;

            case 'fpidiomas':
                $aFormulario = array('ficha_personal_idiomas' => array(
                    array('etiqueta' => gettext('sFCNivelIngles') . ': ', 'columna' => 'nivel_ingles'),
                    array('etiqueta' => gettext('sFCNivelFrances') . ':', 'columna' => 'nivel_frances'),
                    array('etiqueta' => gettext('sFCOtros') . ': ', 'columna' => 'otros'),
                    array('etiqueta' => gettext('sFCNivel') . ': ', 'columna' => 'nivel_otros'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                $aFormulario['ficha_personal_idiomas']['id'] = $iId;
                break;

            case 'fpcursos':
                $aFormulario = array('ficha_personal_otros_cursos' => array(
                    array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCLugar') . ': ', 'columna' => 'lugar'),
                    array('etiqueta' => gettext('sFCFechaInicio') . ': ', 'columna' => 'inicio'),
                    array('etiqueta' => gettext('sFCFechaFin') . ': ', 'columna' => 'fin'),
                    array('etiqueta' => gettext('sFCPeriodo') . ': ', 'columna' => 'periodo'),
                    array('etiqueta' => '', 'columna' => 'ficha', 'hidden' => $_SESSION['ficha'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal_otros_cursos']['id'] = $iId;
                }
                break;

            case 'fpft':
                $aFormulario = array('ficha_personal_formacion_tecnica' => array(
                    array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCLugar') . ': ', 'columna' => 'lugar'),
                    array('etiqueta' => gettext('sFCFechaInicio') . ': ', 'columna' => 'desde'),
                    array('etiqueta' => gettext('sFCFechaFin') . ': ', 'columna' => 'hasta'),
                    array('etiqueta' => gettext('sFCPeriodo') . ': ', 'columna' => 'periodo'),
                    array('etiqueta' => '', 'columna' => 'ficha', 'hidden' => $_SESSION['ficha_ft'])
                )

                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal_formacion_tecnica']['id'] = $_SESSION['id_ft'];
                }
                break;

            case 'fpel':
                $aFormulario = array('ficha_personal_experiencia_laboral' => array(
                    array('etiqueta' => gettext('sFCEmpresa') . ': ', 'columna' => 'empresa'),
                    array('etiqueta' => gettext('sFCPuesto') . ': ', 'columna' => 'puesto'),
                    array('etiqueta' => gettext('sFCFechaInicio') . ': ', 'columna' => 'fecha_inicio'),
                    array('etiqueta' => gettext('sFCFechaFin') . ': ', 'columna' => 'fecha_fin'),
                    array('etiqueta' => '', 'columna' => 'ficha', 'hidden' => $_SESSION['ficha_el'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal_experiencia_laboral']['id'] = $_SESSION['id_el'];
                }
                break;

            case 'fpcp':
                $aFormulario = array('ficha_personal_cambio_perfil' => array(
                    array('etiqueta' => gettext('sFCFechaCambioPerf') . ': ', 'columna' => 'fecha_cambio_perfil'),
                    array('etiqueta' => '', 'columna' => 'ficha', 'hidden' => $_SESSION['ficha_cp'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal_cambio_perfil']['id'] = $_SESSION['id_cp'];
                }
                break;

            case 'fpcd':
                $aFormulario = array('ficha_personal_cambio_departamento' => array(
                    array('etiqueta' => gettext('sFCFechaCambio') . ': ', 'columna' => 'fecha_cambio_departamento'),
                    array('etiqueta' => '', 'columna' => 'ficha', 'hidden' => $_SESSION['ficha_cd'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['ficha_personal_cambio_departamento']['id'] = $_SESSION['id_cd'];
                }
                break;


            case 'reqpuesto':
                $aFormulario = array('requisitos_puesto' => array(array('etiqueta' => gettext('formNom') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFCVersion') . ': ', 'columna' => 'revision'),
                    array('etiqueta' => gettext('sFCCodigo') . ': ', 'columna' => 'codigo'),
                    array('etiqueta' => gettext('sFCFecha') . ': ', 'columna' => 'fecha'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto']['id'] = $iId;
                }
                break;

            case 'competenciasrq':
                $aFormulario = array('requisitos_puesto_competencias' => array(array('etiqueta' => gettext('sFCConocimientos') . ': ', 'columna' => 'conocimientos'),
                    array('etiqueta' => gettext('sFCFunciones') . ': ', 'columna' => 'funciones'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto_competencias']['id'] = $iId;
                }
                break;

            case 'datospuesto':
                $aFormulario = array('requisitos_puesto_datos_puesto' => array(
                    array('etiqueta' => gettext('sFCNomDelPuesto') . ': ', 'columna' => 'nombre_puesto'),
                    array('etiqueta' => gettext('sFCCategoria') . ': ', 'columna' => 'categoria'),
                    array('etiqueta' => gettext('sFCDependeDe') . ': ', 'columna' => 'depende_de'),
                    array('etiqueta' => gettext('sFCArea') . ': ', 'columna' => 'area'),
                    array('etiqueta' => gettext('sFCRequiereAnt') . ': ', 'columna' => 'requiere_ant'),
                    array('etiqueta' => gettext('sFCAntiguedad') . ': ', 'columna' => 'antiguedad'),
                    array('etiqueta' => gettext('sFCObserva') . ': ', 'columna' => 'observaciones'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto_datos_puesto']['id'] = $iId;
                }
                break;

            case 'formacionrq':
                $aFormulario = array('requisitos_puesto_formacion' => array(array('etiqueta' => gettext('$sFCTitulosOfi') . ': ', 'columna' => 'titulos_oficiales'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto_formacion']['id'] = $iId;
                }
                break;

            case 'promocionrq':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_grado_promocion');
                $aGrados = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('requisitos_puesto_promocion' => array(array('etiqueta' => gettext('sFCAutonomiaYResp') . ': ', 'columna' => 'autonomia', 'select' => $aGrados),
                    array('etiqueta' => gettext('sFCRelacionesYCom') . ': ', 'columna' => 'relaciones', 'select' => $aGrados),
                    array('etiqueta' => gettext('sFCLiderazgo') . ': ', 'columna' => 'liderazgo'),
                    array('etiqueta' => gettext('sFCMando') . ': ', 'columna' => 'mando'),
                    array('etiqueta' => gettext('sFCMotivacion') . ': ', 'columna' => 'motivacion'),
                    array('etiqueta' => gettext('sFCNegociacion') . ': ', 'columna' => 'negociacion'),
                    array('etiqueta' => gettext('sFCTrabajo') . ': ', 'columna' => 'trabajo'),
                    array('etiqueta' => gettext('sFCFormacion') . ': ', 'columna' => 'formacion'),
                    array('etiqueta' => '', 'columna' => 'id', 'hidden' => $iId)


                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto_promocion']['id'] = $iId;
                }
                break;

            case 'formaciontecnicarq':
                $aFormulario = array('requisitos_puesto_ft' => array(array('etiqueta' => gettext('sFCFormacionTecnica') . ': ', 'columna' => 'formacion_tecnica'),
                    array('etiqueta' => gettext('sFCOpcional') . ': ', 'columna' => 'opcional'),
                    array('etiqueta' => gettext('sFCHoras') . ': ', 'columna' => 'horas'),
                    array('etiqueta' => '', 'columna' => 'requisitos', 'hidden' => $_SESSION['reqpuesto'])
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['requisitos_puesto_ft']['id'] = $iId;
                }
                break;
        }
        return $aFormulario;
    }
}