<?php
namespace Tuqan\Classes;
/**
 * Created on 11-nov-2005
 *
* LICENSE see LICENSE.md file
 *

 * @version 0.1.0a
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * Esta clase es la que se encarga de enviarle las respuestas del Procesador de peticiones
 * de vuelta a nuestro manejador Ajax
 */

class Manejador_De_Respuestas
{

    /**
     *    Esta es la cadena a devolver a Ajax
     * @access private
     * @var string
     */

    private $sHtml;

    /**
     *    Constructor
     *
     * @access public
     * @param string $sCadena
     */

    function __construct($sCadena)
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_De_Respuestas.php", "Constructor", null);
        }
        $this->sHtml = $sCadena;
    }
    //Fin __construct

    /**
     *       Esta funcion nos devuelve el codigo al manejador ajax con un echo para que lo coja el handleResponse
     *
     * @access public
     *
     */

    public function toAjax()
    {
        global $iDebug;
        if ($iDebug == 1) {
            $_SESSION['oDebugger']->agregar_Paso("Manejador_De_Respuestas.php", "toAjax", null);
        }

        echo $this->sHtml;
    }
    //Fin toAjax
}


