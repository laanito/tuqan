<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 *

 * @version 1.0a
 *
 * Archivo de funciones separado de los demas para Javi
 */

class Form_Nuevo
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
            case 'boton':
                {
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $aCampos = array('id', 'nombre');
                    $aTablas = array('tipo_botones');
                    $aTiposBotones = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);

                    $aFormulario = array('botones' => array(array('etiqueta' => gettext('sFNAccion') . ': ', 'columna' => 'accion'),
                        array('etiqueta' => gettext('sFNTipo') . ': ', 'columna' => 'tipo_botones', 'select' => $aTiposBotones),
                        array('etiqueta' => '', 'columna' => 'menu', 'hidden' => $_SESSION['menu'])
                    )
                    );
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['botones']['id'] = $iId;
                    } else {
                        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                        //Debemos meter valores por defecto para los idiomas del menu, sacamos el id del que sera el menu
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('max(id)'));
                        $oBaseDatos->construir_Tablas(array('botones'));
                        $oBaseDatos->consulta();
                        $aDevolver = $oBaseDatos->coger_Fila();
                        $iIdBoton = $aDevolver[0] + 1;//este será el id del menu

                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('id'));
                        $oBaseDatos->construir_Tablas(array('idiomas'));
                        $oBaseDatos->consulta();
                        $aDespues = array();
                        while ($aDevolver = $oBaseDatos->coger_Fila()) {
                            $aDespues[] = array('tipo' => 'INSERT', 'campos' => array('boton', 'valor', 'idioma_id'),
                                'tablas' => array('botones_idiomas'), 'value' => array($iIdBoton, "''", $aDevolver[0]));
                        }
                    }
                    $aFormulario = array('despues' => $aDespues, 'form' => $aFormulario);
                    break;
                }
            case 'idiomamenu':
                {
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $aCampos = array('id', 'nombre');
                    $aTablas = array('idiomas');
                    $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                    $aFormulario = array('menu_idiomas_nuevo' => array(array('etiqueta' => gettext('sFNValor') . ': ', 'columna' => 'valor'),
                        array('etiqueta' => gettext('sFNValor') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas)

                    )
                    );
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['menu_idiomas_nuevo']['id'] = $iId;
                    }

                    break;
                }
            case 'idiomaboton':
                {
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                    $aCampos = array('id', 'nombre');
                    $aTablas = array('idiomas');
                    $aIdiomas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                    $aFormulario = array('menu_idiomas_nuevo' => array(array('etiqueta' => gettext('sFNValor') . ': ', 'columna' => 'valor'),
                        array('etiqueta' => gettext('sFNValor') . ': ', 'columna' => 'idioma_id', 'select' => $aIdiomas)

                    )
                    );

                    /*$aFormulario= array ('botones_idiomas' => array(array('etiqueta'=>gettext('sFNValor').': ', 'columna'=>'valor')
                                                                      )
                                           );*/
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['botones_idiomas']['id'] = $iId;
                    }
                    break;
                }
            case 'idioma':
                {
                    $aFormulario = array('idiomas' => array(array('etiqueta' => gettext('sFNNombre') . ': ', 'columna' => 'nombre')
                    )
                    );
                    if ($sTipoForm == 'UPDATE') {
                        $aFormulario['idiomas']['id'] = $iId;
                    } else {
                        $aDespues = array();
                        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                        //Sacamos el id del idioma a meter
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('max(id)'));
                        $oBaseDatos->construir_Tablas(array('idiomas'));
                        $oBaseDatos->consulta();
                        if ($aDevolver = $oBaseDatos->coger_Fila()) {
                            $iIdIdioma = $aDevolver[0] + 1;
                        }
                        //Debemos meter valores por defecto para los idiomas del menu, duplicamos las entradas de castellano
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('menu', 'valor'));
                        $oBaseDatos->construir_Tablas(array('menu_idiomas_nuevo'));
                        $oBaseDatos->construir_Where(array('idioma_id=1'));
                        $oBaseDatos->consulta();
                        while ($aDevolver = $oBaseDatos->coger_Fila()) {
                            $aDespues[] = array('tipo' => 'INSERT', 'campos' => array('menu', 'valor', 'idioma_id'),
                                'tablas' => array('menu_idiomas_nuevo'), 'value' => array($aDevolver[0], $aDevolver[1], $iIdIdioma));
                        }
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('boton', 'valor'));
                        $oBaseDatos->construir_Tablas(array('botones_idiomas'));
                        $oBaseDatos->construir_Where(array('idioma_id=1'));
                        $oBaseDatos->consulta();
                        while ($aDevolver = $oBaseDatos->coger_Fila()) {
                            $aDespues[] = array('tipo' => 'INSERT', 'campos' => array('boton', 'valor', 'idioma_id'),
                                'tablas' => array('botones_idiomas'), 'value' => array($aDevolver[0], $aDevolver[1], $iIdIdioma));
                        }
                    }
                    $aFormulario = array('despues' => $aDespues, 'form' => $aFormulario);
                    break;
                }
            case 'horarioauditoria':
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $aCampos = array('id', 'nombre');
                $aTablas = array('areas');
                $aAreas = $this->sacar_Datos_Select($oBaseDatos, $aCampos, $aTablas);
                $aFormulario = array('horario_auditoria' => array(array('etiqueta' => gettext('sFCHorainicio') . ': ', 'columna' => 'horainicio'),
                    array('etiqueta' => gettext('sFCHorafin') . ': ', 'columna' => 'horafin'),
                    array('etiqueta' => gettext('sFCRequisitos') . ': ', 'columna' => 'requisito'),
                    array('etiqueta' => gettext('sFCArea') . ': ', 'columna' => 'area', 'select' => $aAreas),
                    array('etiqueta' => gettext('sFCAuditor') . ': ', 'columna' => 'auditor')
                )
                );
                if ($sTipoForm == 'UPDATE') {
                    $aFormulario['horario_auditoria']['id'] = $iId;
                }
                break;
        }
        return $aFormulario;
    }
}