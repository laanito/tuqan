<?php
/**
 * Controller for mensajes
 */

namespace Tuqan\Controllers;


use Tuqan\Classes\TuqanLogger;

class mensajes
{
    public function anyIndex()
    {
        $action=$_POST['action'];
        if(isset($_POST['datos'])) {
            $aDatos = $_POST['datos'];
        }
        else {
            $aDatos = array();
        }
        TuqanLogger::debug(
            "Arrived Mensajes Controller",
            ["action" => $action, 'aDatos' =>print_r($aDatos,1)]
        );
        $result = "contenedor|We arrived Mensajes controller";
        return $result;
    }
}
