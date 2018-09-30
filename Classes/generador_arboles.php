<?php
namespace Tuqan\Classes;
/**
* LICENSE see LICENSE.md file
 *
 * Generador de arboles dinamicos
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 */
use \HTML_TreeMenu;
use \HTML_TreeNode;
use \HTML_TreeMenu_DHTML;


/**
 * class Generador_Arboles
 */
class Generador_Arboles extends HTML_TreeMenu
{

    /** Aggregations: */

    /** Compositions: */

    /*** Attributes: ***/

    /**
     * @access private
     */
    private $oArbol;
    /**
     * @access private
     */
    private $nodo_Opciones;
    /**
     * @access private
     */
    private $aPinta_Opciones;

    /**
     * inicia los parametros del tipo de arbol
     * @access public
     * @param $sTipoArbol
     * @param null $sOpcion
     */
    public function Inicia_Arbol($sTipoArbol, $sOpcion = null)
    {
        switch ($sTipoArbol) {
            case 'menu':
                $this->aPinta_Opciones = array('images' => 'images/menu',
                    'bCheckBox' => 0);

                $sNavegador = $_SESSION['navegador'];

                if ($sNavegador == "Microsoft Internet Explorer") {
                    $this->nodo_Opciones = array('onmouseover' => 'this.classname=\'seleccionado\'',
                        'onmouseout' => 'this.classname=\'normal\''
                    );
                } else {
                    $this->nodo_Opciones = array('onmouseover' => 'this.classname=\'seleccionado\'',
                        'onmouseout' => 'this.classname=\'normal\''
                    );

                }

                break;
            case 'pepe':
                $this->aPinta_Opciones = array('images' => 'images/menu');//,
                //                                 'bCheckBox'=> 1);
                $this->nodo_Opciones = array('onmouseover' => 'this.classname=\'seleccionado\'',
                    'onmouseout' => 'this.classname=\'normal\'');
                break;
            case 'radio':
                $this->aPinta_Opciones = array('images' => 'images/menu', 'bDepende' => 0);
                $this->nodo_Opciones = array('onmouseover' => 'this.classname=\'seleccionado\'',
                    'onmouseout' => 'this.classname=\'normal\'');
                break;
            case 'nodepende':
                $this->aPinta_Opciones = array('images' => 'images/menu', 'bDepende' => 2);
                $this->nodo_Opciones = array('onmouseover' => 'this.classname=\'seleccionado\'',
                    'onmouseout' => 'this.classname=\'normal\'');
                break;
            default;
                echo 'error tipoarbol ' . $this->Pinta_Arbol() . $sTipoArbol . '\n';
        }
        $this->oArbol = new HTML_TreeMenu();
    } // fin de Inicia_Arbol

    /**
     * Aade un nodo al arbol
     * @param string sEtiqueta
     * @param string sClaseCSS
     * @param bool $bExpanded
     * @return HTML_TreeNode
     * @access public
     * @return object
     */
    public function Nuevo_Nodo($sEtiqueta = 'Valor no definido', $sClaseCSS = 'body', $bExpanded = false)
    {
        $oNodo = new HTML_TreeNode(array('text' => $sEtiqueta, 'cssClass' => $sClaseCSS, 'expanded' => $bExpanded), $this->nodo_Opciones);
        return $oNodo;
    } // end of member function Nuevo_nodo

    /**
     * Coloca un nodo colgando de otro en el arbol
     * @param HTML_TreeNode $oNodo
     * @param HTML_TreeNode|null $oPadre
     */
    public function Situa_Nodo(HTML_TreeNode $oNodo, HTML_TreeNode &$oPadre = null)
    {
        if ($oPadre == null)
            $this->addItem($oNodo);
        else {
            $oPadre->addItem($oNodo);
        }
    }//fin Situa_Nodo

    /**
     *
     * Aade eventos a las entradas de menu
     * @param HTML_TreeNode oNodo
     * @param string sTipoEvento
     * @param string sAccion
     * @access public
     */
    public function Nuevo_Evento_Menu(HTML_TreeNode &$oNodo, $sTipoEvento, $sAccion)
    {
        strtolower($sTipoEvento);
        $oNodo->events[$sTipoEvento] = $sAccion;
    }// fin Nuevo_Evento_Menu

    /**
     *
     * Genera el codigo HTML necesario
     * @param string sTipo_Salida
     * @return string
     * @access public
     */
    public function Pinta_Arbol($sTipo_Salida = 'DHTML')
    {
        switch ($sTipo_Salida) {
            case 'DHTML':
                $oConstructorArbol = new HTML_TreeMenu_DHTML($this, $this->aPinta_Opciones);
                break;
            default:
                echo 'error Pinta_Arbol ' . $sTipo_Salida;
        }
        return $oConstructorArbol->toHTML();

    } // fin de Pinta_Arbol

    public function valor_check($sValor, $sValue)
    {
        $this->nodo_Opciones['bCheckBox'] = 1;
        $this->nodo_Opciones['chequeado'] = $sValor;
        $this->nodo_Opciones['checkvalue'] = $sValue;
    }


} // end of Generador_Arboles

class arbol_listas
{
    private $aAcciones;
    private $sHtml;
    public $aOpciones;
    private $oDb;
    public $accionMenu;
    public $accionBotones;
    public $aArbol;

    public function __construct($aDatos, $aOps)
    {
        if (is_array($aOps)) {
            $this->aOpciones = $aOps;
        }
        if (array_key_exists('accion', $aDatos)) {
            $aCampos = array($aDatos['pkey'], $aDatos['padre'], $aDatos['etiqueta'], $aDatos['accion']);
            $this->aOpciones['accion'] = true;
        } else {
            $aCampos = array($aDatos['pkey'], $aDatos['padre'], $aDatos['etiqueta']);
        }
        $this->oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $this->oDb->iniciar_Consulta('SELECT');
        $this->oDb->construir_Campos($aCampos);
        $this->oDb->construir_Tablas($aDatos['tablas']);
        $this->oDb->construir_Where(array($aDatos['pkey'] . '<>0', $aDatos['condicion']));
        if (isset($aDatos['order'])) {
            $this->oDb->construir_Order(array($aDatos['order']));
        }
        $this->oDb->consulta();

        while ($aIterador = $this->oDb->coger_Fila()) {

            $this->aArbol[$aIterador[1]][$aIterador[0]] = $aIterador[2];
            if ($this->aOpciones['accion']) {
                $this->aAcciones[$aIterador[0]] = $aIterador[3];
            }
        }

    }

    /**
     * @param $oPadre
     * @param int $level
     */
    public function colocarama($oPadre, $level =0)
    {
        if (is_array($this->aArbol[$oPadre])) {
            if ($level == 0){
                $this->sHtml .= '<ul class="nav navbar-nav">';
            }
            else if ($level == 1){
                $this->sHtml .= '<ul class="dropdown-menu multi-level">';
            }
            else {
                $this->sHtml .= '<ul class="dropdown-submenu">';
            }
            foreach ($this->aArbol[$oPadre] as $oId => $oTitulo) {
                $this->sHtml .= '<li class="dropdown" id="' . $oId . '">';
                if (($this->aOpciones['accion']) && (!$this->aOpciones['permisos'])) {
                    $this->sHtml .= "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"".
                        " onclick=sndReq('" . $this->aAcciones[$oId] . "','',1,'')>" . $oTitulo
                        .'<b class="caret"></b></a>';
                } else if ($this->aOpciones['permisos']) {
                    $aTipo = explode(separador, $oTitulo);
                    if (count($aTipo) > 1) {
                        $this->sHtml .= "<a onmouseover=\"this.className='encima'\" onclick=" .
                            $this->accionBotones[$oId] . ">" . $aTipo[0] . "</a>";
                    } else {
                        $this->sHtml .= "<a onmouseover=\"this.className='encima'\" onclick=" .
                            $this->accionMenu[$oId] . ">" . $oTitulo . "</a>";
                    }
                } else {
                    $this->sHtml .= '<a href="#">' . $oTitulo . '</a>';
                }
                $this->colocarama($oId, $level+1);
            }
            $this->sHtml .= '</ul>';
        }
    }

    public function genera_arbol_drag_and_drop()
    {
        $this->sHtml .= '<ul id="arbol_menu" class="dhtmlgoodies_tree"><li id="node0000" noDrag="true" noSiblings="true"><a href="#">Menu</a>';
        $this->sHtml .= '<ul><li id="0" noDrag="true"><a href="#">Activos</a>';
        $this->colocarama(0);
        $this->sHtml .= '<li id="9999" noDrag="true"><a href="#">Inactivos</a>';
        $this->colocarama(9999);
        $this->sHtml .= '</ul>';
        $this->sHtml .= '</ul>';
    }

    public function genera_arbol_drag_and_drop_permisos($iId, $sMenu)
    {
        $this->sHtml = '<ul id="arbol_menu" class="dhtmlgoodies_tree">'.
            '<li id="node0000" noDrag="true" noSiblings="true"><a href="#">Menu</a>';
        $this->sHtml .= '<ul><li id="0" noDrag="true"><a onmouseover="this.className=\'encima\'" onclick=' .
            $this->accionMenu[$iId] . '>' . $sMenu . '</a>';
        $this->colocarama($iId);
        $this->sHtml .= '</ul>';
        $this->sHtml .= '</ul>';
    }

    public function genera_arbol_menu()
    {
        $this->colocarama(0);
    }

    public function to_Html()
    {
        return $this->sHtml;
    }
}

