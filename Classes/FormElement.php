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
        'int2' => 'text',
        'int4' => 'text',
        'varchar' => 'textarea',
        'timestamp' => 'datetime-local',
        'bool' => 'checkbox',
    );

    static public function getElement($type){
        return self::$map[$type];
    }

}