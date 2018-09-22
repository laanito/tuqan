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
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 * @TODO elegir numero de elementos por pagina
 */

use \HTML_Table;
use \boton;
use \desplegable;
use \Pager;

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



    function __construct($sAccion, &$oDb, $sPagina, $iNumeroLinks = 3, $iElementosPorPagina, $sOrder, $sSentidoOrder, $sTabla, $aWhere = "limpiar", $aBusca = null, $sTexto = null)
    {
        $this->sAccion = $sAccion;
        $this->sPagina = $sPagina;
        $this->iNumeroLinks = $iNumeroLinks;
        $this->oDb =& $oDb;
        $this->aOpcionesPager = array('mode' => 'Jumping',
            'delta' => $iNumeroLinks,
            'perPage' => $iElementosPorPagina,
        );

        $this->sOrder = $sOrder;
        $this->sSentidoOrder = $sSentidoOrder;
        $this->aEventos = array();
        $this->aBotones = array();
        $this->sTabla = $sTabla;
        $this->aBuscador = $aBusca;
        $this->sTexto = $sTexto;

        unset($_SESSION['where']);
        if ($aWhere != "limpiar") {
            $_SESSION['where'] = $aWhere;
        }
        unset($_SESSION['tabla']);
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
        $dsn = $this->oDb->dsn();
        $oMiDb = $this->oDb->connect($dsn);
        $paged_data = $this->Pager_Wrapper_DB($oMiDb);
        if (is_object($paged_data)) {
            //Esto sacaria un error
            echo "contenedor|";
            print_r($paged_data);
        }
        if ($paged_data['totalItems'] != 0) {

            $sHtml = "<br /><div id=\"tablas\" align=\"center\">";

            //Tabla de listados de los registros
            $aTableAttrs = array('BORDER' => '0', 'class' => 'tablag');
            $oTable = new HTML_Table($aTableAttrs);


            //Si hay algun boton que afecte a mas de una fila ponemos las cabeceras de las checkbox
            if (count($this->aBotones) > 0) {
                $aContenido = array('<INPUT TYPE=CHECKBOX id=\'checkarriba\' onclick=\'marcardesmarcar()\'>');
                $oTable->addRow($aContenido, null, 'TH');
            } else if (count($this->aBotonesFila) > 0) {
                $aContenido = array('');
                $oTable->addRow($aContenido, null, 'TH');

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
                            if ($this->sSentidoOrder != "DESC")  //cambiar img
                            {
                                //$aAtributos = "onclick=\"sndReq('".$this->sModulo.":listado:general:listado','',1,'".
                                //                $this->sAccion.separador.$i.separador.$value." DESC"."')\"";
                                $aAtributos = "onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" .
                                    $this->sAccion . separador . $iterador . separador . $value . " DESC" . "')\"";
                                $aContenido = array('>' .
                                    '<b onmouseover=\'this.className=\'over\' onmouseout=\'this.className=\'out\'\'>' . $value .
                                    '</b>');
                                $oTable->setCellAttributes(0, $key, $aAtributos);
                                $oTable->setCellContents(0, $key, $aContenido, 'TH');

                            } else {
                                //$aAtributos = "onclick=\"sndReq('".$this->sModulo.":listado:general:listado','',1,'".
                                //               $this->sAccion.separador.$i.separador.$value." ASC"."')\"";
                                $aAtributos = "onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" .
                                    $this->sAccion . separador . $iterador . separador . $value . " ASC" . "')\"";
                                $aContenido = array('>' .
                                    '<b onmouseover=\'this.className=\'over\' onmouseout=\'this.className=\'out\'\'>' . $value .
                                    '</b>');
                                $oTable->setCellAttributes(0, $key, $aAtributos);
                                $oTable->setCellContents(0, $key, $aContenido, 'TH');
                            }
                        } else {

                            //$aAtributos = "onclick=\"sndReq('".$this->sModulo.":listado:general:listado','',1,'".
                            //            $this->sAccion.separador.$i.separador.$value." ASC"."')\"";
                            $aAtributos = "onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador .
                                $iterador . separador . $value . " ASC" . "')\"";
                            $aContenido = '<b onmouseover=\'this.className=\'over\' onmouseout=\'this.className=\'out\'\'>' . $value .
                                '</b>';
                            $oTable->setCellAttributes(0, $key, $aAtributos);
                            $oTable->setCellContents(0, $key, $aContenido, 'TH');
                        }

                    }
                    $iterador++;
                }//end foreach
            }//end if($paged_data['totalItems']!=0)


            for ($i = 0; $i < count($paged_data['data']); $i++) {
                //Cada fila de los registros
                $aContenido = (array('&nbsp;'));
                $cont = $oTable->addRow($aContenido, null, 'TR');

                //Colores de la tabla
                $altRow = array('class' => 'filaimpar');
                $oTable->altRowAttributes(0, null, $altRow, 'TR');

                $altRow = array('class' => 'filapar');
                $oTable->altRowAttributes(1, null, $altRow, 'TR');


                //Añadimos las checkbox si hay 1 o mas botones que afectan a alguna fila
                if ((count($this->aBotones) > 0) || (count($this->aBotonesFila) > 0)) {

                    //Añadimos aqui un filtro especial para los mensajes, para que las checkbox de los mensajes generales salgan inhabilitadas
                    if ($this->sAccion) {

                        $aContenido = array('<div align=\"center\"><INPUT TYPE=CHECKBOX NAME=\'' . $i .
                            '\' onclick=\'comprobar_Botones()\' VALUE=aplicable></div>');
                        $oTable->setCellContents($cont, 0, $aContenido, 'TD');
                    }

                }

                $iColumna = 0;
                foreach ($paged_data['data'][$i] as $key => $value) {

                    if ($key == 'id') {
                        $_SESSION['pagina'][] = $value;
                    } else if ($key == 'destinatario') {
                        //Si es destinatario lo ignoramos dado que este dato solo lo usamos para el filtro de checkbox de arriba
                    } else {
                        if (strlen($value) < 15) {
                            $aContenido = array('<nobr><b>' . stripslashes($value) . '</b></nobr>');
                            $oTable->setCellContents($i + 1, $iColumna, $aContenido, 'TD');

                        } else {
                            $aContenido = array('<b>' . stripslashes($value) . '</b>');
                            $oTable->setCellContents($i + 1, $iColumna, $aContenido, 'TD');

                        }

                    }
                    $iColumna++;
                }
            }

            //Fin Tabla de registros
            $sTabla = $oTable->toHtml();
            $sHtml .= $sTabla . "</center>";

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

            $aTableAttrs = array('class' => 'subtabla');
            $oSubTabla = new HTML_Table($aTableAttrs);

            $sHtml .= "<br /><br />";

            $aContenido = (array('&nbsp;'));
            $oSubTabla->addRow($aContenido, null, 'TR');

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
                            $aContenido = (array($oDesplegable->to_Html()));
                            $oSubTabla->addCol($aContenido, null, 'TD');
                        }
                    }
                }
                //$this->agrega_Boton(gettext('sBotonBusqueda'),"sndReq('".$this->sModulo.":listado:general:listado','',1,'".$this->sAccion.separador."1".separador.$this->sOrder
                //                ." ".$this->sSentidoOrder."')","noafecta");
                $this->agrega_Boton(gettext('sBotonBusqueda'), "sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . "1" . separador . $this->sOrder
                    . " " . $this->sSentidoOrder . "')", "noafecta");
                $oBoton = end($this->aBotonesNoAfectan);


                $sHtml .= $oSubTabla->toHtml();
                $sHtml .= $oBoton->to_Html() . "<br />";
            } else {
                $sHtml .= $oSubTabla->toHtml();
            }
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
            $sHtml .= "<br /><br />";

            $aTableAttrs = array('class' => 'subtabla');
            $oSubTabla = new HTML_Table($aTableAttrs);

            $aContenido = (array('&nbsp;'));
            $oSubTabla->addRow($aContenido, null, 'TR');

            if (is_array($this->aBuscador)) {
                /*                 foreach ($this->aBuscador as $sValor)
                                 {
                                     $sDefecto="";
                                     if ($_SESSION['where']!=null)
                                     {
                                         if (array_key_exists($sValor,$_SESSION['where']))
                                         {
                                             $sDefecto=$_SESSION['where'][$sValor];
                                         }
                                     }

                                     $aContenido = (array($sValor));
                                     $oSubTabla->addCol($aContenido,null,'TD');

                                     $aContenido = (array("<INPUT TYPE=TEXT NAME=\"".$sValor."\" VALUE=\"".$sDefecto."\">"));
                                     $oSubTabla->addCol($aContenido,null,'TD');
                                 }*/
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
                            $aContenido = (array($oDesplegable->to_Html()));
                            $oSubTabla->addCol($aContenido, null, 'TD');
                        }
                    }
                }
                $this->agrega_Boton(gettext('sBotonBusqueda'), "sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . "1" . separador . $this->sOrder
                    . " " . $this->sSentidoOrder . "')", "noafecta");
                $oBoton = end($this->aBotonesNoAfectan);

                $sHtml .= $oSubTabla->toHtml();

                $sHtml .= $oBoton->to_Html() . "<br /><br />";
            } else {
                $sHtml .= $oSubTabla->toHtml();
            }

            $sHtml .= "</div>";
        }
        //    echo "<TEXTAREA name='thetext' rows='80' cols='120'>".$sHtml."</TEXTAREA>";die();
        return $sHtml;
    }
    //Fin muestra_Pagina

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
     * @param string $sOnclick la accion asociada al boton
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


// Pager_Wrapper 
// ------------- 
// 
// Ready-to-use wrappers for paging the result of a query, 
// when fetching the whole resultset is NOT an option. 
// This is a performance- and memory-savvy method 
// to use PEAR::Pager with a database. 
// With this approach, the network load can be 
// consistently smaller than with PEAR::DB_Pager. 
// 
// The following wrappers are provided: one for each PEAR 
// db abstraction layer (DB, MDB and MDB2), one for 
// PEAR::DB_DataObject, and one for the PHP Eclipse library 
// 
// 
// SAMPLE USAGE 
// ------------ 
// 
// $query = 'SELECT this, that FROM mytable'; 
// require_once 'Pager_Wrapper.php'; 
//this file 
// $pagerOptions = array( 
//     'mode'    => 'Sliding', 
//     'delta'   => 2, 
//     'perPage' => 15, 
// ); 
// $paged_data = Pager_Wrapper_MDB2($db, $query, $pagerOptions); 
// 
//$paged_data['data'];  
//paged data 
// 
//$paged_data['links']; 
//xhtml links for page navigation 
// 
//$paged_data['page_numbers']; 
//array('current', 'total'); 
// 

    /**
     * Helper method - Rewrite the query into a "SELECT COUNT(*)" query.
     * @param string $sql query
     * @return string rewritten query OR false if the query can't be rewritten
     * @access private
     */

    private function rewriteCountQuery($sql)
    {
        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) || preg_match('/\s+GROUP\s+BY\s+/is', $sql)) {
            return false;
        }
        $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', "SELECT COUNT(*) FROM ", $sql, 1);
        list($queryCount,) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        list($queryCount,) = preg_split('/\bLIMIT\b/is', $queryCount);
        return trim($queryCount);
    }
    //Fin rewriteCountQuery

    /**
     * Esta funcion nos pone los links necesarios entre el pagina anterior y el pagina siguiente, es el metodo
     * del PAGER modificado para que las peticiones vayan por nuestro manejador AJAX
     * @TODO debe recibir el action que envia el AJAX
     * @param object PEAR:PAGER
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
                $links .= "<b onMouseOver=\"this.className='encima'\" onmouseout=\"this.className='fuera'\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . $i . separador . $this->sOrder .
                    " " . $this->sSentidoOrder . "')\">" . $i . "&nbsp</b>";
                $cadena = "sndReq('general:busqueda:comun:nuevo:listado','',1,'" . $this->sAccion . separador . $i . separador . $this->sOrder .
                    " " . $this->sSentidoOrder . "')";
            } else {
                $pager->range[$i] = true;
                $links .= "<b onMouseOver=\"this.className='encima'\" onmouseout=\"this.className='fuera'\" onclick=\"sndReq('general:busqueda:comun:nuevo:listado',' ',1,'" . $this->sAccion . separador . $i . separador . $this->sOrder .
                    " " . $this->sSentidoOrder . "')\">[" . $i . "]&nbsp</b>";
            }
            $links .= $pager->_spacesBefore
                . (($i != $pager->_totalPages) ? $pager->_separator . $pager->_spacesAfter : '');
        }
        return $links;
    }
    //Fin pon_Links

    /**
     * @param object PEAR::DB instance
     * @param string db query
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

    private function Pager_Wrapper_DB(&$db, $disabled = false, $fetchMode = DB_FETCHMODE_ASSOC, $dbparams = null)
    {
        $query = $this->oDb->to_String_Consulta();
        if (!array_key_exists('totalItems', $this->aOpcionesPager)) {
            //  be smart and try to guess the total number of records
            /*    if ($countQuery = $this->rewriteCountQuery($query))
                    {
                        $totalItems = $db->getOne($countQuery, $dbparams);
                        if (\PEAR::isError($totalItems))
                        {
                            return $totalItems;
                        }
                    }
                else
                {  */
            $res =& $db->query($query, $dbparams);
            if (\PEAR::isError($res)) {
                return $res;
            }
            $totalItems = (int)$res->numRows();
            $res->free();
            //}
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
         *     Hacemos la consulta limitada para obtener un numero prefijado de datos
         */

        //Arreglo del fallo cuando no tenemos select
        $iPaginas = 20;
        if ($this->aOpcionesPager['perPage'] != null) {
            $iPaginas = $this->aOpcionesPager['perPage'];
        }
        $res = ($disabled) ?
            $db->limitQuery($query, 0, $totalItems, $dbparams) :
            $db->limitQuery($query, $page['from'] - 1, $iPaginas, $dbparams);
        $pera = new \PEAR();
        if ($pera->isError($res)) {
            return $res;
        }

        /**
         *     Añadimos los datos al listado
         */
        $row=array();
        $page['data'] = array();
        while ($res->fetchInto($row, $fetchMode)) {
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
