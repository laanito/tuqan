<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * NOTA: las peticiones para los listados pueden llevar multiples datos asociados, la estructura es:
 * 1) Pagina del listado pedida
 * 2) Order pedido
 * 3) Campo where adicional
 * Esta clase es la encargada de realizar los listados que se mostraran en
 * pantalla
 *
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

use \boton;
use \desplegable;
use \Pager;
use Surface\Surface;

class generador_listados
{

    // Attributos
    /**
     *     Aqui guardamos la accion que identifica el listado
     * @access private
     * @var Object
     */

    private $sAccion;

    /**
     *      Esta es el numero de la pagina a mostrar
     * @access private
     * @var string
     */

    private $sPagina;

    /**
     *      Aqui tenemos el numero de links a mostrar bajo el listado para navegar por las distintas paginas
     * @access private
     * @var integer
     */

    private $iNumeroLinks;

    /**
     *      Este es el objeto DB que usara el Wrapper
     * @access private
     * @var object
     */

    private $oDb;

    /**
     *      Las opciones que le pasaremos al objeto Pager
     * @access private
     * @var array
     */

    private $aOpcionesPager;

    /**
     *     La columna por la cual ordenar
     * @access private
     * @var String
     */

    private $sOrder;

    /**
     *     Nos dice si ordenar en sentido ascendente (ASC) o descendente (DESC)
     * @access private
     * @var String
     */

    private $sSentidoOrder;

    /**
     *     En este array guardamos los botones a poner que afectan a varias filas
     * @access private
     */

    private $aBotones;

    /**
     *     En este array guardamos los botones a poner que no tienen nada que ver con las check
     * @access private
     */

    private $aBotonesNoAfectan;

    /**
     *     En este array guardamos los botones que solo funcionan con 1 check marcado
     * @access private
     */

    private $aBotonesFila;

    /**
     *     En este array guardamos los botones que se deshabilitan con algun check marcado
     * @access private
     */

    private $aBotonesNoCheck;

    /**
     *     En este array guardamos los campos a poner en el buscador
     * @access private
     */

    private $aBuscador;

    /**
     *     Aqui guardamos la lista desplegable del numero de elementos
     * @access private
     * @var Object
     */

    private $aDesplegable;

    /**
     *     Aqui guardamos Si tenemos que mostrar algun texto bajo el listado
     * @access private
     * @var Object
     */

    private $sTexto;

    private $aEventos;

    private $sTabla;

    /**
     * generador_listados constructor.
     * @param $sAccion
     * @param $oDb
     * @param $sPagina
     * @param int $iNumeroLinks
     * @param int $iElementosPorPagina
     * @param string $sOrder
     * @param string $sSentidoOrder
     * @param string $sTabla
     * @param string $aWhere
     * @param array $aBusca
     * @param string $sTexto
     */

    function __construct(
        $sAccion,
        &$oDb,
        $sPagina,
        $iNumeroLinks = 3,
        $iElementosPorPagina=10,
        $sOrder=null,
        $sSentidoOrder=null,
        $sTabla=null,
        $aWhere = "limpiar",
        $aBusca = null,
        $sTexto = null
    )
    {
        $this->sAccion = $sAccion;
        $this->sPagina = $sPagina;
        $this->iNumeroLinks = $iNumeroLinks;
        $this->oDb =& $oDb;
        $this->aOpcionesPager = array('mode' => 'Jumping',
            'delta' => $iNumeroLinks,
            'perPage' => $iElementosPorPagina,
        );
        if(!is_null($sOrder) && !is_null($sSentidoOrder)){
            $this->sOrder = $sOrder;
            $this->sSentidoOrder = $sSentidoOrder;
        }
        $this->aEventos = array();
        $this->aBotones = array();
        $this->sTabla = $sTabla;
        $this->aBuscador = $aBusca;
        $this->sTexto = $sTexto;
        $_SESSION['where'] = $aWhere;
        $_SESSION['tabla'] = $sTabla;
    }
    //Fin __construct

    /**
     *     Esta es la funcion que nos devuelve una cadena HTML con el listado ya metido en una tabla con todos
     *     los elementos necesarios.
     * @access public
     * @return string
     */
    public function muestra_Pagina()
    {
        unset($_SESSION['pagina']);
        $_SESSION['pagina'] = array();
        $paged_data = $this->Pager_Wrapper_DB();
        if (is_object($paged_data)) {
            TuqanLogger::debug("Error en la llamada:",$paged_data);
        }
        if ($paged_data['totalItems'] != 0) {

            $sHtml = "<div id=\"tablas\" align=\"center\">";

            //Tabla de listados de los registros
            $aTableAttrs = array('class' => 'table table-responsive');
            $oTable = new Surface($aTableAttrs);
            $headerContent =array();

            //Si hay algun boton que afecte a mas de una fila ponemos las cabeceras de las checkbox
            if (count($this->aBotones) > 0) {
                $aContenido = "<input type=checkbox id='checkarriba' onclick='marcardesmarcar()'>";
                $headerContent[]=$aContenido;
            } else if (count($this->aBotonesFila) > 0) {
                $aContenido = '';
                $headerContent[]=$aContenido;

            }
            if (is_array($paged_data['data'][0])) {
                $iterador = 0;
                foreach (array_keys($paged_data['data'][0]) as $key => $value) {

                    /**
                     *     Aqui comprobamos si es la columna por la que vamos a ordenar, para poner el icono de la flecha y para
                     *    poder enviar la peticion por el order correspondiente dependiendo del que ya estuviera (ASC o DESC)
                     */
                    if (($value != 'id') && ($value != 'destinatario')) {
                        if ($value == $this->sOrder) {
                            if ($this->sSentidoOrder != "DESC") {
                                $newOrder = "ASC";
                                $glyphIcon = '<span class="glyphicon glyphicon-arrow-down"></span>';
                            } else {
                                $newOrder = "DESC";
                                $glyphIcon = '<span class="glyphicon glyphicon-arrow-up"></span>';
                            }
                        } else {
                            $newOrder = "";
                            $glyphIcon = '';
                        }

                        $aAtributos = "onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" .
                            $this->sAccion . separador . $iterador . separador . $value . " ".$newOrder . "')\"";
                        $aContenido = '<span '.$aAtributos.'>'.$value.'</span>'.$glyphIcon;
                        $headerContent[]=$aContenido;
                        $iterador++;
                    }
                }
            }
            $rows=array();
            for ($i = 0; $i < count($paged_data['data']); $i++) {
                //Cada fila de los registros
                $rowContent = array();
                //Añadimos las checkbox si hay 1 o mas botones que afectan a alguna fila
                if ((count($this->aBotones) > 0) || (count($this->aBotonesFila) > 0)) {

                    //Añadimos aqui un filtro especial para los mensajes, para que las checkbox de los mensajes generales salgan inhabilitadas
                    if ($this->sAccion) {
                        $aContenido = '<INPUT TYPE=CHECKBOX NAME=\'' . $i .
                            '\' onclick=\'comprobar_Botones()\' VALUE=aplicable>';
                        $rowContent[] =$aContenido;
                    }
                }
                foreach ($paged_data['data'][$i] as $key => $value) {
                    if ($key == 'id') {
                        $_SESSION['pagina'][] = $value;
                    } else if ($key !== 'destinatario') {
                        $aContenido = '<b>' . stripslashes($value) . '</b>';
                        $rowContent[] = $aContenido;
                    }
                }
                $rows[]=$rowContent;
            }

            //Fin Tabla de registros
            $sTabla = $oTable->setHead($headerContent)
                ->addRows($rows)
                ->render();
            $sHtml .= $sTabla . "</div>";

            //Aqui metemos texto por si alguna opcion lo necesita
            if ($this->sTexto != null) {
                $sHtml .= "<br />" . $this->sTexto . "<br />";
            }

            $sHtml = $sHtml .
                "<div class=\"busqueda\">";
            $sHtml = $sHtml . $paged_data['links'];
            if (is_array($this->aDesplegable)) {
                $oDesplElem = end($this->aDesplegable);
                $sHtml .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $oDesplElem->to_Html();
            }
            $sHtml .= "<br /><br />";

            //Primero ponemos los botones que no afectan

            if (is_array($this->aBotonesNoAfectan)) {
                foreach ($this->aBotonesNoAfectan as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }

            //Luego ponemos los botones que con un check marcado se deshabilitan

            if (is_array($this->aBotonesNoCheck)) {
                foreach ($this->aBotonesNoCheck as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }

            //Luego ponemos los botones que afectan a muchas filas

            if (is_array($this->aBotones)) {
                foreach ($this->aBotones as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }

            //Por ultimo ponemos los botones de filas

            if (is_array($this->aBotonesFila)) {
                foreach ($this->aBotonesFila as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }
            $sHtml .=$this->buscadores();
            $sHtml .= "</div>";
        } else {
            $sHtml = "<div align='center'>" . gettext('sNoFila') . "<br />";
            if (is_array($this->aBotonesNoAfectan)) {
                foreach ($this->aBotonesNoAfectan as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }


            if (is_array($this->aBotonesNoCheck)) {
                foreach ($this->aBotonesNoCheck as $oBoton) {
                    $sHtml .= $oBoton->to_Html();
                }
            }
            $sHtml .=$this->buscadores();
            $sHtml .= "</div>";
        }
        return $sHtml;
    }
    //Fin muestra_Pagina


    private function buscadores() {
        $sHtml ='';
        $sHtml .= "<br /><br />";

        $aTableAttrs = array('class' => 'subtabla');
        $oSubTabla = new Surface($aTableAttrs);

        $rowContent = array();

        if (is_array($this->aBuscador)) {
            for ($iContador = 0; $iContador < count($this->aBuscador['nombres']); $iContador++) {
                $sDefecto = $_SESSION['where'][$this->aBuscador['campos'][$iContador]];
                $sHtml .= "<TD>"
                    . $this->aBuscador['nombres'][$iContador] . ":" .
                    "</TD>" .
                    "<TD>" .
                    "<INPUT TYPE=TEXT NAME=\"" . $this->aBuscador['campos'][$iContador] .
                    "\" VALUE=\"" . $sDefecto . "\">" .
                    "</TD>";
            }
            if (is_array($this->aDesplegable)) {
                foreach ($this->aDesplegable as $oDesplegable) {
                    if (!$oDesplegable->esNPag()) {
                        $aContenido = ($oDesplegable->to_Html());
                        $rowContent[] = $aContenido;
                    }
                }
            }
            $this->agrega_Boton(gettext('sBotonBusqueda'), "sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . "1" . separador . $this->sOrder
                . " " . $this->sSentidoOrder . "')", "noafecta");
            $oBoton = end($this->aBotonesNoAfectan);
            $oSubTabla->addRow($rowContent);
            $sHtml .= $oSubTabla->render();

            $sHtml .= $oBoton->to_Html() . "<br /><br />";
        } else {
            $sHtml .= $oSubTabla->render();
        }
        return $sHtml;
    }

    /**
     *     Este metodo añade eventos a realizar cuando pulsamos en un elemento de los listados, los eventos son
     *     Javascript.
     * @param string $sTipo es el tipo de evento pej 'onclick', 'onmouseover', etc
     * @param string $sAccion es la accion que dispara dicho evento
     * @access public
     */
    public function agrega_Evento($sTipo, $sAccion)
    {
        $this->aEventos[$sTipo] = $sAccion;
    }
    //Fin agrega_Evento

    /**
     *     Añadimos un boton al listado
     * @param string $sBoton el nombre y label del boton
     * @param string $sOnclick la accion asociada al boton
     * @param string $sTipoBoton es el tipo del boton (general/fila)
     * @access public
     */

    public function agrega_Boton($sBoton, $sOnclick, $sTipoBoton)
    {
        $oNuevoBoton = new boton($sBoton, $sOnclick, $sTipoBoton);
        switch ($sTipoBoton) {
            case "general":
                $this->aBotones[] = $oNuevoBoton;
                break;
            case "fila":
                $this->aBotonesFila[] = $oNuevoBoton;
                break;
            case "noafecta":
                $this->aBotonesNoAfectan[] = $oNuevoBoton;
                break;
            case "sincheck":
                $this->aBotonesNoCheck[] = $oNuevoBoton;
                break;
        }

    }
    //Fin agrega_Boton


    /**
     *     Añadimos un desplegable al listado
     * @param string $sBoton el nombre y label del boton
     * @param string $sAccion la accion asociada al boton
     * @param array $aOpciones el array con las opciones
     * @param string $sNombre el nombre del campo, para las de elementos por listado null
     * @access public
     */

    public function agrega_Desplegable($sBoton, $sAccion, $aOpciones, $sNombre = null)
    {
        $sReq = null;
        if ($sAccion != null) {
            $sReq = "sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . "1" . separador . $this->sOrder
                . " " . $this->sSentidoOrder . "')";
        }
        $this->aDesplegable[] = new desplegable($sBoton, $sReq, $aOpciones, $sNombre);

    }
    //Fin agrega_Desplegable


    /**
     * Esta funcion nos pone los links necesarios entre el pagina anterior y el pagina siguiente, es el metodo
     * del PAGER modificado para que las peticiones vayan por nuestro manejador AJAX
     * @param object PEAR::PAGER
     * @return string con los links
     * @access private
     */

    private function pon_Links(&$pager)
    {
        $links = '';
        $limits = $pager->getPageRangeByPageId($pager->_currentPage);
        for ($i = $limits[0]; $i <= min($limits[1], $pager->_totalPages); $i++) {
            if ($i != $pager->_currentPage) {
                $pager->range[$i] = false;
                $pager->_linkData[$pager->_urlVar] = $i;
                $links .= "<b onMouseOver=\"this.className='encima'\" onmouseout=\"this.className='fuera'\"".
                    " onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" .
                    $this->sAccion . separador . $i . separador . $this->sOrder .
                    " " . $this->sSentidoOrder . "')\">" . $i . "&nbsp</b>";
            } else {
                $pager->range[$i] = true;
                $links .= "<b onMouseOver=\"this.className='encima'\"".
                    " onmouseout=\"this.className='fuera'\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" .
                    $this->sAccion . separador . $i . separador . $this->sOrder .
                    " " . $this->sSentidoOrder . "')\">[" . $i . "]&nbsp</b>";
            }
            $links .= $pager->_spacesBefore
                . (($i != $pager->_totalPages) ? $pager->_separator . $pager->_spacesAfter : '');
        }
        return $links;
    }
    //Fin pon_Links

    /**
     * @param object db query
     * @param array  PEAR::Pager options
     * @param boolean Disable pagination (get all results)
     * @param integer fetch mode constant
     * @param mixed  parameters for query placeholders
     * If you use placeholders for table names or column names, please
     * count the # of items returned by the query and pass it as an option:
     * $pager_options['totalItems'] = count_records('some query');
     * @return array with links and paged data
     * @access private
     */
    private function Pager_Wrapper_DB($disabled = false, $fetchMode = \PDO::FETCH_ASSOC, $dbparams = null)
    {
        $query = $this->oDb->to_String_Consulta();
        TuqanLogger::debug("Query in wrapper: ",['query' => $query]);
        if (!array_key_exists('totalItems', $this->aOpcionesPager)) {
            $this->oDb->consulta($query);
            $totalItems = (int)$this->oDb->rowCount();
            $this->aOpcionesPager['totalItems'] = $totalItems;
        }
        $pager = Pager::factory($this->aOpcionesPager);

        /**
         *     El siguiente bloque if se encarga de poner los links correspondientes dependiendo de si es primera pagina,
         *     ultima pagina o una pagina que este en medio.
         */

        if (($this->sPagina < $pager->numPages()) && ($this->sPagina > 1)) {
            $pager->_currentPage = $this->sPagina;
            $sLinks = "<b class=\"encima\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" . $this->sAccion . separador . ($pager->_currentPage - 1) .
                separador . $this->sOrder . " " . $this->sSentidoOrder . "')\"> << &nbsp;</b>" .
                $this->pon_Links($pager) .
                "<b class=\"encima\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" . $this->sAccion . separador . ($pager->_currentPage + 1) .
                separador . $this->sOrder . " " . $this->sSentidoOrder . "')\">&nbsp; >></b>";
        } else if ($this->sPagina <= 1) {
            $pager->_currentPage = $this->sPagina;

            if ($pager->numPages() > 1) {
                $sLinks = $this->pon_Links($pager) . "<b class=\"encima\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" . $this->sAccion . separador;
                $sLinks .= ($pager->_currentPage + 1) . separador . $this->sOrder . " " . $this->sSentidoOrder . "')\">>></b>";
            } else {
                $sLinks = $this->pon_Links($pager);
            }
        } else {
            $pager->_currentPage = $this->sPagina;
            $sLinks = "<b class=\"encima\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" . $this->sAccion . separador . ($pager->_currentPage - 1) . separador .
                $this->sOrder . " " . $this->sSentidoOrder . "')\"><< &nbsp;</b>" .
                $sLinks = $this->pon_Links($pager);
        }

        $page = array();
        $page['totalItems'] = $this->aOpcionesPager['totalItems'];
        $page['links'] = $pager->links;
        $page['page_numbers'] = array(
            'current' => $pager->getCurrentPageID(),
            'total' => $pager->numPages()
        );
        list($page['from'], $page['to']) = $pager->getOffsetByPageId();


        /**
         *     Añadimos los datos al listado
         */
        $row=array();
        $page['data'] = array();
        while ($row=$this->oDb->coger_Fila(true, $fetchMode)) {
            $page['data'][] = $row;
        }
        if ($disabled) {
            $page['links'] = '';
            $page['page_numbers'] = array(
                'current' => 1,
                'total' => 1
            );
        }

        //Sobreescribimos los links que tuviera con los que obtuvimos anteriormente
        $page['links'] = $sLinks;
        return $page;
    }
    //Fin Page_Wrapper_DB
}
