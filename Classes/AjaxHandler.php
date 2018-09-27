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
        $aDatos= $_POST['datos'];
        $oPeticion = new Manejador_De_Peticiones($action, $aDatos);
        $aParametros = $oPeticion->devuelve_Parametros();
        $oProcesador = new Procesador_De_Peticiones($aParametros);
        $result=$oProcesador->devolver();
        return $result;
    }

}