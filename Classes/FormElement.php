<?php
/**
 *
 * Form Element Mapper
 *
 */

namespace Tuqan\Classes;


class FormElement
{

    static public $map = array(
        'int4' => 'text',
        'varchar' => 'textarea',
        'timestamp' => 'datetime',
        'bool' => 'check',
    );

    static public function getElement($type){
        return self::$map[$type];
    }

}