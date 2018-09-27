<?php
/**
 * Class that handles ajax requests
 *
 * @author lamigo
 */

namespace Tuqan\Classes;


class AjaxHandler
{
    public function anyIndex($action, $sesion, $datos)
    {
        $result='contenedor|This is the default page and will respond to /ajax and /ajax/index\n';
        $result.="action=$action";
        $result.="sesion=$sesion";
        $result.="datos=$datos";
        return $result;
    }

}