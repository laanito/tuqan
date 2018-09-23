<?php
namespace Tuqan\Classes;

/**
 * Created on 15-oct-2005
 *
 * LICENSE see LICENSE.md file
 *
 *
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 * Esta clase nos producira objetos debugger, los cuales nos iran registrando el punto en el que estamos y el
 * estado de la aplicacion para que nos sea mas sencillo depurar el codigo.
 */

class Debugger
{

    /**
     *    Esta es la matriz que contiene el camino que estamos siguiendo en la aplicacion, tiene la forma
     *    array => archivo => array => metodos, parametros
     * @access private
     * @var array
     */

    private $aPath;

    /**
     *    Este es la ultima posicion del array.
     * @access private
     * @var integer
     */

    private $iUltimo;

    /**
     * debugger constructor.
     * Constructor. Nos crea un objeto debugger empezando en $sInicio
     * @param $sInicio
     * @param $sMetodo
     * @param $aVariables
     */

    function __construct($sInicio, $sMetodo, $aVariables)
    {

        $this->aPath = array();
        $this->iUltimo = -1;
        $this->agregar_Paso($sInicio, $sMetodo, $aVariables);
    }
    //Fin __construct

    /**
     * Esta funcion nos añade un nuevo paso al debugger
     * @param $sPaso
     * @param $sMetodo
     * @param $aVariables
     */
    public function agregar_Paso($sPaso, $sMetodo, $aVariables)
    {
        /**
         * @var array
         */
        $aTmp = array($sPaso, $sMetodo);

        /**
         * @var integer
         */
        if (is_array($aVariables)) {
            foreach ($aVariables as $sValor) {
                $aTmp[] = $sValor;
            }
            $this->aPath[] = $aTmp;
            $this->iUltimo++;
        } else {
            $this->aPath[] = array('ninguna');
            $this->iUltimo++;
        }
    }
    //Fin agregar_Paso

    /**
     *    Esta funcion nos indica el camino seguido paso a paso
     *
     * @access public
     * @return string
     *
     */

    public function mostrar_Path()
    {

        /**
         *  Nuestro indice para iterar
         * @var integer
         */

        $iContador = 0;

        /**
         *     Donde iremos guardando la salida del debugger
         * @var String
         */
        $sCadena = "<table border=1><tr><td><b>Paso</b></td><td><b>".gettext('sArchivo').
            "</b></td><td><b>".gettext('sMetodo')."</b></td><td><b>".gettext('sVariables')."</b></td></tr>";
        while ($iContador <= $this->iUltimo) {
            $aPaso = $this->aPath[$iContador];
            $sCadena .= "<tr><td>" . $iContador . "</td><td>" . $aPaso[0] . "</td><td>" . $aPaso[1] . "</td><td>";
            foreach ($aPaso as $sKey => $sValor) {
                if (($sKey != 0) && ($sKey != 1)) {
                    $sCadena .= $sValor;
                }
            }
            $sCadena .= "</td></tr>";
            $iContador++;
        }
        $sCadena .= "</table>";
        return $sCadena;
    }
    //Fin mostrar_Path
}
