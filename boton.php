<?php
/**
 * Created on 12-dic-2005
 *
* LICENSE see LICENSE.md file

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 *
 * Estas clase esta hecha para ser parte de los listados y de los arboles para añadir botones y desplegables a sus elementos
 */

class boton
{
    //Atributos
    /**
     *    Este es el contenido del boton
     * @access private
     * @var string
     */

    private $sLabel;

    /**
     *       Esta es la accion javascript que lleva asociada el boton
     * @acces private
     * @var string
     */

    private $sAccion;

    /**
     *    Este es el tipo del boton si es un boton para una sola fila o en general asociado a checkbox
     * @acces private
     * @var string
     */
    private $sTipo;

    //Fin Atributos

    function __construct($sLabel, $sAccion, $sTipo)
    {
        $this->sLabel = $sLabel;
        $this->sAccion = $sAccion;
        $this->sTipo = $sTipo;
    }

    /**
     *     Esta funcion mete mas datos dentro de la peticion del boton
     * @access public
     * @param String
     * @deprecated
     */

    public function meter_Parametros($sDatos)
    {
        $aTmp = explode("1", $this->sAccion);
        $this->sAccion = $aTmp[0] . "1,'" . $sDatos . "')";

    }

    /**
     *     Esta funcion devuelve el html asociado al boton
     * @access public
     * @return String
     */

    public function to_Html()
    {
        require_once('estilo.php');
        $sNavegador = $_SESSION['navegador'];
        $sDisabled='';

        if (!(($this->sTipo == 'noafecta') || ($this->sTipo == 'sincheck'))) {
            $sDisabled = 'DISABLED';

            if ($sNavegador == "Microsoft Internet Explorer") {
                return ("<INPUT class=\"b_inactivo\" onMouseOver=\"this.className='b_focus'\"".
                    " onMouseOut=\"this.className='b_activo'\" TYPE=BUTTON NAME=\"" .
                    $this->sTipo . "\"onClick=\"" . $this->sAccion . "\"  VALUE=\"" . $this->sLabel . "\" " . $sDisabled . ">");
            } else {
                return ("<INPUT class=\"b_inactivo\" TYPE=BUTTON NAME=\"" .
                    $this->sTipo . "\"onClick=\"" . $this->sAccion . "\"  VALUE=\"" . $this->sLabel . "\" " . $sDisabled . ">");
            }

        } else {
            if ($sNavegador == "Microsoft Internet Explorer") {
                return ("<INPUT TYPE=BUTTON class=\"b_activo\" onMouseOver=\"this.className='b_focus'\"".
                    " onMouseOut=\"this.className='b_activo'\"  NAME=\"" . $this->sTipo . "\"onClick=\"" .
                    $this->sAccion . "\"  VALUE=\"" . $this->sLabel . "\" " . $sDisabled . ">");
            } else {
                return ("<INPUT TYPE=BUTTON class=\"b_activo\"  NAME=\"" .
                    $this->sTipo . "\"onClick=\"" . $this->sAccion . "\"  VALUE=\"" . $this->sLabel . "\" " . $sDisabled . ">");
            }

        }
    }
}

class desplegable
{
    //Atributos
    /**
     *    Este es el label del desplegable
     * @access private
     * @var string
     */

    private $sLabel;

    /**
     *       Esta es la accion javascript que lleva asociada el boton
     * @acces private
     * @var string
     */

    private $sAccion;

    /**
     *       Este es el array con las opciones para el desplegable
     * @acces private
     * @var array
     */

    private $aOpciones;

    /**
     *       Este es el nombre del desplegable tiene que ser igual al campo donde haremos el where
     * @acces private
     * @var String
     */

    private $sNombre;
    private $sId;

    //FIN Atributos

    function __construct($sLabel, $sAccion, $aOpciones, $sNombre = null, $sId = null)
    {
        $this->sLabel = $sLabel;
        $this->sAccion = $sAccion;
        $this->aOpciones = $aOpciones;
        $this->sNombre = $sNombre;
        $this->sId = $sId;
    }
    //FIN __construct

    /**
     *     Esta funcion devuelve el html asociado al boton
     * @access public
     * @return String
     */

    public function to_Html()
    {
        if ($this->sAccion != null) {
            $sHtml = $this->sLabel . ": <select name='paginas' onChange=\"" . $this->sAccion . "\">";

            if (is_array($this->aOpciones)) {
                foreach ($this->aOpciones as $sKey => $sValor) {
                    $sHtml .= "<option value=\"" . $sValor . "\">" . $sValor;
                }
            }
        } else {
            $sHtml = $this->sLabel . ": <select id='" . $this->sId . "' name='" . $this->sNombre . "'>";

            if (is_array($this->aOpciones)) {
                foreach ($this->aOpciones as $sKey => $sValor) {
                    $sHtml .= "<option value=\"" . $sKey . "\">" . $sValor;
                }
            }
        }


        $sHtml .= "</select>";
        return $sHtml;
    }

    public function esNPag()
    {
        if ($this->sAccion != null) {
            return (true);
        } else {
            return (false);
        }
    }

}
