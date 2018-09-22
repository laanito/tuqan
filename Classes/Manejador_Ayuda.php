<?php
namespace Tuqan\Classes;

Class Manejador_Ayuda
{
    /**
     * @param $sAccion
     * @return array
     */
    function prepara_Ayuda($sAccion)
    {
        return (array('accion' => $sAccion, 'accionanterior' => $_SESSION['accionanterior'],
            'accionactual' => $_SESSION['accionactual'], 'accioniframe' => $_SESSION['accioniframe']));
    }
}


