<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *

 */

Class Manejador_Editor
{
    /**
     * @param $sAccion
     * @return array
     */
    function prepara_Editor($sAccion)
    {
        return (array('accion' => $sAccion));
    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_Editor_Editar($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'idDocumento' => $_SESSION['pagina'][$aDatos[0]]));
    }
}
