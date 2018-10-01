<?php
namespace Tuqan\Classes;


/**
* LICENSE see LICENSE.md file
 * Este es nuestro generador de formularios

 * @version 0.3.12a
 * @copyright XXX
 */
use \HTML_QuickForm2;
use \HTML_QuickForm2_DataSource_Array;
use \HTML_Page;

class genera_Formularios extends HTML_QuickForm2
{

    // Attributos

    /**
     *    Esta es la estructura de datos, una matriz tridimensional
     * @access private
     * @var array
     */

    private $aMatrizDatos;

    /**
     *    Este es el elemento en el que nos encontramos en cada momento
     * @access private
     * @var integer
     */

    private $iElementoActual;

    /**
     *    Esta la base de datos a la que conectar
     * @access private
     * @var String
     */

    private $sDb;

    /**
     *    Este es el login con el que conectar
     * @access private
     * @var String
     */

    private $sLogin;

    /**
     *    Este es el pass con el que conectar
     * @access private
     * @var String
     */

    private $sPass;

    /**
     *    Esta es la tabla actual en la que nos encotramos
     * @access private
     * @var String
     */

    private $sTablaActual;

    /**
     *    Esta es el tipo de formulario, puede ser 'INSERT' o 'UPDATE'
     * @access private
     * @var String
     */

    private $sTipo;


    /**
     *    Esta es la accion que ha llamado al formulario
     * @access private
     * @var String
     */

    private $sAccion;

    /**
     *    Esta es la cabecera del formulario
     * @access private
     * @var String
     */

    private $sCabecera;

    /**
     *    Esta es la id de la fila a updatear
     * @access private
     * @var integer
     */

    private $iId;

    /**
     *     Este campo especial nos dice cuando el formulario es usado para un cambio de password
     * @access private
     * @var integer
     */
    private $iPassword;

    /**
     *    Aqui tenemos en un array todas las acciones que tenemos que hacer antes de ejecutar el procesamiento
     * @access private
     * @var array
     */

    private $aAntes;

    /**
     *    Aqui tenemos en un array todas las acciones que tenemos que hacer tras ejecutar las consultas del generador
     * @access private
     * @var array
     */

    private $aDespues;

    /**
     *    Numero de entradas que mostramos en el formulario
     * @access private
     * @var integer
     */

    private $iRepeticiones = 1;

    private $sRespuesta;

    private $iAncho;

    private $iFilas;

    private $aPonerValoresDefecto;

    /**
     * genera_Formularios constructor.
     * @param $aDatos
     * @param $aOpciones
     */

    function __construct($aDatos, $aOpciones)
    {
        /**
         *         Inicializamos los campos del objeto
         */
        parent::__construct($this->iId);

        $this->iId = $aOpciones['id'];
        $this->sCabecera = $aOpciones['cabecera'];
        $this->sAccion = $aOpciones['accion'];
        $this->iElementoActual = 0;
        $this->aMatrizDatos = $aDatos['formulario'];
        $this->sDb = $aOpciones['db'];
        $this->sLogin = $aOpciones['login'];
        $this->sPass = $aOpciones['pass'];
        $this->sTipo = $aOpciones['tipo'];
        if(isset($aDatos['antes'])) {
            $this->aAntes = $aDatos['antes'];
        }
        if(isset($aDatos['despues'])) {
            $this->aDespues = $aDatos['despues'];
        }
        $this->iPassword = $aOpciones['password'];
        $this->sRespuesta = $aOpciones['formrespuesta'];

        if (isset($aOpciones['repeticiones'])) {
            $this->iRepeticiones = $aOpciones['repeticiones'];
        }
        if (isset($aOpciones['ponervaloresdefecto'])) {
            $this->aPonerValoresDefecto = $aOpciones['ponervaloresdefecto'];
        }
        $this->iAncho = 64;
        $this->iFilas = 6;
        /**
         * Manejador base de datos
         * @var Object
         */
        $oBaseDatos = new Manejador_Base_Datos($this->sLogin, $this->sPass, $this->sDb);
        if (is_array($this->aMatrizDatos)) {
            $aTablas = array_keys($this->aMatrizDatos);
        }
        /**
         * Para cada tabla que contenga campos para introducir en el formulario
         */

        if (is_array($aTablas)) {
            foreach ($aTablas as $aTabla) {

                /**
                 *  Construimos la sentencia para sacar el tipo de dato, su longitud y si puede ser nulo o no
                 */

                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array("attname AS nombre, ".
                            "pg_type.typname as tipo,CASE WHEN atttypmod=-1 THEN attlen ELSE atttypmod END AS longitud,".
                            " attnotnull AND NOT atthasdef AS nulo"));
                $oBaseDatos->construir_Tablas(array('pg_attribute', 'pg_class', 'pg_type'));
                $oBaseDatos->construir_Where(array('pg_attribute.attrelid=pg_class.oid', 'attnum>0', 'pg_class.relname=\''
                    . $aTabla . '\'', 'pg_type.oid=atttypid'));
                $oBaseDatos->construir_Order(array('attnum ASC'));
                $oBaseDatos->consulta();

                /**
                 *         Para cada Fila del array dado inicialmente le añadimos el nombre del campo, el tipo, su longitud y
                 *         si es nulo o no
                 */

                while ($aIterador = $oBaseDatos->coger_Fila()) {
                    $iPos = 0;
                    while (($this->aMatrizDatos[$aTabla][$iPos] != null) &&
                        ($this->aMatrizDatos[$aTabla][$iPos]['columna'] != $aIterador[0])) {
                        $iPos++;
                    }
                    //Fin while

                    if ($this->aMatrizDatos[$aTabla][$iPos] != null) {
                        //Aqui estamos en la fila de donde extraer informacion
                        $this->aMatrizDatos[$aTabla][$iPos]['campo'] = $aTabla . ":" .
                            $this->aMatrizDatos[$aTabla][$iPos]['columna'];
                        $this->aMatrizDatos[$aTabla][$iPos]['tipo'] = $aIterador[1];
                        $this->aMatrizDatos[$aTabla][$iPos]['longitud'] = $aIterador[2];
                        $this->aMatrizDatos[$aTabla][$iPos]['nulo'] = $aIterador[3];
                    }
                    //Fin if
                }
                //Fin while
                $oBaseDatos->desconexion();

                /**
                 *         En el caso de que queramos hacer UPDATE, ademas sacamos el valor que tenia el campo almacenado
                 */

                if ($this->sTipo == "UPDATE") {
                    $aCampos = array();
                    /**
                     * Aqui sacamos los nombres de las columnas
                     */

                    if (is_array($this->aMatrizDatos)) {
                        foreach ($this->aMatrizDatos[$aTabla] as $sKey => $aFila) {
                            //cambio para no meter la checkbox de discriminacion de campos
                            if (is_array($aFila)) {
                                $aSplit = explode(':', $aFila['campo']);
                                $aCampos[] = $aSplit[1];
                            }
                            //Fin if
                        }
                    }
                    //Fin foreach
                    /**
                     * Iniciamos una consulta para sacar los valores de los campos dados
                     */
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos($aCampos);
                    $oBaseDatos->construir_Tablas(array($aTabla));
                    $oBaseDatos->construir_Where(array(('id=\'' . $this->aMatrizDatos[$aTabla]['id'] . '\'')));
                    $oBaseDatos->consulta();
                    if ($aIterador = $oBaseDatos->coger_Fila()) {
                        /**
                         *    Añadimos los valores al array de datos
                         */

                        if (is_array($this->aMatrizDatos)) {
                            foreach ($this->aMatrizDatos[$aTabla] as $sKey => $Value) {
                                if ((string)$sKey != 'id') {
                                    $this->aMatrizDatos[$aTabla][$sKey]['valor'] = $aIterador[$sKey];
                                }
                                //Fin if
                            }
                        }
                        //Fin foreach
                    }
                    $oBaseDatos->desconexion();
                    //Fin if
                }
                //Fin if
            }
            //Fin foreach
        }
    }
    //Fin __construct

    /**
     *    Añade un campo password con otro campo comprobar password y su check
     * @param $sCampo
     * @param $sEtiqueta
     * @param $iLongitud
     * @throws \HTML_QuickForm2_InvalidArgumentException
     * @throws \HTML_QuickForm2_NotFoundException
     */
    private function agrega_Pass($sCampo, $sEtiqueta, $iLongitud)
    {
        $this->addElement(
            'password',
            $sCampo,
            array('size' => $iLongitud,'maxlength' => $iLongitud),
            array('label' => $sEtiqueta));
        $this->addElement(
            'password',
            $sCampo . "check",
            array('size' => $iLongitud, 'maxlength' => $iLongitud),
            array('label' => "Repetir Password: "));
    }


    /**
     *    Añade un campo al formulario dependiendo del tipo, longitud, etc
     * @param $aValores
     * @throws \HTML_QuickForm2_InvalidArgumentException
     * @throws \HTML_QuickForm2_NotFoundException
     */
    private function agrega_Elemento($aValores)
    {
        //Si son datos que ya le metemos por defecto le metemos como campo hidden
        if(isset($aValores['hidden'])) {
            if (is_string($aValores['hidden']) || (is_integer($aValores['hidden']))) {
                $this->addElement('hidden', $aValores['campo'], null, array('value' => $aValores['hidden']));
            } else if (is_array($aValores['hidden'])) {
                $Fieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['campo']);
                $group = $Fieldset->addGroup($aValores['campo']);
                $group->addHidden('', array('value' => $aValores['hidden'][1]));
                $group->addHidden('', array('value' => $aValores['hidden'][2]));
                $group->addHidden('', array('value' => 0));
                $group->addHidden('', array('value' => 0));
            }
        } else if (isset($aValores['texto'])) {
            $this->addStatic(array('content' =>$aValores['texto']));
        } else if (isset($aValores['hierselect'])) {
            $opts[] = $aValores['hierselect'][0];
            $opts[] = $aValores['hierselect'][1];
            $aOptions = array('options' => $opts);
            $this->addElement('hierselect', $aValores['campo'], $aValores['etiqueta'], $aOptions);
        } else if (isset($aValores['select'])) {
            $this->addElement('select', $aValores['campo'], $aValores['etiqueta'], $aValores['select']);
        } else if (isset($aValores['select'])){
            if($aValores['check'] == "si") {
            $this->addElement('advcheckbox', $aValores['campo'], $aValores['etiqueta'],
                array('values' => array(null, null, 0, 1)));
            }
        } else if (preg_match("/char/", $aValores['tipo'])) {
            if ($aValores['longitud'] < $this->iAncho) {
                $sComprobacion = explode(':', $aValores['campo']);
                if ($sComprobacion[1] == "password") {
                    //No lo añadimos si es update, y no estamos cambiando el pass
                    if (($this->sTipo != 'UPDATE') || ($this->iPassword == 1)) {
                        $this->agrega_Pass($aValores['campo'], $aValores['etiqueta'], $aValores['longitud']);
                    }
                } else {
                    $this->addElement('text', $aValores['campo'],
                        array('size' => $aValores['longitud'], 'maxlength' => $aValores['longitud'])
                        , array('label'=>$aValores['etiqueta']));
                }
            } else {
                if (array_key_exists('textarea', $aValores) && $aValores['textarea'] == false) {
                    $this->addElement(
                        'text',
                        $aValores['campo'],
                        array('size' => $this->iAncho, 'maxlength' => $aValores['longitud']),
                        array('label' => $aValores['etiqueta'])
                    );
                } else {
                    $this->addElement(
                        'textarea',
                        $aValores['campo'],
                        array('cols' => $this->iAncho, 'rows' => $this->iFilas),
                        array('label'=>$aValores['etiqueta'])
                    );
                }
            }
        } //Si es entero, flotante o serial le damos una longitud al campo dependiendo de la magnitud
        else if (preg_match("/int/", $aValores['tipo']) || preg_match("/serial/", $aValores['tipo'])) {
            if (preg_match("/4/", $aValores['tipo'])) {
                $this->addElement(
                    'text',
                    $aValores['campo'],
                    array('size' => 12),
                    array('value' => $aValores['etiqueta'])
                );
            } else if (preg_match("/2/", $aValores['tipo'])) {
                $this->addElement(
                    'text',
                    $aValores['campo'],
                    array('size' => 6),
                    array('label' => $aValores['etiqueta'])
                );
            } else if (preg_match("/8/", $aValores['tipo'])) {
                $this->addElement(
                    'text',
                    $aValores['campo'],
                    array('size' => 22),
                    array('label' => $aValores['etiqueta'])
                );
            } else {
                $this->addElement(
                    'text',
                    $aValores['campo'],
                    array('size' => 12),
                    array('value' =>$aValores['etiqueta'])
                );
            }
        } else if (preg_match("/timestamp/", $aValores['tipo'])) {
            $oFieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['etiqueta']);
            $oGroup=$oFieldset->addGroup($aValores['campo'])
                ->setLabel($aValores['etiqueta'])
                ->setSeparator(' : ');
            $oGroup->addText('day', array('size' => 2), null);
            $oGroup->addText('month', array('size' => 2), null);
            $oGroup->addText('year', array('size' => 4),null);
            $oGroup->addText('hour',  array('size' => 2), null);
            $oGroup->addText('minute', array('size' => 2), null);
            //Condicion para hacer hovers en los botones
            $aEstiloBoton = array("class" => "b_activo",
                "onclick" => "displayCalendarTextBox('" .
                    $aValores['campo'] . "[2]','" .
                    $aValores['campo'] . "[1]','" .
                    $aValores['campo'] . "[0]',false,false,this)"
            );
            $oFieldset->addElement(
                'button',
                'Calendario',
                $aEstiloBoton,
                array('label' => gettext('sTCalentario'))
            );

        } else if (preg_match("/date/", $aValores['tipo'])) {
            $oFieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['etiqueta']);
            $oGroup=$oFieldset->addGroup($aValores['campo'])
                ->setLabel($aValores['etiqueta'])
                ->setSeparator(' : ');
            $oGroup->addText('day', array('size' => 2), null);
            $oGroup->addText('month', array('size' => 2), null);
            $oGroup->addText('year',  array('size' => 4), null);

            //Condicion para hacer hovers en los botones
            $aEstiloBoton = array("class" => "b_activo",
                "onclick" => "displayCalendarTextBox('" .
                    $aValores['campo'] . "[2]','" .
                    $aValores['campo'] . "[1]','" .
                    $aValores['campo'] . "[0]',false,false,this)"
            );
            $oFieldset->addElement(
                'button',
                'Calendario',
                $aEstiloBoton ,
                array('label' => gettext('sTCalentario')) );
        } //Para valor booleano ponemos un radiobutton
        else if (preg_match("/bool/", $aValores['tipo'])) {
            if (!isset($aValores['bool'])) {
                $oFieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['etiqueta']);
                $oFieldset->addRadio(
                    null,
                    null,
                    array('label'=>'Si', 'value'=>'1')
                );
                $oFieldset->addRadio(
                    null,
                    null,
                    array('label'=>'No', 'value'=>'0')
                );
            } else {
                $oFieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['etiqueta']);
                $oFieldset->addRadio(
                    null,
                    null,
                    array('label'=>$aValores['bool'][0], 'value'=>'1')
                );
                $oFieldset->addRadio(
                    null,
                    null,
                    array('label'=>$aValores['bool'][1], 'value'=>'0'));
            }
        } //Los campos text los tratamos como un varchar grande
        else if (preg_match("/text/", $aValores['tipo'])) {
            $this->addElement(
                'textarea',
                $aValores['campo'],
                $aValores['etiqueta'],
                array('cols' => $this->iAncho, 'rows' => $this->iFilas)
            );
        } else if (preg_match("/numeric/", $aValores['tipo']) || preg_match("/float/", $aValores['tipo'])) {
            $oFieldset = $this->addFieldset($aValores['campo'])->setLabel($aValores['etiqueta']);
            $oGroup=$oFieldset->addGroup($aValores['campo'])
                ->setLabel($aValores['etiqueta'])
                ->setSeparator(' : ');
            $oGroup->addText('euros', '', array('size' => 2));
            $oGroup->addText('centimos', '', array('size' => 2));
        }
        //Si lleva un boton al lado lo metemos ahora
        if ((isset($aValores['boton'])) && (count($aValores['boton']) > 1)) {
            if (is_string($aValores['hidden'])) {
                $this->addElement(
                    'text',
                    $aValores['campo'] . "texto",
                    null,
                    array(
                        'label' => $aValores['etiqueta'],
                        'value' => $aValores['boton']['valor'],
                        'disabled' => 'true')
                );
            }
            $aEstiloBoton = array("class" => "b_activo",
                "onclick" => $aValores['boton']['action']
            );


            $this->addElement(
                'button',
                "boton",
                $aEstiloBoton,
                array('label' => $aValores['boton']['label'])
            );
        }
    }
    //Fin agrega_Elemento


    /**
     *    Añade una rule de longitud maxima a no ser que el chequeo de tipos ya lo haya hecho
     *
     * @access private
     * @param String $sCampo
     * @param String $iLongitud
     * @param String $sTipo
     */

    /**
     *    Genera el formulario con los datos que ya tenemos almacenados, incluye todas sus rules
     *
     * @access public
     * @param $mCampo
     * @param $sDatos
     * @return bool
     */
    function tipoOk($mCampo, $sDatos)
    {
        $bOk = false;
        if (preg_match("/char/", $sDatos)) {
            $bOk = (!is_array($sDatos) && !is_object($sDatos));
        } else if (preg_match("/int/", $sDatos)) {
            $iCopiaCampo = $mCampo;
            if (!is_array($iCopiaCampo)) {
                $aCopiaCampo = array($mCampo);
            } else {
                $aCopiaCampo = $iCopiaCampo;
            }
            $bOk = true;
            foreach ($aCopiaCampo as $iCopiaCampo) {
                if (is_numeric($iCopiaCampo)) {
                    if (preg_match("/2/", $sDatos)) {
                        $bOk2 = (($mCampo >= pow(-2, 15)) && ($mCampo <= pow(+2, 15) - 1));
                    } else if (preg_match("/4/", $sDatos)) {
                        $bOk2 = (($mCampo >= pow(-2, 31)) && ($mCampo <= pow(+2, 31) - 1));
                    } else if (preg_match("/8/", $sDatos)) {
                        $bOk2 = (($mCampo >= pow(-2, 63)) && ($mCampo <= pow(+2, 63) - 1));
                    } else if (($iCopiaCampo == 'null') || (is_null($iCopiaCampo))) {
                        $bOk2 = true;
                    } else {
                        $bOk2 = false;
                    }
                    $bOk = $bOk && $bOk2;
                } else if ($iCopiaCampo == 'null') {
                    $bOk = true;
                } else {
                    $bOk = false;
                }
            }
        } else if (preg_match("/timestamp/", $sDatos)) {
            $bOk4 = (($mCampo[0] == "") && ($mCampo[1] == "") && ($mCampo[2] == "") && (($mCampo[3] == "") || ($mCampo[3] == 0)) && (($mCampo[4] == "") || ($mCampo[4] == "0")));
            $bOk1 = checkdate((int)$mCampo[1], (int)$mCampo[0], (int)$mCampo[2]);
            $bOk2 = (($mCampo[3] < 24) && ($mCampo[3] > -1));
            $bOk3 = (($mCampo[4] < 60) && ($mCampo[4] > -1));
            $bOk = (($bOk1) && ($bOk2) && ($bOk3));
            $bOk = ($bOk) && (is_numeric($mCampo[0]) && is_numeric($mCampo[1]) &&
                    is_numeric($mCampo[2]) &&
                    is_numeric($mCampo[3]) &&
                    is_numeric($mCampo[4])) || $bOk4;
            //Comprobamos que no es nula
        } else if (preg_match("/date/", $sDatos)) {
            $bOk = checkdate((int)$mCampo[1], (int)$mCampo[0], (int)$mCampo[2]);
            $bOk = ($bOk) && (is_numeric($mCampo[0]) && is_numeric($mCampo[1]) && is_numeric($mCampo[2]));
            if ((strlen($mCampo[0]) == 0) && (strlen($mCampo[1]) == 0) && (strlen($mCampo[2]) == 0)) {
                $bOk = true;
            }
        } else if (preg_match("/bool/", $sDatos)) {
            //siempre sera true por que va con radio buttons con valores 0 o 1
            $bOk = true;
        } else if (preg_match("/text/", $sDatos)) {
            $bOk = (!is_array($sDatos) && !is_object($sDatos));
        } else if (preg_match("/numeric/", $sDatos) || (preg_match("/float/", $sDatos))) {
            $bOk1 = (($mCampo[0] >= pow(-2, 31)) && ($mCampo[0] <= pow(+2, 31) - 1));
            $bOk2 = (($mCampo[1] >= 0) && ($mCampo[1] < 100)) || ($mCampo[1] == "");
            $bOk = ($bOk1) && ($bOk2) && (is_numeric($mCampo[0]) && (is_numeric($mCampo[1])));
        }
        return $bOk;
    }
    //Fin tipoOk


    /**
     *    Genera el formulario con los datos que ya tenemos almacenados, incluye todas sus rules
     *
     * @throws \HTML_QuickForm2_InvalidArgumentException
     * @throws \HTML_QuickForm2_NotFoundException
     */
    public function generar()
    {
        $this->addElement('hidden', 'action', array('value' => $this->sRespuesta));
        $this->addElement('hidden', 'datos', array('value' => $this->sAccion . separador . $this->iId));
        $this->setName($this->sCabecera);
        $iContadorTabla = 0;
        if (is_array($this->aMatrizDatos)) {
            $aTablas = array_keys($this->aMatrizDatos);
        }

        // TODO change rule validation form Quickform style to Quickform2
 //       $this->registerRule('checkType', 'function', 'tipoOK', $this);

        $aDefaults = array();

        /**
         * Para cada Tabla Generamos los campos, si queremos poner un header para cada campo debemos hacerlo
         * antes del foreach
         */

        if (is_array($this->aMatrizDatos)) {
            for ($iContador = 1; $iContador <= $this->iRepeticiones; $iContador++) {
                foreach ($this->aMatrizDatos as $aValue) {
                    $this->sTablaActual = $aTablas[$iContadorTabla];

                    $this->iElementoActual = 0;
                    /**
                     *     Para cada campo de la tabla que hayamos seleccionado lo añadimos
                     */
                    if (is_array($aValue)) {
                        foreach ($aValue as $sKey => $aValores) {
                            if ($iContador > 1) {
                                //Sustituimos los valores de campos para que pueda haber muchos (sino los campos
                                //tendrian el mismo id y fallaria), le ponemos un numero a la tabla que despues
                                //en el procesar sera identificada
                                $aValores['etiqueta'] = rtrim($aValores['etiqueta'], ':') . " " . $iContador . ": ";
                                $aCampo = explode(':', $aValores['campo']);
                                $aValores['campo'] = $aCampo[0] . $iContador . ":" . $aCampo[1];
                            }
                            if (($sKey == "0") || (($sKey != 'id') && ($sKey != 'checkbox') && ($sKey != 'oculto'))) {
                                $this->agrega_Elemento($aValores);
                            }
                            /**
                             * Si es update mostramos el valor que ya tenia el campo
                             */
                            if ($this->sTipo == 'UPDATE') {
                                if ($aValores['tipo'] == "timestamp") {
                                    $aFecha = explode(' ', $aValores['valor']);
                                    $aDate = explode('-', $aFecha[0]);
                                    $aHora = explode(':', $aFecha[1]);
                                    $aDefaults[$aValores['campo']] = array($aDate[2], $aDate[1], $aDate[0], $aHora[0], $aHora[1]);
                                } else if ($aValores['tipo'] == "date") {
                                    $aFecha = explode('-', $aValores['valor']);
                                    $aDefaults[$aValores['campo']] = array($aFecha[2], $aFecha[1], $aFecha[0]);
                                } else if (($aValores['tipo'] == "numeric") || (preg_match("/float/", $aValores['tipo']))) {
                                    $aSeparar = explode('\.', $aValores['valor']);
                                    if (!$aSeparar[1]) {
                                        $aSeparar[1] = '00';
                                    }
                                    $aDefaults[$aValores['campo']] = array($aSeparar[0], $aSeparar[1]);
                                } else if ($aValores['tipo'] == "bool") {
                                    if ($aValores['valor'] == 't') {
                                        $aDefaults[$aValores['campo']] = 1;
                                    } else {
                                        $aDefaults[$aValores['campo']] = 0;
                                    }
                                } else if ($aValores['hierselect']) {
                                    $aDefaults[$aValores['campo']] = array($aValores['valor'], $_SESSION['formaud']);
                                } else {
                                    $aDefaults[$aValores['campo']] = $aValores['valor'];
                                }
                            } else {
                                /*
                                 * Ponemos valor por defecto a los float y numericos por comodidad
                                 */
                                if (($aValores['tipo'] == "numeric") || (preg_match("/float/", $aValores['tipo']))) {
                                    $aDefaults[$aValores['campo']] = array('0', '00');
                                }

                                if ($aValores['tipo'] == "timestamp") {
                                    $aDefaults[$aValores['campo']] = array('', '', '', '00', '00');
                                }
                                if (isset($this->aPonerValoresDefecto)) {
                                    foreach ($this->aPonerValoresDefecto as $iLlave => $aValor) {
                                        if ($aValores['campo'] == $iLlave) {
                                            $aFecha = explode("/", $aValor, 3);
                                            $aDefaults[$aValores['campo']] = array($aFecha[0], $aFecha[1], $aFecha[2]);
                                        }
                                    }
                                }
                                $this->iElementoActual++;
                            }
                            /**
                             * Si el valor no puede ser nulo aadimos la rule correspondiente
                             */
                            // @TODO revisar reglas de formularios para actualizar a QF2
//                            if ($aValores['nulo'] == "t") {
//                                $this->addRule($aValores['campo'], 'Campo requerido', 'required');
//                            }
                            /**
                             * Aadimos las reglas de tipo y longitud
                             */
/*                            if ($aValores['hidden'] == null) {
                                $this->addRule(
                                    $aValores['campo'],
                                    'Tipo no concuerda, debe ser: ' . $aValores['tipo'],
                                    'checkType', $aValores['tipo']);
                            }*/
                        }
                        $iContadorTabla++;
                    }
                }
            }
            $this->addDataSource(new HTML_QuickForm2_DataSource_Array($aDefaults));

            $aBotones = array("class" => "b_activo");
            $oFieldset = $this->addFieldset('actions')->setLabel(gettext('Actions'));

            $oFieldset->addReset('reset', $aBotones, array('value' =>'Limpiar'));
            $oFieldset->addSubmit('submit',$aBotones, array('value' =>'Enviar'));
        }
    }
    //Fin generar

    /**
     *         Realiza las consultas previas o posteriores a la accion del formulario, viene dado por el parametro aAntes o
     *         oDespues
     * @param         $oBaseDatos        object        Referencia a un objeto del manejador de base de datos
     * @param        $sOrden            string        Cadena que nos dice si son consultas anteriores o posteriores
     * @access private
     */
    private function consultasExtras(&$oBaseDatos, $sOrden)
    {
        if ($sOrden == 'antes') {
            $aConsultas = $this->aAntes;
        } else {
            $aConsultas = $this->aDespues;
        }
        foreach ($aConsultas as $aPreConsulta) {
            if ($aPreConsulta['tipo'] == 'INSERT') {
                $oBaseDatos->iniciar_Consulta('INSERT');
                if (is_array($aPreConsulta['campos'])) {
                    $oBaseDatos->construir_Campos($aPreConsulta['campos']);
                    if (is_array($aPreConsulta['tablas'])) {
                        $oBaseDatos->construir_Tablas($aPreConsulta['tablas']);
                        if (is_array($aPreConsulta['value'])) {
                            $oBaseDatos->construir_Value($aPreConsulta['value']);
                            $oBaseDatos->consulta();
                        }
                    }
                }
            } else {
                $oBaseDatos->iniciar_Consulta('UPDATE');
                if (is_array($aPreConsulta['setsin'])) {
                    $oBaseDatos->construir_SetSin($aPreConsulta['setsin'][0], $aPreConsulta['setsin'][1]);
                    if (is_array($aPreConsulta['tablas'])) {
                        $oBaseDatos->construir_Tablas($aPreConsulta['tablas']);
                        if (is_array($aPreConsulta['wheres'])) {
                            $oBaseDatos->construir_Where($aPreConsulta['where']);
                        }
                        $oBaseDatos->consulta();
                    }
                } else if (is_array($aPreConsulta['set'])) {
                    $oBaseDatos->construir_Set($aPreConsulta['set'][0], $aPreConsulta['set'][1]);
                    if (is_array($aPreConsulta['tablas'])) {
                        $oBaseDatos->construir_Tablas($aPreConsulta['tablas']);
                        if (is_array($aPreConsulta['wheres'])) {
                            $oBaseDatos->construir_Where($aPreConsulta['where']);
                        }
                        $oBaseDatos->consulta();
                    }
                }
            }
        }
    }

    /**
     *    Inserta en la base de datos
     *
     * @access public
     *
     */

    public function process()
    {
        $aInsertar = array();
        $oBaseDatos = new Manejador_Base_Datos($this->sLogin, $this->sPass, $this->sDb);
        $oBaseDatos->comienza_transaccion();

        //Antes de ejecutar la accion del formulario ejecutamos las acciones que teniamos que hacer antes
        if (is_array($this->aAntes)) {
            $this->consultasExtras($oBaseDatos, 'antes');
        }
        if (is_array($_POST)) {
            foreach ($_POST as $sKey => $sCampo) {
                //Si el campo es vacio ponemos null para cuando hagamos el insert o update en la db
                if ($sCampo == null) {
                    $sCampo = 'null';
                }
                if (($sKey != "buttons") && ($sKey != "action") && ($sKey != "datos")) {
                    $aDatos = explode(':', $sKey);
                    // Añadimos el dato al array comprobando que quitamos los __(No se por que salen con los advcheckbox)
                    if (!preg_match("/__/", $sKey)) {
                        $aInsertar[$aDatos[0]][$aDatos[1]] = $sCampo;
                    }
                }
            }
        }
        if (is_array($aInsertar)) {
            foreach ($aInsertar as $sKey => $aValor) {
                //discriminamos la checkbox que nos puede dar problemas aqui
                if ($this->sTipo == "INSERT") {
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    //Filtramos el nombre de la tabla, si termina en un numero lo quitamos, esto se hace para
                    //poder hacer las multiples inserciones en una tabla
                    if (preg_match('/[0-9]/', $sKey)) {
                        $sKey = rtrim($sKey, '0123456789');
                    }
                    $oBaseDatos->pon_Tabla($sKey);
                    if (is_array($aValor)) {
                        foreach ($aValor as $sCampo => $sValor) {
                            //Si era un campo de comprobacion para password no lo procesamos
                            if (!preg_match('/check$/', $sCampo)) {
                                //POSIBLES PROBLEMAS CON OTROS DATOS ARRAY
                                if (is_array($sValor)) {
                                    if ((($sValor[0] == "") && ($sValor[1] == "") && ($sValor[2] == "") && ($sValor[3] == "") && ($sValor[4] == ""))
                                        || (($sValor[0] == "") && ($sValor[1] == ""))) {
                                        $sValor = 'null';
                                    } else if ($sValor[2] != null) {
                                        $sValor = $sValor[2] . "-" . $sValor[1] . "-" . $sValor[0];
                                    } else if (($sKey == "acciones_mejora") && ($sCampo == 'tipo')) {
                                        $sAud = $sValor[1];
                                        $sValor = $sValor[0];
                                        $oBaseDatos->pon_Campo('auditoria');
                                        $oBaseDatos->pon_Value($sAud);
                                    } else {

                                        if ($sValor[1] == "") {
                                            $sValor[1] = 0;
                                        }
                                        $sValor = $sValor[0] . "." . $sValor[1];
                                    }
                                }
                                if ($sCampo == "password") {
                                    $sValor = md5($sValor);
                                }
                                $oBaseDatos->pon_Campo($sCampo);
                                $oBaseDatos->pon_Value($sValor);
                            }
                        }
                    }
                } else {
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->pon_Tabla($sKey);
                    foreach ($aValor as $sCampo => $sValor) {
                        if (!preg_match('/check$/', $sCampo)) {
                            if (is_array($sValor)) {
                                if ((($sValor[0] == "") && ($sValor[1] == "") && ($sValor[2] == "") && ($sValor[3] == "") && ($sValor[4] == ""))
                                    || (($sValor[0] == "") && ($sValor[1] == "")) || (($sValor[0] == "") && ($sValor[1] == "") && ($sValor[2] == ""))) {
                                    $sValor = 'null';
                                } /*
                                        * lamigo: ¿no haciamos el insert de las horas?
                                        */
                                else if ($sValor[3] != null) {
                                    $sValor = $sValor[2] . "-" . $sValor[1] . "-" . $sValor[0] . ":" . $sValor[3] . ":" . $sValor[4];
                                } else if ($sValor[2] != null) {
                                    $sValor = $sValor[2] . "-" . $sValor[1] . "-" . $sValor[0];
                                } else if (($sKey == "acciones_mejora") && ($sCampo == 'tipo')) {
                                    $sAud = $sValor[1];
                                    $sValor = $sValor[0];
                                    $oBaseDatos->pon_Campo('auditoria');
                                    $oBaseDatos->pon_Value($sAud);
                                } else {
                                    $sValor = $sValor[0] . "." . $sValor[1];
                                }
                            }
                            if ($sCampo == "password") {
                                $sValor = md5($sValor);
                            }
                            if ($sValor == 'null') {
                                $oBaseDatos->pon_SetSin($sCampo, $sValor);
                            } else {
                                $oBaseDatos->pon_Set($sCampo, $sValor);
                            }
                        }
                    }
                    $oBaseDatos->pon_Where('id=' . $this->aMatrizDatos[$sKey]['id']);
                }
                $oBaseDatos->consulta();
            }
        }
        //Hacemos las consultas que nos hayan indicado
        if (is_array($this->aDespues)) {
            $this->consultasExtras($oBaseDatos, 'despues');
        }

        $oBaseDatos->termina_transaccion();
        $formatoPagina =new Formato_Pagina();
        $aParametros = $formatoPagina->variables_Pagina($_SESSION['navegador'], $_SESSION['sistema_operativo']);


        $oPagina = new HTML_Page(array('charset' => $aParametros[0],
                'language' => $aParametros[1],
                'cache' => $aParametros[2],
                'lineend' => $aParametros[3]
            )
        );
        $oPagina->addScriptDeclaration('onload=parent.atras();', 'text/javascript');
        $oPagina->addBodyContent("<div align='center'>");
        $oPagina->addBodyContent("Operacion Correcta<br />".
                "<br /><input type=\"button\" class=\"b_activo\" value=\"Atras\" onclick=\"parent.atras(-2)\",'',1)\">");
        $oPagina->addBodyContent("</div>");
        $oPagina->display();
    }
    //Fin process
}


