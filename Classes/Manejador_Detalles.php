<?php
namespace Tuqan\Classes;



class Manejador_Detalles {
    /**
     * Esta funcion prepara para ver detalles del documento
     * @param array $aDatos
     * @return array
     */

    function prepara_Detalles_Documento($aDatos) {
        return (array('accion' => 'detalles:documento', 'documento' => $aDatos[0]));
    }

        /**
         * @param $sCodigo
         * @param $aDatos
         * @return array
         */

    function prepara_Detalles_DocumentoId($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'tarea' => $aDatos[0]));
    }


    /**
     * Funcion para preparar los detalles de algun elemento de un listado
     *
     * @param array $aDatos
     * @param string $sAccion
     * @return array
     */

    function prepara_Detalles($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $aDatos[1]));
    }
}