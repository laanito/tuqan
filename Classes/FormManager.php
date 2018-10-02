<?php
/**
 *
 * New Form Manager created to rearrange how forms are handled
 */

namespace Tuqan\Classes;

use Former\Facades\Former as Former;

class FormManager
{
    /**
     * @var $action string
     */
    private $action;

    /**
     * @var $data array
     */
    private $data;

    /**
     * @var $fields array
     */
    private $fields;


    /**
     * @var Manejador_Base_Datos
     */
    private $oDb;

    /**
     * FormManager constructor.
     * @param $action string
     * @param $aDatos array
     */
    public function __construct($action, $aDatos = null)
    {
        $this->action = $action;
        $this->data = $aDatos;
        $Form=new Forms();
        $this->fields = $Form->formulario($this->action);
        $this->oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    }

    /**
     * Generate an array with all the database fields needed for form
     *
     *  @return array|bool
     */
    private function getFields()
    {
        if (is_array($this->fields)) {
            $tables = array_keys($this->fields);
        }
        /**
         * Para cada tabla que contenga campos para introducir en el formulario
         */
        try {
            $allTableFields = array();
            foreach ($tables as $table) {
                /**
                 *  Construimos la sentencia para sacar el tipo de dato, su longitud y si puede ser nulo o no
                 */
                $this->oDb->iniciar_Consulta('SELECT');
                $this->oDb->construir_Campos(array("attname AS nombre, " .
                    "pg_type.typname as tipo,CASE WHEN atttypmod=-1 THEN attlen ELSE atttypmod END AS longitud," .
                    " attnotnull AND NOT atthasdef AS nulo"));
                $this->oDb->construir_Tablas(array('pg_attribute', 'pg_class', 'pg_type'));
                $this->oDb->construir_Where(array('pg_attribute.attrelid=pg_class.oid', 'attnum>0', 'pg_class.relname=\''
                    . $table . '\'', 'pg_type.oid=atttypid'));
                $this->oDb->construir_Order(array('attnum ASC'));
                $this->oDb->consulta();

                $tableFieldsArray = array();
                while ($aIterador = $this->oDb->coger_Fila(false, DB_FETCHMODE_ASSOC)) {
                    $tableFieldsArray[$aIterador['nombre']] = array(
                        'tipo' => $aIterador['tipo'],
                        'longitud' => $aIterador['longitud'],
                        'nulo' => $aIterador['nulo'],
                    );
                }
                $allTableFields[$table] = $tableFieldsArray;
            }
            return $allTableFields;
        } catch (\Exception $e) {
            TuqanLogger::debug("Exception on FormManager ->getFields", ['exception' => $e]);
            return false;
        }
    }

    /**
     * @param $name string
     * @param $field array
     * @return string
     */
    private function addField($name,  $field){
        return (string)call_user_func(
            array('\Former\Facades\Former', FormElement::getElement($field['tipo'])),
            $name
        );
    }
    /**
     * @param $fields
     * @return mixed
     */
    private function generate($fields){
        $Formulario = (string)Former::framework('TwitterBootstrap3');
        $Formulario.= Former::horizontal_open();

        foreach($fields as $table => $tablefields) {
            $Formulario.="<fieldset><legend>".$table."</legend>";
            foreach($tablefields as $name => $field){
                $Formulario.=$this->addField($name,$field);
            }
            $Formulario.="</fieldset>";
        }

        $Formulario.= Former::close();
        return $Formulario;
    }
    /**
     * @return string
     */
    public function process(){
        $allTableFields = $this->getFields();
        $result='<div class="container-fluid">';
        $result.= $this->generate($allTableFields);
        $result.='</div>';
        return $result;
    }
}
