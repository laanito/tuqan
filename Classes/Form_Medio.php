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

class Form_Medio
{


    /**
     *     Esta funcion toma un manejador de DB y nos saca el array para construir un desplegable en el
     *     generador de formularios
     *
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

    /**
     * @param $sFormulario
     * @param $sTipoForm
     * @param $iId
     * @return array
     */
    function devuelve_Array_Form($sFormulario, $sTipoForm, $iId)
    {
        switch ($sFormulario) {
            case 'legislacion':
                 $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_area_aplicacion');
                $aArea = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);
                $aArea[0] = "[Ninguno]";

                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_ambito_aplicacion');
                $aAmbito = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, null);
                $aAmbito[0] = "[Ninguno]";

                if ($sTipoForm == 'UPDATE') {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
                    $oBaseDatos->construir_Tablas(array('legislacion_aplicable', 'documentos'));
                    $oBaseDatos->construir_Where(array('(legislacion_aplicable.id=\'' . $iId . '\')', 'documentos.id=legislacion_aplicable.id_ficha'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sFicha = $aIterador[0];
                    }
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
                    $oBaseDatos->construir_Tablas(array('legislacion_aplicable', 'documentos'));
                    $oBaseDatos->construir_Where(array('(legislacion_aplicable.id=\'' . $iId . '\')', 'documentos.id=legislacion_aplicable.id_ley'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $sLey = $aIterador[0];
                    }
                }

                $aFormulario = array('legislacion_aplicable' => array(array('etiqueta' => gettext('sFMNombre') . ': ', 'columna' => 'nombre'),
                    array('etiqueta' => gettext('sFMLegislacionApli') . ': ', 'columna' => 'id_ley', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sLey, 'action' => 'parent.sndReq(\'documentacion:legislacion:comun:seleccionaley\',\'\',1,\'legislacion_aplicable:id_ley\')')),
                    array('etiqueta' => gettext('sFMFicha') . ': ', 'columna' => 'id_ficha', 'hidden' => 'null',
                        'boton' => array('label' => 'Seleccionar', 'valor' => $sFicha, 'action' => 'parent.sndReq(\'documentacion:legislacion:comun:seleccionaficha\',\'\',1,\'legislacion_aplicable:id_ficha\')')),
                    array('etiqueta' => gettext('sFMArea') . ': ', 'columna' => 'tipo_area', 'select' => $aArea),
                    array('etiqueta' => gettext('sFMAmbito') . ': ', 'columna' => 'tipo_ambito', 'select' => $aAmbito),
                    array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 'true')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['legislacion_aplicable']['id'] = $iId;
                }
                break;

            case 'aspecto':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_impactos');
                $aWheres = array('activo=true');
                $aImpacto = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_aspectos');

                $aAspectos = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_gravedad');
                $aGravedad = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_frecuencia');
                $aFrecuencia = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                $aCampos = array('id', 'nombre');
                $aTablas = array('tipo_magnitud');
                $aWheres = array('id<>3');
                $aMagnitud = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);


                if ($_SESSION['areasactivadas']) {

                    $aFormulario = array('aspectos' => array(array('etiqueta' => gettext('sFMNombre') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                        array('etiqueta' => gettext('sFMMagnitud') . ': ', 'columna' => 'magnitud', 'select' => $aMagnitud),
                        array('etiqueta' => gettext('sFMGravedad') . ': ', 'columna' => 'gravedad', 'select' => $aGravedad),
                        array('etiqueta' => gettext('sFMFrecuencia') . ': ', 'columna' => 'frecuencia', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFMTipoAspecto') . ': ', 'columna' => 'tipo_aspecto', 'select' => $aAspectos),
                        array('etiqueta' => gettext('sFMImpactoAmbiental') . ': ', 'columna' => 'impacto', 'select' => $aImpacto),
                        array('etiqueta' => gettext('sFMObservaciones') . ': ', 'columna' => 'observaciones'),
                        array('etiqueta' => gettext('sFMAreas') . ': ', 'columna' => 'area_id', 'hidden' => $_SESSION['areausuario'])
                    )
                    );
                } else {
                    $aFormulario = array('aspectos' => array(array('etiqueta' => gettext('sFMNombre') . ': ', 'columna' => 'nombre'),
                        array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                        array('etiqueta' => gettext('sFMMagnitud') . ': ', 'columna' => 'magnitud', 'select' => $aMagnitud),
                        array('etiqueta' => gettext('sFMGravedad') . ': ', 'columna' => 'gravedad', 'select' => $aGravedad),
                        array('etiqueta' => gettext('sFMFrecuencia') . ': ', 'columna' => 'frecuencia', 'select' => $aFrecuencia),
                        array('etiqueta' => gettext('sFMTipoAspecto') . ': ', 'columna' => 'tipo_aspecto', 'select' => $aAspectos),
                        array('etiqueta' => gettext('sFMImpactoAmbiental') . ': ', 'columna' => 'impacto', 'select' => $aImpacto),
                        array('etiqueta' => gettext('sFMAreas') . ': ', 'columna' => 'area'),
                        array('etiqueta' => gettext('sFMObservaciones') . ': ', 'columna' => 'observaciones')
                    )
                    );
                }
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['aspectos']['id'] = $iId;
                }
                break;

            case 'aspectoemergencia':
                {
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $aCampos = array('id', 'nombre');
                    $aTablas = array('tipo_impactos');
                    $aWheres = array('activo=true');
                    $aImpacto = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);


                    $aCampos = array('id', 'nombre');
                    $aTablas = array('tipo_severidad');
                    $aSeveridad = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                    $aCampos = array('id', 'nombre');
                    $aTablas = array('tipo_probabilidad');
                    $aProbabilidad = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                    if ($_SESSION['areasactivadas']) {
                        $aCampos = array('id', 'nombre');
                        $aTablas = array('areas');
                        $aWheres = array('activo=true');
                        $aAreas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);
                        $aFormulario = array('aspectos' => array(array('etiqueta' => gettext('sFMNombre') . ': ', 'columna' => 'nombre'),
                            array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                            array('etiqueta' => gettext('sFMSeveridad') . ': ', 'columna' => 'severidad', 'select' => $aSeveridad),
                            array('etiqueta' => gettext('sFMProbabilidad') . ': ', 'columna' => 'probabilidad', 'select' => $aProbabilidad),
                            array('etiqueta' => gettext('sFMTipoAspecto') . ': ', 'columna' => 'tipo_aspecto', 'hidden' => 3),
                            array('etiqueta' => gettext('sFMImpactoAmbiental') . ': ', 'columna' => 'impacto', 'select' => $aImpacto),
                            array('etiqueta' => gettext('sFMObservaciones') . ': ', 'columna' => 'observaciones'),
                            array('etiqueta' => gettext('sFMAreas') . ': ', 'columna' => 'area_id', 'select' => $aAreas)
                        )
                        );
                    } else {

                        $aFormulario = array('aspectos' => array(array('etiqueta' => gettext('sFMNombre') . ': ', 'columna' => 'nombre'),
                            array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                            array('etiqueta' => gettext('sFMSeveridad') . ': ', 'columna' => 'severidad', 'select' => $aSeveridad),
                            array('etiqueta' => gettext('sFMProbabilidad') . ': ', 'columna' => 'probabilidad', 'select' => $aProbabilidad),
                            array('etiqueta' => gettext('sFMTipoAspecto') . ': ', 'columna' => 'tipo_aspecto', 'hidden' => 3),
                            array('etiqueta' => gettext('sFMImpactoAmbiental') . ': ', 'columna' => 'impacto', 'select' => $aImpacto),
                            array('etiqueta' => gettext('sFMAreas') . ': ', 'columna' => 'area'),
                            array('etiqueta' => gettext('sFMObservaciones') . ': ', 'columna' => 'observaciones')
                        )
                        );
                    }

                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['aspectos']['id'] = $iId;
                    }
                    break;
                }

            case 'magnitud':
                $aFormulario = array('tipo_magnitud' => array(
                    array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor')
                )

                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_magnitud']['id'] = $iId;
                }
                break;

            case 'magnitudidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aWheres = null;
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aFormulario = array('tipo_magnitud_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'magnitud', 'hidden' => $_SESSION['magnitud']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_magnitud_idiomas']['id'] = $iId;
                }
                break;

            case 'frecuencia':
                $aFormulario = array('tipo_frecuencia' => array(
                    array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_frecuencia']['id'] = $iId;
                }
                break;

            case 'frecuenciaidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aWheres = null;
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aFormulario = array('tipo_frecuencia_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'frecuencia', 'hidden' => $_SESSION['frecuencia']),

                )
                );

                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_frecuencia_idiomas']['id'] = $iId;
                }
                break;

            case 'gravedad':
                $aFormulario = array('tipo_gravedad' => array(array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor')
                )
                );


                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_gravedad']['id'] = $iId;
                }
                break;

            case 'gravedadidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aWheres = null;
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aFormulario = array('tipo_gravedad_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'gravedad', 'hidden' => $_SESSION['gravedad']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_gravedad_idiomas']['id'] = $iId;
                }
                break;

            case 'severidad':
                $aFormulario = array('tipo_severidad' => array(array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor')
                )
                );


                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_severidad']['id'] = $iId;
                }
                break;

            case 'severidadidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aWheres = null;
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aFormulario = array('tipo_severidad_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'severidad', 'hidden' => $_SESSION['severidad']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_severidad_idiomas']['id'] = $iId;
                }
                break;

            case 'probabilidad':
                $aFormulario = array('tipo_probabilidad' => array(
                    array('etiqueta' => gettext('sFAValor') . ': ', 'columna' => 'valor')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_probabilidad']['id'] = $iId;
                }
                break;

            case 'probabilidadidioma':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('idiomas');
                $aWheres = null;
                $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas, $aWheres);

                $aFormulario = array('tipo_probabilidad_idiomas' => array(array('etiqueta' => gettext('sFANombre') . ': ', 'columna' => 'valor'),
                    array('etiqueta' => gettext('sFAAIdioma') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas),
                    array('etiqueta' => '', 'columna' => 'probabilidad', 'hidden' => $_SESSION['probabilidad']),

                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['tipo_probabilidad_idiomas']['id'] = $iId;
                }
                break;


            case 'formula':
                {
                    $aFormulario = array('formula_aspectos' => array(array('etiqueta' => gettext('sFMFormula') . ': ', 'columna' => 'formula')
                    )
                    );
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['formula_aspectos']['id'] = $iId;
                    }
                    break;
                }
        }
        return $aFormulario;
    }
}