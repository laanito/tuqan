<?php
/**
 *
 * New Form Manager created to rearrange how forms are handled
 */

namespace Tuqan\Classes;


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
     * FormManager constructor.
     * @param $action string
     * @param $aDatos array
     */
    public function __construct($action, $aDatos = null)
    {
        $this->action = $action;
        $this->data = $aDatos;
    }

    /**
     * @return string
     */
    public function process(){
        $Form=new Forms();
        $sHtml = $Form->formulario($this->action);
        return 'contenedor|'.print_r($sHtml,1);
    }

}