<?php
/**
 * Class that handles ajax requests
 *
 * @author lamigo
 */

namespace Tuqan\Classes;


class AjaxHandler
{
    public function anyIndex()
    {
        $result='contenedor|This is the default page and will respond to /ajax and /ajax/index\n';
        $result.="action=".$_POST['action'];
        $result.="sesion=".$_POST['sesion'];
        $result.="datos=".$_POST['datos'];
        return $result;
    }

}