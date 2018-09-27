<?php
/**
 * Class that handles ajax requests
 *
 * @author lamigo
 */

namespace Tuqan\Classes;


class AjaxHandler
{
    /**
     * @return string
     */
    public function anyIndex()
    {
        $action=$_POST['action'];
        if(isset($_POST['datos'])) {
            $aDatos = $_POST['datos'];
        }
        else {
            $aDatos = array();
        }
        $oPeticion = new Manejador_De_Peticiones($action, $aDatos);
        $aParametros = $oPeticion->devuelve_Parametros();
        TuqanLogger::debug('Parameters: ',['aparametros' => $aParametros]);
        $oProcesador = new Procesador_De_Peticiones($aParametros);
        $oProcesador->procesar();
        $result=$oProcesador->devolver();
        return $result;
    }

}