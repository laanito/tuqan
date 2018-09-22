<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 *         Clase para generar sencillas cadenas SQL(INSERT,UPDATE y SELECT)
 *

 * @author Alejandra Jess Garca Romero <u>agromero@islanda.es</u>
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 *
 */

class generador_SQL
{
    // Atributos

    /**
     *    Esta es la cadena SQl
     * @access private
     * @var array
     */

    private $sSql;

    /**
     *    Este es el array con los campos a seleccionar en el SELECT o a insertar
     * @access private
     * @var array
     */

    private $aCampos;

    /**
     *    Este es el array con las tablas de donde seleccionar, o la tabla donde hacer INSERT o UPDATE
     * @access private
     * @var array
     */

    private $aTablas;

    /**
     *    Este es el array con las condiciones WHERE
     * @access private
     * @var array
     */

    private $aWheres;

    /**
     *    Este es el array con los GROUP
     * @access private
     * @var array
     */

    private $aGroups;

    /**
     *    Este es el array con los ORDER
     * @access private
     * @var array
     */

    private $aOrders;

    /**
     *    Este es el array con los Values a insertar
     * @access private
     * @var array
     */

    private $aValues;

    /**
     *    Este es el array con los SET a hacer en el UPDATE
     * @access private
     * @var array
     */

    private $aSets;

    /**
     *    Este es el tipo de SQL que vamos a construir
     * @access private
     * @var String
     */

    private $sTipo;

    // Fin Atributos

    //  Metodos

    /**
     *             Constructor
     * @param String $sTipo
     * @access public
     */

    function __construct($sTipo)
    {
        $this->sTipo = $sTipo;
        switch ($sTipo) {
            case 'SELECT':
                $this->aCampos = array();
                $this->aTablas = array();
                $this->aWhere = array();
                $this->aGroup = array();
                $this->aOrder = array();
                break;
            case 'SELECT DISTINCT':
                $this->aCampos = array();
                $this->aTablas = array();
                $this->aWhere = array();
                $this->aGroup = array();
                $this->aOrder = array();
                break;
            case 'INSERT':
                $this->aTablas = array();
                $this->aCampos = array();
                $this->aValues = array();
                $this->aSelect = array();
                break;
            case 'UPDATE':
                $this->aTablas = array();
                $this->aSet = array();
                $this->aWhere = array();
                break;
            case 'BEGIN':
                $this->aSentencias = array();
                break;
        }
        //Fin switch
    }

    //Fin __construct
    function __destruct()
    {
        unset($sSql);
        unset($aCampos);
        unset($aTablas);
        unset($aWheres);
        unset($aGroups);
        unset($aOrders);
        unset($aValues);
        unset($aSets);
        unset($sTipo);
    }
    //FIN __destruct

    /**
     *             Funcion para una sentencia al BEGIN
     * @param String $sSentencia
     * @access public
     */

    public function nuevo_Sentencia($sSentencia)
    {
        $this->aSentencias[] = $sSentencia;
    }

    /**
     *             Funcion para un select al insert
     * @param String $sSentencia
     * @access public
     */

    public function nuevo_Select($sSentencia)
    {
        $this->aSelect[] = $sSentencia;
    }

    /**
     *             Funcion para aadir campos
     * @param String $sCampo
     * @access public
     */

    public function nuevo_Campo($sCampo)
    {
        $this->aCampos[] = $sCampo;
    }
    //Fin nuevo_Campo

    /**
     *             Funcion para aadir tablas
     * @param String $sTabla
     * @access public
     */

    public function nuevo_Tabla($sTabla)
    {
        $this->aTablas[] = $sTabla;
    }
    //Fin nuevo_Tabla

    /**
     *             Funcion para aadir condiciones where
     * @param String $sWhere
     * @access public
     */

    public function nuevo_Where($sWhere)
    {
        $this->aWheres[] = "(" . $sWhere . ")";
    }
    //Fin nuevo_Where

    /**
     *             Funcion para aadir GROUP
     * @param String $sGroup
     * @access public
     */

    public function nuevo_Group($sGroup)
    {
        $this->aGroups[] = $sGroup;
    }
    //Fin nuevo_Group

    /**
     *             Funcion para aadir ORDER
     * @param String $sGroup
     * @access public
     */

    public function nuevo_Order($sOrder)
    {
        $this->aOrders[] = $sOrder;
    }
    //Fin nuevo_Order

    /**
     *             Funcion para aadir VALUES
     * @param String $sValue
     * @access public
     */

    public function nuevo_Value($sValue)
    {
        if ($sValue != 'null') {
            $this->aValues[] = "'" . addslashes($sValue) . "'";
            //$this->aValues[]="'".pg_escape_string($sValue)."'";
        } else {
            $this->aValues[] = $sValue;
        }
    }

    public function nuevo_ValueSin($sValue)
    {
        $this->aValues[] = addslashes($sValue);
        //$this->aValues[]=pg_escape_string($sValue);
    }

    public function nuevo_ValueSinSlashes($sValue)
    {
        $this->aValues[] = "'" . $sValue . "'";
    }
    //Fin nuevo_Value

    /**
     *             Funcion para añadir SET
     * @param String $sSet
     * @access public
     */

    public function nuevo_Set($sSet, $sValor)
    {
        $this->aSets[] = $sSet . '=\'' . $sValor . '\'';
    }

    //Fin nuevo_Set

    public function nuevo_SetSin($sSet, $sValor)
    {
        $this->aSets[] = $sSet . '=' . $sValor . '';
    }

    public function nuevo_SetSlashes($sSet, $sValor)
    {
        $this->aSets[] = $sSet . '=\'' . addslashes($sValor) . '\'';
    }

    //Fin nuevo_Set

    private function concatenar($sArray)
    {
        if (is_array($this->$sArray)) {
            foreach ($this->$sArray as $iKey => $sValor) {
                if ($iKey == 0) {
                    $this->sSql .= $sValor;
                } else {
                    if ($sArray == "aWheres") {
                        $this->sSql .= " AND " . $sValor;
                    } else {
                        $this->sSql .= ", " . $sValor;
                    }
                }
            }
        }
    }

    /**
     *             Funcion para devolver la cadena SQL correspondiente
     * @return String
     * @access public
     */

    public function to_String()
    {
        switch ($this->sTipo) {
            case 'SELECT':
                if (($this->aCampos[0]) != null) {
                    $this->sSql = "SELECT ";
                    //Ponemos los campos
                    $this->concatenar('aCampos');
                    if ($this->aTablas[0] != null) {
                        $this->sSql .= " FROM ";
                        $this->concatenar('aTablas');
                    }
                    if ($this->aWheres[0] != null) {
                        $this->sSql .= " WHERE ";
                        $this->concatenar('aWheres');
                    }
                    if ($this->aGroups[0] != null) {
                        $this->sSql .= " GROUP BY ";
                        $this->concatenar('aGroups');
                    }
                    if ($this->aOrders[0] != null) {
                        $this->sSql .= " ORDER BY ";
                        $this->concatenar('aOrders');
                    }
                }
                break;
            case 'SELECT DISTINCT':
                {
                    if (($this->aCampos[0]) != null) {
                        $this->sSql = "SELECT DISTINCT ";
                        //Ponemos los campos
                        $this->concatenar('aCampos');
                        if ($this->aTablas[0] != null) {
                            $this->sSql .= " FROM ";
                            $this->concatenar('aTablas');
                        }
                        if ($this->aWheres[0] != null) {
                            $this->sSql .= " WHERE ";
                            $this->concatenar('aWheres');
                        }
                        if ($this->aGroups[0] != null) {
                            $this->sSql .= " GROUP BY ";
                            $this->concatenar('aGroups');
                        }
                        if ($this->aOrders[0] != null) {
                            $this->sSql .= " ORDER BY ";
                            $this->concatenar('aOrders');
                        }
                    }
                    break;
                }
            case 'INSERT':
                if (($this->aTablas[0]) != null) {
                    $this->sSql = "INSERT INTO " . $this->aTablas[0];
                    if ($this->aCampos[0] != null) {
                        $this->sSql .= " ( ";
                        $this->concatenar('aCampos');
                        $this->sSql .= " )";
                    }
                    if ($this->aValues[0] != null) {
                        $this->sSql .= " VALUES ( ";
                        $this->concatenar('aValues');
                        $this->sSql .= " )";
                    }
                    if ($this->aSelect[0] != null) {
                        $this->sSql .= " " . $this->aSelect[0];
                    }
                }
                break;
            case 'UPDATE':
                if (($this->aTablas[0]) != null) {
                    //    $this->sSql= "UPDATE ".$this->aTablas[0];
                    $this->sSql = "UPDATE ";
                    $this->concatenar('aTablas');
                    if ($this->aSets[0] != null) {
                        $this->sSql .= " SET ";
                        $this->concatenar('aSets');
                    }
                    if ($this->aWheres[0] != null) {
                        $this->sSql .= " WHERE ";
                        $this->concatenar('aWheres');
                    }
                }
                break;
            case 'BEGIN':

                if (is_array($this->aSentencias)) {
                    if (($this->aSentencias[0]) != null) {
                        $this->sSql = "BEGIN; ";
                        foreach ($this->aSentencias as $sValue) {
                            $this->sSql .= $sValue . ";";
                        }
                        $this->sSql .= " COMMIT;";
                    }
                }
                break;
        }
        return $this->sSql;
    }
    //Fin to_String
    //  Fin Metodos


}

//Fin Clase

