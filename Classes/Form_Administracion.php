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

class Form_Administracion
{

    /**
     *     Esta funcion toma un manejador de DB y nos saca el array para construir un desplegable en el
     *     generador de formularios
     *
     * @access private
     * @param object $oBaseDatos Nuestro manejador de base datos
     * @param array $aCampos Nuestro array de campos
     * @param array $aTablas Nuestro array de tablas
     * @param array $aWheres Nuestro array de wheres
     * @return string
     */

    function crearArrayPermisoss()
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

    /**
     * @param Manejador_Base_Datos $oBaseDatos
     * @param array $aCampos
     * @param array $aTablas
     * @param array $aWheres
     * @param bool $bNinguno
     * @return mixed
     */
    function sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres = null, $bNinguno = false)
    {
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos($aCampos);
        $oBaseDatos->construir_Tablas($aTablas);
        $oBaseDatos->construir_Where($aWheres);

        $oBaseDatos->consulta();

        /**
         *     Devolvemos el array a meter para crear el select, en la key metemos las claves primarias, si nos lo
         * han pedido metemos un valor null
         */
        $aPerfiles = array();
        if ($bNinguno != false) {
            $aPerfiles['null'] = '[Ninguno]';
        }
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aPerfiles[$aIterador[0]] = $aIterador[1];
        }
        return $aPerfiles;
    }

    /**
     *     Esta funcion nos devuelve listo el array para introducir en el generador de formularios para el
     *     caso de cambiar passwords
     *
     * @param String $sFormulario
     * @param int $iId
     * @return array
     */

    function devuelve_Array_Pass($sFormulario, $iId)
    {
        switch ($sFormulario) {
            case 'passwordusuario':
            case 'usuario':
                $aFormulario = array('usuarios' => array(array('etiqueta' => 'Login: ', 'columna' => 'login'),
                    array('etiqueta' => 'Password: ', 'columna' => 'password')
                )
                );
                $aFormulario['usuarios']['id'] = $iId;
                break;
            default:
                $aFormulario = array();
                break;
        }
        return $aFormulario;
    }


    /**
     *     Esta funcion nos devuelve ya listo el array para introducir en el generador de formularios
     *
     * @access public
     * @param String $sFormulario
     * @param String $sTipoForm
     * @param Int $iId
     * @return array
     */

    function devuelve_Array_Form($sFormulario, $sTipoForm, $iId)
    {
        switch ($sFormulario) {
            case 'mensajes':
                $aFormulario = array('mensajes' => array(array('etiqueta' => gettext('sFAContenido') . ': ', 'columna' => 'contenido'),
                    array('etiqueta' => '', 'columna' => 'destinatario', 'hidden' => '0'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'origen', 'hidden' => $_SESSION['userid']),
                    array('etiqueta' => gettext('sFATitulo') . ': ', 'columna' => 'titulo'),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['mensajes']['id'] = $iId;
                }
                break;

            case 'area':
                $aFormulario = array('areas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFAActivo') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['areas']['id'] = $iId;
                }
                break;

            case 'usuario':
                //Conectamos a la DB para poder sacar los datos que poner en los desplegables del formulario
                require_once 'Manejador_Base_Datos.class.php';

                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('perfiles');
                $aWhere = array('activo=\'t\'');
                $aPerfiles = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWhere);
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('areas');
                $aWhere = array('activo=\'t\'');
                $aAreas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWhere);
                $sFicha = '';
                $sRequisitos = '';

                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('ficha_personal.codigo||\' \'||ficha_personal.nombre'));
                    $oBaseDatos->construir_Tablas(array('usuarios', 'ficha_personal'));
                    $oBaseDatos->construir_Where(array('(usuarios.id=\'' . $iId . '\')', 'ficha_personal.id=usuarios.ficha'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sFicha = $aIterador[0];
                    }

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('requisitos_puesto.codigo||\' \'||requisitos_puesto.nombre'));
                    $oBaseDatos->construir_Tablas(array('usuarios', 'requisitos_puesto'));
                    $oBaseDatos->construir_Where(array('(usuarios.id=\'' . $iId . '\')', 'requisitos_puesto.id=usuarios.requisitos'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sRequisitos = $aIterador[0];
                    }
                }
                $aFormulario = array('usuarios' => array(array('etiqueta' => gettext('sFALogin') . ': ', 'columna' => 'login'),
                    array('etiqueta' => gettext('sFAPassword') . ': ', 'columna' => 'password'),
                    array('etiqueta' => gettext('sFAPerfil') . ': ', 'columna' => 'perfil', 'select' => $aPerfiles,
                        'boton' => array('label' => 'Ver Permisos', 'action' => 'parent.sndReq(\'administracion:usuarios:arbol:selecciona:verPerfil\',\'\',1)')),
                    array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFA1Apellido') . ': ', 'columna' => 'primer_apellido'),
                    array('etiqueta' => gettext('sFA2Apellido') . ': ', 'columna' => 'segundo_apellido'),
                    array('etiqueta' => gettext('sFATelefono') . ': ', 'columna' => 'telefono'),
                    array('etiqueta' => gettext('sFANIF') . ': ', 'columna' => 'nif'),
                    array('etiqueta' => gettext('sBotonArea') . ': ', 'columna' => 'area', 'select' => $aAreas),
                    array('etiqueta' => gettext('sFAEmail') . ': ', 'columna' => 'email'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => gettext('sFAFicha') . ': ', 'columna' => 'ficha', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sFicha,
                            'action' => 'parent.sndReq(\'administracion:usuarios:comun:seleccionaficha\',\'\',1,\'usuarios:ficha\')')),
                    array('etiqueta' => gettext('sFARequisitos') . ': ', 'columna' => 'requisitos', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sRequisitos,
                            'action' => 'parent.sndReq(\'administracion:usuarios:comun:seleccionarequisitos\',\'\',1,\'usuarios:requisitos\')')),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['usuarios']['id'] = $iId;
                }
                break;

            case 'perfil':
                $aFormulario = array('perfiles' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['perfiles']['id'] = $iId;
                }
                break;

            case 'cliente':
                $aFormulario = array('clientes' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFATelefono') . ': ', 'columna' => 'telefono'),
                    array('etiqueta' => gettext('sFAContacto') . ': ', 'columna' => 'contacto'),
                    array('etiqueta' => gettext('sFAActivo') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['clientes']['id'] = $iId;
                }
                break;

            case 'mejora':

                $aFormulario = array('tipo_acciones' => array(
                    array('etiqueta' => gettext('sFAActivo') . ': ', 'columna' => 'activo')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_acciones']['id'] = $iId;
                }
                break;

            case 'mejoraidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);

                $aFormulario = array('tipo_acciones_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'mejora', 'hidden' => $_SESSION['mejora']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_acciones_idiomas']['id'] = $iId;
                }
                break;

            case 'criterio':
                $aFormulario = array('criterios_homologacion' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFAActivo') . ': ', 'columna' => 'activo'),
                    array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor'),
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['criterios_homologacion']['id'] = $iId;
                }
                break;

            case 'tipodocumento':

                $aTipo = array('listado' => gettext('sFATipoListado'), 'unico' => gettext('sFATipoUnico'));

                $sPermisos = $this->crearArrayPermisoss();

                $aFormulario = array('tipo_documento' => array(
                    array('etiqueta' => gettext('sFATipo') . ': ', 'columna' => 'tipo', 'select' => $aTipo),
                    array('etiqueta' => '', 'columna' => 'perfil_ver', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_nueva', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_modificar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_revisar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_aprobar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_historico', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_tareas', 'hidden' => $sPermisos),


                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_documento']['id'] = $iId;
                }
                break;

            case 'tipodocidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);

                $aFormulario = array('tipo_documento_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'tipodoc', 'hidden' => $_SESSION['tipodoc']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_documento_idiomas']['id'] = $iId;
                }
                break;


            case 'ayuda':

                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aIdioma = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);


                $aFormulario = array('division_ayuda' => array(array('etiqueta' => gettext('sFAAMenu') . ': ', 'columna' => 'menu'),
                    array('etiqueta' => gettext('sFAABoton') . ': ', 'columna' => 'boton'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma', 'select' => $aIdioma),
                    array('etiqueta' => gettext('sFAATexto') . ': ', 'columna' => 'texto')

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['division_ayuda']['id'] = $iId;
                }
                break;

            case 'normativa':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('areas');
                $aAreas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);
                $sPermisos = $this->crearArrayPermisoss();
                $aFormulario = array('documentos' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFACodigo') . ': ', 'columna' => 'codigo'),
                    array('etiqueta' => '', 'columna' => 'revisado_por', 'hidden' => $_SESSION['userid']),
                    array('etiqueta' => '', 'columna' => 'aprobado_por', 'hidden' => $_SESSION['userid']),
                    array('etiqueta' => '', 'columna' => 'estado', 'hidden' => iVigor),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                    array('etiqueta' => '', 'columna' => 'medioambiente', 'hidden' => 't'),
                    array('etiqueta' => gettext('sFAArea') . ': ', 'columna' => 'area', 'select' => $aAreas),
                    array('etiqueta' => '', 'columna' => 'perfil_ver', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_nueva', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_modificar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_revisar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_aprobar', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_historico', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'perfil_tareas', 'hidden' => $sPermisos),
                    array('etiqueta' => '', 'columna' => 'tipo_documento', 'hidden' => iIdExterno)
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['documentos']['id'] = $iId;
                }
                break;

            case 'tipoarea':

                $aFormulario = array('tipo_area_aplicacion' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_area_aplicacion']['id'] = $iId;
                }
                break;
            case 'impacto':

                $aFormulario = array('tipo_impactos' => array(array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_impactos']['id'] = $iId;
                }
                break;

            case 'tipoimpidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);

                $aFormulario = array('tipo_impactos_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'impactos', 'hidden' => $_SESSION['tipoimp']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_impactos_idiomas']['id'] = $iId;
                }
                break;

            case 'tipoamb':

                $aFormulario = array('tipo_ambito_aplicacion' => array(
                    array('etiqueta' => '', 'columna' => 'idioma', 'hidden' => $_SESSION['idiomaid'])

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_ambito_aplicacion']['id'] = $iId;
                }
                break;

            case 'tipoambidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);

                $aFormulario = array('tipo_ambito_aplicacion_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'tipoamb', 'hidden' => $_SESSION['tipoamb']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_ambito_aplicacion_idiomas']['id'] = $iId;
                }
                break;

            case 'preguntasleg':
                $aFormulario = array('preguntas_legislacion_aplicable' => array(array('etiqueta' => gettext('sFAPregunta') . ': ', 'columna' => 'pregunta'),
                    array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor_deseado'),
                    array('etiqueta' => '', 'columna' => 'legislacion_aplicable', 'hidden' => $_SESSION['admlegislacion']),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['preguntas_legislacion_aplicable']['id'] = $iId;
                }
                break;

            case 'tipocurso':
                $aFormulario = array('tipos_cursos' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipos_cursos']['id'] = $iId;
                }
                break;

            case 'centros':
                $aFormulario = array('areas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFAActivo') . ': ', 'columna' => 'activo')

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['areas']['id'] = $iId;
                }
                break;

            case 'menu':
                $aFormulario = array('menu_nuevo' => array(array('etiqueta' => gettext('sFAAccion') . ': ', 'columna' => 'accion'),
                    array('etiqueta' => '', 'columna' => 'permisos', 'hidden' => '{f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f,f}'),
                    //array('etiqueta'=>'Padre: ', 'columna'=>'padre','select'=> $aMenus,)
                    array('etiqueta' => gettext('sFAPadre') . ': ', 'columna' => 'padre')
                ),
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['menu_nuevo']['id'] = $iId;
                } else {
                     $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    //Debemos meter valores por defecto para los idiomas del menu, sacamos el id del que sera el menu
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('max(id)'));
                    $oBaseDatos->construir_Tablas(array('menu_nuevo'));
                    $oBaseDatos->consulta();
                    $aDevolver = $oBaseDatos->coger_Fila();
                    $iIdMenu = $aDevolver[0] + 1;//este será el id del menu

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('id'));
                    $oBaseDatos->construir_Tablas(array('idiomas'));
                    $oBaseDatos->consulta();
                    $aDespues = array();
                    while ($aDevolver = $oBaseDatos->coger_Fila()) {
                        $aDespues[] = array('tipo' => 'INSERT', 'campos' => array('menu', 'valor', 'idioma_id'),
                            'tablas' => array('menu_idiomas_nuevo'), 'value' => array($iIdMenu, "''", $aDevolver[0]));
                    }
                }
                $aFormulario = array('despues' => $aDespues, 'form' => $aFormulario);
                break;
        }
        return $aFormulario;
    }
}