<?php

/**
 * Created on 19-oct-2005
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 0.5.1f
 * @TODO Eliminar css innecesario e incluir css menus
 * En este archivo definimos la/s hoja/s de estilo que va a tener la aplicacion.
 * Todas ellas seran clases que extienden de HTML_CSS.
 *
 */

/**
 * Aqui cargamos la libreria de PEAR 'Css.php', desde donde extenderemos nuestra/s hoja/s
 * de estilo.
 */
if (!isset($_SESSION)) {
    session_start();
}
require_once 'HTML/CSS.php';

/**
 * Este es la clase que cargar el estilo general de la aplicacion.
 * Tendra un solo constructor que nos devolvera el objeto con el estilo cargado.
 */
class Estilo_Pagina extends HTML_CSS
{

    public function __construct($iAnchura, $iAltura, $sBrowser=null)
    {
        /**
         * Variables a usar
         */
        parent::HTML_CSS();
        require_once 'items.php';



         $iAnchoContenedor = ((($iAnchura * 95) / 100));
         $iAnchura = ($iAnchura - 20);
        if ($sBrowser == "Microsoft Internet Explorer") {
            $iAnchura = ($iAnchura + 20);
        }
        if ($sBrowser == "Microsoft Internet Explorer") {
            $iAltura = ($iAltura - 184);
        }
        if ($sBrowser == "Microsoft Internet Explorer") {
            $iAlturaCont = ($iAltura - 156);
        } else {
            $iAlturaCont = ($iAltura - 196);
        }
        $iAnchoCalendario = $iAnchoContenedor;

        /**        $sColorPrimario = '#000033'; //azuloscuro
         * $sColorSecundario = '#ff9900'; //naranja
         * $sColorTerciario = '#0000ee'; //azul
         * $sColorTextoPrimario='#000033'; //casi negro
         * $sColorTextoSecundario='#ff9900'; //casi blanco
         * $sFondoPrimario='#f6f6f6'; //Gris claro
         * $sFondoSecundario='#dfdfe5'; //Gris mas claro
         */


        $sColorPrimario = '#ffffff'; //blanco
        $sColorSecundario = '#3399cc'; //azul ICS
        $sColorTerciario = '#ffcc00'; //naranja NOVASOFT
        $sColorCuarto = '#333366'; //azul oscuro
        $sColorGris = '#999999'; // gris
        $sColorTextoPrimario = '#ffffff'; // blanco
        $sColorTextoSecundario = '#3399cc'; //azul ICS
        $sColorTextoTerciario = '#333333'; //gris oscuro
        $sFondoPrimario = '#ffffff'; //blanco
        $sFondoSecundario = '#ffffff'; //blanco
        $sFilaPar = '#AEE0F5'; //87D1F0 o dddddd
        $sFilaImpar = '#D6EFFA'; //D6EFFA o eeeeee
        $sCabeceraTabla = '#3399cc';


        /**
         * Cambiamos la fuente en funcion de la resolucion
         */


        /**
         * BODY: Aqui definimos las propiedades del cuerpo de la pagina.
         */

        $this->setStyle('body', 'background-color', $sFondoSecundario);
        $this->setStyle('body', 'margin', '0 0 50px');
        $this->setStyle('body', 'padding', '0');
        $this->setStyle('body', 'font-family', '"Arial", sans-serif');
        $this->setStyle('body', 'color', $sColorTextoTerciario);
        $this->setStyle('body', 'height','100%');
        $this->setStyle('body', 'width',  '100%');
        //$this->setStyle('body', 'overflow', 'hidden');
        $this->setStyle('body', 'font-size', '8pt');


        $this->setStyle('#pagina', 'height', '100%');
        $this->setStyle('#pagina', 'width', '100%');
        //$this->setStyle('#pagina', 'overflow', 'hidden');
        $this->setStyle('#pagina', 'background-color', $sFondoPrimario);


        /************************
         * Layers
         ************************/

        /**
         * Aqui definimos la cabecera de nuestra pagina, su anchura, altura, posición y márgenes.
         */


        /**
         * Cabecera Superior
         */


        /*
        $this->setStyle('#cabecera1', 'background-image', 'url("images/Cab_Izq.gif")');
        $this->setStyle('#cabecera1', 'background-repeat', 'no repeat');
        $this->setStyle('#cabecera1', 'background-attachment', 'scroll');
        $this->setStyle('#cabecera1', 'background-position', 'top');
        $this->setStyle('#cabecera1', 'top', '0px');
        $this->setStyle('#cabecera1', 'left', '0px');
        $this->setStyle('#cabecera1', 'height', '129px');
        $this->setStyle('#cabecera1', 'width', '18px');
        $this->setStyle('#cabecera1', 'position', 'absolute');
        $this->setStyle('#cabecera1', 'z-index', '0');
        */

        //$this->setStyle('#cabecera2', 'background-image', 'url("images/logotipo-tuqan.png")');
        //$this->setStyle('#cabecera2', 'background-repeat', 'no-repeat');
        //$this->setStyle('#cabecera2', 'background-attachment', 'scroll');
        //$this->setStyle('#cabecera2', 'background-position', 'center');
        $this->setStyle('#cabecera2', 'background-color', '#F0F0F0');
        /*$this->setStyle('#cabecera2', 'top', '0px');
        $this->setStyle('#cabecera2', 'left', '18px');*/
        $this->setStyle('#cabecera2', 'height', '129px');
        $this->setStyle('#cabecera2', 'width', '100%');
        $this->setStyle('#cabecera2', 'text-align', 'center');
        $this->setStyle('#cabecera2 .logo_tuqan', 'max-width', '300px');
        $this->setStyle('#cabecera2 .logo_tuqan', 'margin-top', '17px');

        $this->setStyle('#cabecera3','box-shadow','inset 0 3px 3px 2px rgba(0, 0, 0, 0.2)');
        $this->setStyle('#cabecera3','-moz-box-shadow','inset 0 3px 3px 2px rgba(0, 0, 0, 0.2)');
        $this->setStyle('#cabecera3','-webkit-box-shadow','inset 0 3px 3px 2px rgba(0, 0, 0, 0.2)');
        $this->setStyle('#cabecera3','background-color','#BE1622');
        $this->setStyle('#cabecera3','margin-top','-10px');
        $this->setStyle('.titulo_cabecera','font-size','12pt');
        $this->setStyle('.titulo_cabecera','font-weight','normal');
        $this->setStyle('.titulo_cabecera','color','#E4E4E4');
        $this->setStyle('.titulo_cabecera','text-align','center');
        $this->setStyle('.titulo_cabecera','padding','20px 0');
        $this->setStyle('.titulo_cabecera','letter-spacing','0.4em');
        $this->setStyle('.titulo_cabecera','text-shadow','1px 1px 1px rgba(0,0,0,0.3)');
        $this->setStyle('.titulo_cabecera','font-family','"Arial",sans-serif');

        /*$this->setStyle('#cabecera2', 'position', 'absolute');*/
        /*$this->setStyle('#cabecera2', 'z-index', '0');*/
        /*$this->setStyle('#cabecera3', 'background-image', 'url("images/Patron_Centro.gif")');
        $this->setStyle('#cabecera3', 'background-repeat', 'repeat-x');
        $this->setStyle('#cabecera3', 'background-attachment', 'scroll');
        $this->setStyle('#cabecera3', 'background-position', 'top');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#cabecera3', 'width', ($iAnchura - 688) . "px");
        } else {
            $this->setStyle('#cabecera3', 'width', ($iAnchura - 688) . "px");
        }
        /**if ($sBrowser == "Microsoft Internet Explorer") {
         * $this->setStyle('#cabecera3', 'width', ($iAnchura - 709)."px");
         * }
         * else
         * {
         * $this->setStyle('#cabecera3', 'width', ($iAnchura - 703)."px");
         * }*/
        /*$this->setStyle('#cabecera3', 'height', '129px');
        $this->setStyle('#cabecera3', 'top', '0px');
        $this->setStyle('#cabecera3', 'left', '547px');
        $this->setStyle('#cabecera3', 'position', 'absolute');
        $this->setStyle('#cabecera3', 'overflow', 'hidden');
        $this->setStyle('#cabecera3', 'z-index', '0');

        $this->setStyle('#cabecera4', 'background-image', 'url("images/Cab_LogoICS.gif")');
        $this->setStyle('#cabecera4', 'background-repeat', 'no repeat');
        $this->setStyle('#cabecera4', 'background-attachment', 'scroll');
        $this->setStyle('#cabecera4', 'background-position', 'top');
        $this->setStyle('#cabecera4', 'top', '0px');
        $this->setStyle('#cabecera4', 'right', '18px');
        $this->setStyle('#cabecera4', 'height', '129px');
        $this->setStyle('#cabecera4', 'width', '200px');
        $this->setStyle('#cabecera4', 'position', 'absolute');
        $this->setStyle('#cabecera4', 'z-index', '0');

        $this->setStyle('#cabecera5', 'background-image', 'url("images/Cab_Der.gif")');
        $this->setStyle('#cabecera5', 'background-repeat', 'no repeat');
        $this->setStyle('#cabecera5', 'background-attachment', 'scroll');
        $this->setStyle('#cabecera5', 'background-position', 'top');
        $this->setStyle('#cabecera5', 'top', '0px');
        $this->setStyle('#cabecera5', 'right', '0px');
        $this->setStyle('#cabecera5', 'height', '129px');
        $this->setStyle('#cabecera5', 'width', '18px');
        $this->setStyle('#cabecera5', 'position', 'absolute');
        $this->setStyle('#cabecera5', 'z-index', '0');*/

        //----------------------------------------------------------------------------

        /**********
         * Logins *
         **********/

        /*$this->setStyle('#login', 'position', 'absolute');
        $this->setStyle('#login', 'top', '129px');
        $this->setStyle('#login', 'left', '13px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#login', 'width', ($iAnchura - 50) . "px");
        } else {
            $this->setStyle('#login', 'width', ($iAnchura - 26) . "px");
        }
        $this->setStyle('#login', 'height', '47px');*/
        //$this->setStyle('#login', 'border','1px solid '.$sColorGris);
        //$this->setStyle('#login', 'overflow', 'hidden');
        $this->setStyle('#login', 'font-family', '"Arial", sans-serif');
        $this->setStyle('#login', 'font-weight', 'normal');
        $this->setStyle('#login', 'text-align', 'center');
        $this->setStyle('#login', 'text-transform', 'uppercase');
        $this->setStyle('#login', 'font-size', '10pt');
        $this->setStyle('#login', 'color', '#575756');
        $this->setStyle('#login', 'margin-top', '40px');
        //$this->setStyle('#login', 'background-color', $sColorSecundario);


        /*************************************************************
         * Sub: Aqui definimos la division situada justo debajo de la cabecera.
         * Propiedades: posicion, anchura, altura y margenes.
         *************************************************************/


        /*$this->setStyle('#imagenizq', 'background-image', 'url("images/Esq_Izq_Usuario.gif")');
        $this->setStyle('#imagenizq', 'background-repeat', 'no repeat');
        $this->setStyle('#imagenizq', 'background-attachment', 'scroll');
        $this->setStyle('#imagenizq', 'background-position', 'top');
        $this->setStyle('#imagenizq', 'width', '18px');
        $this->setStyle('#imagenizq', 'height', '22px');
        $this->setStyle('#imagenizq', 'top', '129px');
        $this->setStyle('#imagenizq', 'left', '0px');
        $this->setStyle('#imagenizq', 'overflow', 'hidden');
        $this->setStyle('#imagenizq', 'position', 'absolute');*/

        //$this->setStyle('#usuariodiv', 'background-color', $sColorSecundario);
        $this->setStyle('#usuariodiv', 'color', '#575756');
        //$this->setStyle('#usuariodiv', 'width', '261px');
        $this->setStyle('#usuariodiv', 'height', '22px');
        //$this->setStyle('#usuariodiv', 'top', '129px');
        //$this->setStyle('#usuariodiv', 'left', '18px');
        $this->setStyle('#usuariodiv', 'font-family', '"Arial", sans-serif');
        $this->setStyle('#usuariodiv', 'font-weight', 'regular');
        $this->setStyle('#usuariodiv', 'text-align', 'left');
        $this->setStyle('#usuariodiv', 'padding-top', '4px');
        $this->setStyle('#usuariodiv', 'padding-right', '10px');
        $this->setStyle('#usuariodiv', 'border-bottom', '1px dotted #797979');
        $this->setStyle('#usuariodiv', 'float', 'left');
        $this->setStyle('#usuariodiv', 'margin-top', '50px');
        $this->setStyle('#usuariodiv', 'margin-left', '20px');

        //$this->setStyle('#usuariodiv', 'overflow', 'hidden');
        //$this->setStyle('#usuariodiv', 'position', 'absolute');

        /*$this->setStyle('#separador', 'background-image', 'url("images/Separador_usuario.gif")');
        $this->setStyle('#separador', 'background-repeat', 'no repeat');
        $this->setStyle('#separador', 'background-attachment', 'scroll');
        $this->setStyle('#separador', 'background-position', 'top');
        $this->setStyle('#separador', 'width', '1px');
        $this->setStyle('#separador', 'height', '22px');
        $this->setStyle('#separador', 'top', '129px');
        $this->setStyle('#separador', 'left', '279px');
        $this->setStyle('#separador', 'overflow', 'hidden');
        $this->setStyle('#separador', 'position', 'absolute');*/

        //$this->setStyle('#titulo', 'background-color', $sColorTerciario);
        $this->setStyle('#titulo', 'color', '#575756');
        /*if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#titulo', 'width', ($iAnchura - 298) . "px");
        } else {
            $this->setStyle('#titulo', 'width', ($iAnchura - 298) . "px");
        }
        /**if ($sBrowser == "Microsoft Internet Explorer") {
         * $this->setStyle('#titulo', 'width', ($iAnchura - 319)."px");
         * }
         * else
         * {
         * $this->setStyle('#titulo', 'width', ($iAnchura - 313)."px");
         * }*/
        $this->setStyle('#titulo', 'height', '22px');
        //$this->setStyle('#titulo', 'top', '129px');
        //$this->setStyle('#titulo', 'left', '280px');
        $this->setStyle('#titulo', 'font-family', 'Arial, sans-serif');
        $this->setStyle('#titulo', 'font-weight', 'regular');
        $this->setStyle('#titulo', 'text-align', 'left');
        $this->setStyle('#titulo', 'padding-top', '4px');
        $this->setStyle('#titulo', 'padding-left', '10px');
        //$this->setStyle('#titulo', 'position', 'absolute');
        $this->setStyle('#titulo', 'overflow', 'hidden');
        $this->setStyle('#titulo', 'border-left', '1px dotted #797979');
        $this->setStyle('#titulo', 'border-bottom', '1px dotted #797979');
        $this->setStyle('#titulo', 'float', 'left');
        $this->setStyle('#titulo', 'margin-top', '50px');

        /*$this->setStyle('#imagender', 'background-image', 'url("images/Esq_Der_Titulo.gif")');
        $this->setStyle('#imagender', 'background-repeat', 'no repeat');
        $this->setStyle('#imagender', 'background-attachment', 'scroll');
        $this->setStyle('#imagender', 'background-position', 'top');
        $this->setStyle('#imagender', 'width', '18px');
        $this->setStyle('#imagender', 'height', '22px');
        $this->setStyle('#imagender', 'top', '129px');
        $this->setStyle('#imagender', 'right', '0px');
        $this->setStyle('#imagender', 'overflow', 'hidden');
        $this->setStyle('#imagender', 'position', 'absolute');*/

        /*
         * Las variables estan arriba
         */
        /************
         * Usuario
         ************/


        /*$this->setStyle('#imgmenuizq', 'background-image', 'url("images/Esq_Izq_MP.gif")');
        $this->setStyle('#imgmenuizq', 'background-repeat', 'no repeat');
        $this->setStyle('#imgmenuizq', 'background-attachment', 'scroll');
        $this->setStyle('#imgmenuizq', 'background-position', 'top');
        $this->setStyle('#imgmenuizq', 'width', '18px');
        $this->setStyle('#imgmenuizq', 'height', '25px');
        $this->setStyle('#imgmenuizq', 'top', '151px');
        $this->setStyle('#imgmenuizq', 'left', '0px');
        $this->setStyle('#imgmenuizq', 'overflow', 'visible');
        $this->setStyle('#imgmenuizq', 'position', 'absolute');
        $this->setStyle('#imgmenuizq', 'z-index', '1');*/

        /*
         * dhtmlgoodies_menu
         */

        /*$this->setStyle('#submenu', 'background-image', 'url("images/Patron_MP.gif")');
        $this->setStyle('#submenu', 'background-repeat', 'repeat-x');
        $this->setStyle('#submenu', 'background-attachment', 'scroll');
        $this->setStyle('#submenu', 'background-position', 'top');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#submenu', 'width', ($iAnchura - 36) . "px");
        } else {
            $this->setStyle('#submenu', 'width', ($iAnchura - 36) . "px");
        }
        /**if ($sBrowser == "Microsoft Internet Explorer") {
         * $this->setStyle('#submenu','width', ($iAnchura - 57)."px");
         * }
         * else
         * {
         * $this->setStyle('#submenu','width', ($iAnchura - 51)."px");
         * }*/
        $this->setStyle('#submenu', 'height', '25px');
       // $this->setStyle('#submenu', 'top', '151px');
        //$this->setStyle('#submenu', 'left', '18px');
        //$this->setStyle('#submenu', 'z-index', '1');
        $this->setStyle('#submenu', 'position', 'relative');
        $this->setStyle('#submenu', 'width', '100%');
        $this->setStyle('#submenu', 'background-color', '#ffffff');
        $this->setStyle('#submenu', 'margin-top', '-11px');
        $this->setStyle('#submenu', 'padding', '10px 0');
        $this->setStyle('#submenu', 'margin-bottom', '20px');
        $this->setStyle('#submenu','box-shadow','1px 1px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu','-moz-box-shadow','1px 1px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu','-webkit-box-shadow','1px 1px 5px rgba(0,0,0,0.2)');

        $this->setStyle('#submenu a', 'font-size', '8pt');
        $this->setStyle('#submenu a', 'margin', '0px'); //Espacio exterior entre opciones de 1� nivel
        $this->setStyle('#submenu a', 'color', '#BE1622'); //Color del texto 1� nivel
        $this->setStyle('#submenu a', 'text-decoration', 'none');

        $this->setStyle('#submenu .currentDepth1', 'padding-left', '5px');
        $this->setStyle('#submenu .currentDepth1', 'padding-right', '5px');
        $this->setStyle('#submenu .currentDepth1', 'padding-top', '6px');

        $this->setStyle('#submenu .currentDepth1 a', 'font-weight', 'bold');

        $this->setStyle('#submenu .currentDepth1over', 'padding-left', '5px');
        $this->setStyle('#submenu .currentDepth1over', 'padding-right', '5px');
        $this->setStyle('#submenu .currentDepth1over', 'padding-top', '6px');
        $this->setStyle('#submenu .currentDepth1over', 'height', '25px');
        //$this->setStyle('#submenu .currentDepth1over', 'background-image', 'url("images/Patron_MP_bg.gif")');
        //$this->setStyle('#submenu .currentDepth1over', 'background-repeat', 'repeat-x');
        //$this->setStyle('#submenu .currentDepth1over', 'background-attachment', 'scroll');
        //$this->setStyle('#submenu .currentDepth1over', 'background-position', 'top');

        $this->setStyle('#submenu .currentDepth1over a', 'color', '#575756');
        $this->setStyle('#submenu .currentDepth1over a', 'font-weight', 'normal');

        $this->setStyle('#submenu .currentDepth2', 'width', '150px'); //Tama�o del desplegable de 2� nivel
        $this->setStyle('#submenu .currentDepth2', 'padding', '2px');
        $this->setStyle('#submenu .currentDepth2', 'background-color', '#BE1622');

        $this->setStyle('#submenu .currentDepth2 a','width', '140px');//Posicionamiento del desplegable de tercer nivel
        $this->setStyle('#submenu .currentDepth2 a', 'color', $sColorTextoPrimario);
        $this->setStyle('#submenu .currentDepth2 a', 'font-weight', 'normal');

        $this->setStyle('#submenu .currentDepth2over', 'width', '150px'); //Tama�o del desplegable de 2� nivel
        $this->setStyle('#submenu .currentDepth2over', 'padding', '2px');
        $this->setStyle('#submenu .currentDepth2over', 'background-color', '#393938');

        $this->setStyle('#submenu .currentDepth2over a', 'width', '150px'); //Tama�o del desplegable de 2� nivel
        $this->setStyle('#submenu .currentDepth2over a', 'color', $sColorTextoPrimario);
        $this->setStyle('#submenu .currentDepth2over a', 'font-weight', 'normal');

        $this->setStyle('#submenu .currentDepth3', 'padding', '2px');
        $this->setStyle('#submenu .currentDepth3', 'background-color', '#575756');
        $this->setStyle('#submenu .currentDepth3', 'width', '120px'); //Tama�o del desplegable de 3� nivel

        $this->setStyle('#submenu .currentDepth3 a', 'color', $sColorPrimario);
        $this->setStyle('#submenu .currentDepth3 a', 'font-weight', 'normal');

        $this->setStyle('#submenu .currentDepth3over', 'padding', '2px');
        $this->setStyle('#submenu .currentDepth3over', 'background-color', '#797979');
        $this->setStyle('#submenu .currentDepth3over', 'width', '120px'); //Tama�o del desplegable de 3� nivel

        $this->setStyle('#submenu .currentDepth3over a', 'color', $sColorTextoPrimario);
        $this->setStyle('#submenu .currentDepth3over a', 'font-weight', 'normal');

        $this->setStyle('#submenu ul li ul', 'display', 'none');

        $this->setStyle('#submenu li', 'list-style-type', 'none');

        $this->setStyle('#submenu ul', 'margin', '0px');

        $this->setStyle('#submenu ul.menuBlock1', 'border', '0px');
        $this->setStyle('#submenu ul.menuBlock1', 'padding', '0px');
        $this->setStyle('#submenu ul.menuBlock1', 'overflow', 'visible');

        $this->setStyle('#submenu ul.menuBlock2', 'margin-top', '-22px'); //Posicionamiento del menu secundario
        $this->setStyle('#submenu ul.menuBlock2', 'padding', '10px');
        $this->setStyle('#submenu ul.menuBlock2', 'background', '#BE1622');
        $this->setStyle('#submenu ul.menuBlock2', 'border-radius', '2px');
        $this->setStyle('#submenu ul.menuBlock2', 'box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu ul.menuBlock2', '-moz-box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu ul.menuBlock2', '-webkit-box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu ul.menuBlock2', 'top', '0px');

        $this->setStyle('#submenu ul.menuBlock3', 'margin-top', '6px');
        $this->setStyle('#submenu ul.menuBlock3', 'margin-left', '12px');
        $this->setStyle('#submenu ul.menuBlock3', 'padding', '10px');
        $this->setStyle('#submenu ul.menuBlock3', 'background', '#575756');
        $this->setStyle('#submenu ul.menuBlock3', 'border-radius', '2px');
        $this->setStyle('#submenu ul.menuBlock3', 'box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu ul.menuBlock3', '-moz-box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');
        $this->setStyle('#submenu ul.menuBlock3', '-webkit-box-shadow', '2px 2px 5px rgba(0,0,0,0.2)');


       /* $this->setStyle('#imgmenuder', 'background-image', 'url("images/Esq_Der_MP.gif")');
        $this->setStyle('#imgmenuder', 'background-repeat', 'no repeat');
        $this->setStyle('#imgmenuder', 'background-attachment', 'scroll');
        $this->setStyle('#imgmenuder', 'background-position', 'top');
        $this->setStyle('#imgmenuder', 'width', '18px');
        $this->setStyle('#imgmenuder', 'height', '25px');
        $this->setStyle('#imgmenuder', 'top', '151px');
        $this->setStyle('#imgmenuder', 'right', '0px');
        $this->setStyle('#imgmenuder', 'overflow', 'hidden');
        $this->setStyle('#imgmenuder', 'position', 'absolute');
        $this->setStyle('#imgmenuder', 'z-index', '1');*/


        /*****************************
         * Seccion de Administracion *
         *****************************/


        $this->setStyle('#administrador', 'background-color', $sColorSecundario);
        $this->setStyle('#administrador', 'height', '100%');
        $this->setStyle('#administrador', 'color', $sColorTextoPrimario);
        $this->setStyle('#administrador', 'width', '15%');
        $this->setStyle('#administrador', 'overflow', 'hidden');
        $this->setStyle('#administrador', 'text-align', 'center');
        $this->setStyle('#administrador', 'font-family', '"helvetica", sans-serif');
        $this->setStyle('#administrador', 'font-weight', 'bold');
        $this->setStyle('#administrador', 'margin-left', '80%');
        $this->setStyle('#administrador', 'display', 'inline');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.admin_over', 'cursor', 'hand');

            $this->setStyle('b.admin_over', 'color', $sColorTextoPrimario);
            $this->setStyle('b.admin_out', 'color', $sColorTextoPrimario);
            $this->setStyle('b.admin_out', 'text-decoration', 'none');
        } else {
            $this->setStyle('.admin_over', 'cursor', 'pointer');
            $this->setStyle('#administrador:hover', 'color', $sColorTextoPrimario);
        }


        $this->setStyle('#volver', 'background-color', $sColorSecundario);
        $this->setStyle('#volver', 'font-size', '90%');
        $this->setStyle('#volver', 'height', '100%');
        $this->setStyle('#volver', 'color', $sColorPrimario);
        $this->setStyle('#volver', 'width', '20%');
        $this->setStyle('#volver', 'overflow', 'hidden');
        $this->setStyle('#volver', 'text-align', 'center');
        $this->setStyle('#volver', 'font-family', '"helvetica", sans-serif');
        $this->setStyle('#volver', 'font-weight', 'bold');
        $this->setStyle('#volver', 'margin-left', '80%');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.admin_over', 'cursor', 'hand');
            $this->setStyle('b.admin_over', 'color', $sColorTerciario);
            $this->setStyle('b.admin_out', 'color', $sColorTextoPrimario);
            $this->setStyle('b.admin_out', 'text-decoration', 'none');
        } else {
            $this->setStyle('#volver:hover', 'color', $sColorTextoPrimario);
        }


        /**************
         * Marco Exterior
         **************/

       /* $this->setStyle('#BordeIzq', 'width', '18px');
        $this->setStyle('#BordeIzq', 'height', $iAlturaCont . 'px');
        $this->setStyle('#BordeIzq', 'top', '176px');
        $this->setStyle('#BordeIzq', 'left', '0px');
        $this->setStyle('#BordeIzq', 'z-index', '0');
        $this->setStyle('#BordeIzq', 'position', 'absolute');
        $this->setStyle('#BordeIzq', 'background-image', 'url("images/Patron_Izq.gif")');
        $this->setStyle('#BordeIzq', 'background-repeat', 'repeat-y');
        $this->setStyle('#BordeIzq', 'background-attachment', 'scroll');
        $this->setStyle('#BordeIzq', 'background-position', 'top');

        $this->setStyle('#BordeDer', 'width', '18px');
        $this->setStyle('#BordeDer', 'height', $iAlturaCont . 'px');
        $this->setStyle('#BordeDer', 'top', '176px');
        $this->setStyle('#BordeDer', 'right', '0px');
        $this->setStyle('#BordeDer', 'z-index', '0');
        $this->setStyle('#BordeDer', 'position', 'absolute');
        $this->setStyle('#BordeDer', 'background-image', 'url("images/Patron_Der.gif")');
        $this->setStyle('#BordeDer', 'background-repeat', 'repeat-y');
        $this->setStyle('#BordeDer', 'background-attachment', 'scroll');
        $this->setStyle('#BordeDer', 'background-position', 'top');

        $this->setStyle('#Esq_Izq', 'width', '18px');
        $this->setStyle('#Esq_Izq', 'height', '14px');
        $this->setStyle('#Esq_Izq', 'top', ($iAlturaCont + 176) . 'px');
        $this->setStyle('#Esq_Izq', 'left', '0px');
        $this->setStyle('#Esq_Izq', 'z-index', '0');
        $this->setStyle('#Esq_Izq', 'position', 'absolute');
        $this->setStyle('#Esq_Izq', 'background-image', 'url("images/Esq_Inf_Izq.gif")');
        $this->setStyle('#Esq_Izq', 'background-repeat', 'no-repeat');
        $this->setStyle('#Esq_Izq', 'background-attachment', 'scroll');
        $this->setStyle('#Esq_Izq', 'background-position', 'top');

        $this->setStyle('#Esq_Der', 'width', '18px');
        $this->setStyle('#Esq_Der', 'height', '14px');
        $this->setStyle('#Esq_Der', 'top', ($iAlturaCont + 176) . 'px');
        $this->setStyle('#Esq_Der', 'right', '0px');
        $this->setStyle('#Esq_Der', 'z-index', '0');
        $this->setStyle('#Esq_Der', 'position', 'absolute');
        $this->setStyle('#Esq_Der', 'background-image', 'url("images/Esq_Inf_Der.gif")');
        $this->setStyle('#Esq_Der', 'background-repeat', 'no-repeat');
        $this->setStyle('#Esq_Der', 'background-attachment', 'scroll');
        $this->setStyle('#Esq_Der', 'background-position', 'top');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#Borde_Inf', 'width', ($iAnchura - 36) . "px");
        } else {
            $this->setStyle('#Borde_Inf', 'width', ($iAnchura - 36) . "px");
        }
        /**if ($sBrowser == "Microsoft Internet Explorer") {
         * $this->setStyle('#Borde_Inf','width', ($iAnchura - 57)."px");
         * }
         * else
         * {
         * $this->setStyle('#Borde_Inf','width', ($iAnchura - 51)."px");
         * }*/
        /*$this->setStyle('#Borde_Inf', 'height', '14px');
        $this->setStyle('#Borde_Inf', 'top', ($iAlturaCont + 176) . 'px');
        $this->setStyle('#Borde_Inf', 'left', '18px');
        $this->setStyle('#Borde_Inf', 'z-index', '0');
        $this->setStyle('#Borde_Inf', 'position', 'absolute');
        $this->setStyle('#Borde_Inf', 'background-image', 'url("images/Patron_Inf.gif")');
        $this->setStyle('#Borde_Inf', 'background-repeat', 'repeat-x');
        $this->setStyle('#Borde_Inf', 'background-attachment', 'scroll');
        $this->setStyle('#Borde_Inf', 'background-position', 'top');*/

        /********************
         * Cargar / Guardar *
         ********************/

        $this->setStyle('#wait', 'position', 'absolute');
        $this->setStyle('#wait', 'background-attachment', 'fixed');
        $this->setStyle('#wait', 'right', '0px');
        $this->setStyle('#wait', 'max-width', '20%');
        $this->setStyle('#wait', 'top', '90px');

        /**************
         * Secciones
         **************/

       /* $this->setStyle('#contenido', 'position', 'absolute');
        $this->setStyle('#contenido', 'top', '176px');
        $this->setStyle('#contenido', 'left', '18px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#contenido', 'width', ($iAnchura - 40) . "px");
        } else {
            $this->setStyle('#contenido', 'width', ($iAnchura - 34) . "px");
        }
        //$this->setStyle('#contenido', 'width', '800px');
        $this->setStyle('#contenido', 'height', $iAlturaCont . 'px');
        $this->setStyle('#contenido', 'overflow', 'none');*/
        $this->setStyle('#contenido', 'background-color', $sFondoPrimario);
        $this->setStyle('#contenido', 'width', '100%');
        $this->setStyle('#contenido', 'margin-top', '50px');


        /**************
         * Contenedor *
         **************/

        /**
         * Contenedor: Aqui definimos las propiedades de la division principal, donde se mostrarn
         * los contenidos. Propiedades: anchura, altura, posicin, mrgenes, color, tipo
         * de letra. El calculo esta hecho arriba.
         */

        /*$this->setStyle('#contenedor', 'position', 'absolute');
        $this->setStyle('#contenedor', 'top', '0px');
        $this->setStyle('#contenedor', 'left', '0px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#contenedor', 'width', ($iAnchura - 40) . "px");
        } else {
            $this->setStyle('#contenedor', 'width', ($iAnchura - 34) . "px");
        }
        $this->setStyle('#contenedor', 'height', $iAlturaCont . 'px');*/
        $this->setStyle('#contenedor', 'font-size', '100%');
        $this->setStyle('#contenedor', 'color', '#575756');
        //$this->setStyle('#contenedor', 'overflow', 'auto');
        $this->setStyle('#contenedor', 'background-color', $sFondoSecundario);
        $this->setStyle('#contenedor', 'width', '100%');


        /**************************************
         * Diveditor: Division para el editor *
         **************************************/

        /*$this->setStyle('#diveditor', 'position', 'absolute');
        $this->setStyle('#diveditor', 'top', '0px');
        $this->setStyle('#diveditor', 'left', '0px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#diveditor', 'width', ($iAnchura - 40) . "px");
        } else {
            $this->setStyle('#diveditor', 'width', ($iAnchura - 34) . "px");
        }
        $this->setStyle('#diveditor', 'height', $iAlturaCont . 'px');*/
        $this->setStyle('#diveditor', 'visibility', 'hidden');
        $this->setStyle('#diveditor', 'overflow', 'hidden');
        $this->setStyle('#diveditor', 'width', '100%');

        /****************************
         * Clases dentro de los formularios
         ****************************/

        /***********
         * Logins  *
         ***********/

        $this->setStyle('.parrafo', 'font-family', '"Arial", sans-serif');
        $this->setStyle('.parrafo', 'color', '#797979');
        $this->setStyle('.parrafo', 'font-weight', 'normal');
        $this->setStyle('.parrafo', 'font-size', '9pt');
        $this->setStyle('.parrafo', 'text-align', 'center');
        $this->setStyle('.parrafo', 'margin-bottom', '35px');

        /**********
         * Logins *
         **********/

        //$this->setStyle('.formulario1', 'color', $sColorTextoSecundario);
        $this->setStyle('.formulario1', 'font-family', '"Arial", sans-serif');
        $this->setStyle('.formulario1', 'font-size', '7pt');
        $this->setStyle('.formulario1', 'max-width', '450px');
        $this->setStyle('.formulario1', 'padding', '20px 10px');
        $this->setStyle('.formulario1', 'margin-top', '20px');
        $this->setStyle('.formulario1', 'border-top', '1px dotted #ccc');
        $this->setStyle('.formulario1', 'border-bottom', '1px dotted #ccc');
        /*if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.formulario1', 'width', ($iAnchura - 57) . "px");
        } else {
            $this->setStyle('.formulario1', 'width', ($iAnchura - 51) . "px");
        }*/

        /**
         * Identificacion pertenece al FORM que hay dentro de #centra
         */


        /**
         * Esto modifica las letras que van al lado de las cajas de login y password
         */

        $this->setStyle('#Identificacion table tbody tr td b', 'font-size', '100%');


        $this->setStyle('#Formulario b', 'color', $sColorSecundario);
        /****************************************
         * LOGOS DEL INICIO                        *
         ****************************************/
        //$this->setStyle('#logos', 'position', 'absolute');
        //$this->setStyle('#logos', 'top', '600px');
        //$this->setStyle('#logos', 'left', '25%');
        //$this->setStyle('#logos', 'float', 'left');
        $this->setStyle('#logos', 'width', '100%');
        $this->setStyle('#logos', 'height', '50px');
        $this->setStyle('#logos', 'text-align', 'center');
        $this->setStyle('#logos', 'margin-top', '100px');
        $this->setStyle('#logos', 'background-color', '#393938');
        $this->setStyle('#logos', 'box-shadow', '0px -2px 2px 0px rgba(0,0,0,0.2)');
        $this->setStyle('#logos', '-moz-box-shadow', '0px -2px 2px 0px rgba(0,0,0,0.2)');
        $this->setStyle('#logos', '-webkit-box-shadow', '0px -2px 2px 0px rgba(0,0,0,0.2)');
        $this->setStyle('#logos', 'position', 'fixed');
        $this->setStyle('#logos', 'bottom', '0');
        $this->setStyle('#logos img', 'padding', '10px');
        $this->setStyle('#logos img', 'max-width', '120px');

        /***************************
         * Listado de Formularios  *
         ***************************/

        /*$this->setStyle('#diviframe', 'position', 'absolute');
        $this->setStyle('#diviframe', 'top', '0px');
        $this->setStyle('#diviframe', 'left', '0px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#diviframe', 'width', ($iAnchura - 40) . "px");
        } else {
            $this->setStyle('#diviframe', 'width', ($iAnchura - 34) . "px");
        }
        $this->setStyle('#diviframe', 'height', $iAlturaCont . 'px');*/
        $this->setStyle('#diviframe', 'color', $sColorPrimario);
        $this->setStyle('#diviframe', 'visibility', 'visible');
        $this->setStyle('#diviframe', 'overflow', 'auto');
        $this->setStyle('#diviframe', 'width', '100%');

        /**
         * Mensajes
         */

        $this->setStyle('.titulo', 'font-size', '8pt');
        $this->setStyle('.titulo', 'font-family', '"verdana", sans-serif');
        $this->setStyle('.titulo', 'color', $sColorTextoSecundario);
        $this->setStyle('.titulo', 'font-weight', 'bold');
        $this->setStyle('.titulo', 'overflow', 'visible');
        $this->setStyle('.titulo', 'text-align', 'justify');

        $this->setStyle('.texto', 'font-size', '8pt');
        $this->setStyle('.texto', 'font-family', '"verdana", sans-serif');
        $this->setStyle('.texto', 'color', $sColorTextoSecundario);
        $this->setStyle('.texto', 'overflow', 'visible');
        $this->setStyle('.texto', 'text-align', 'justify');

        /*****************************************
         * Menu lateral-> atributos de los nodos *
         *****************************************/

        $this->setStyle('.nodo1', 'font-size', '8pt');
        $this->setStyle('.nodo2', 'font-size', '8pt');

        $this->setStyle('.nodo1', 'font-family', '"verdana", sans-serif');
        $this->setStyle('.nodo2', 'font-family', '"verdana", sans-serif');


        $this->setStyle('.nodo1', 'color', $sColorTextoPrimario);
        $this->setStyle('.nodo1', 'font-weight', 'bold');
        $this->setStyle('.nodo1', 'overflow', 'visible');
        $this->setStyle('.nodo1', 'text-align', 'center');
        $this->setStyle('.nodo1:link', 'cursor', 'default');

        $this->setStyle('.nodo2', 'color', $sColorTextoSecundario);
        $this->setStyle('.nodo2', 'font-weight', 'bold');
        $this->setStyle('.nodo2', 'overflow', 'visible');
        $this->setStyle('.nodo2', 'text-align', 'center');


        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.nodo1', 'cursor', 'default');
            $this->setStyle('.nodo2', 'cursor', 'hand');

        } else {
            $this->setStyle('.nodo1', 'cursor', 'default');
            $this->setStyle('.nodo2', 'cursor', 'pointer');
            $this->setStyle('.nodo2:hover', 'color', $sColorTextoPrimario);
            $this->setStyle('.nodo2:hover span', 'text-decoration', 'underline');
        }


        /***********************************
         * Hovers del submenu para el IE y calendario *
         ***********************************/


        if ($sBrowser == "Microsoft Internet Explorer") {
            //Para los links de bsqueda
            $this->setStyle('b.encima', 'cursor', 'hand');

            //Para los listados
            $this->setStyle('b.encima_barra', 'color', $sColorTerciario);
            $this->setStyle('b.encima_barra', 'cursor', 'hand');
            $this->setStyle('b.fuera_barra', 'color', $sColorTextoPrimario);
            $this->setStyle('th', 'cursor', 'hand');

            //Para el calendario anual
            $this->setStyle('b.encima_casilla', 'cursor', 'hand');
            $this->setStyle('td.encima_casilla', 'cursor', 'hand');

        } else {
            //Para los links de bsqueda
            $this->setStyle('b.encima', 'cursor', 'pointer');

            //Para los listados
            $this->setStyle('.tablag tbody tr th b:hover', 'color', $sColorTerciario);
            $this->setStyle('.tablag tbody tr th b:hover', 'cursor', 'pointer');
            $this->setStyle('th', 'cursor', 'pointer');

            //Para el calendario anual

            $this->setStyle('b.encima_casilla', 'cursor', 'pointer');
            $this->setStyle('td.encima_casilla', 'cursor', 'pointer');
        }


        //Por defecto en el calendario
        $this->setStyle('.calendar th', 'cursor', 'default');
        $this->setStyle('.calendar', 'cursor', 'default');
        $this->setStyle('.agno', 'cursor', 'default');
        $this->setStyle('.calendar th', 'border', '1px solid ' . $sColorTextoSecundario);


        /***********
         * Tablas  *
         ***********/
        $this->setStyle('#tablas', 'overflow', 'auto');
        $this->setStyle('#tablas', 'position', 'relative');
        $this->setStyle('#tablas', 'top', '20px');
        $this->setStyle('#tablas', 'left', '0px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#tablas', 'width', ($iAnchura - 40) . 'px');
        } else {
            $this->setStyle('#tablas', 'width', ($iAnchura - 36) . 'px');
        }
        //$this->setStyle('#tablas', 'height', ($iAlturaCont - 35).'px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#tablas', 'height', ($iAlturaCont - 35) . 'px');
        } else {
            $this->setStyle('#tablas', 'height', ($iAlturaCont - 35) . 'px');
        }

        $this->setStyle('.tablag', 'background-color', $sColorPrimario);
        $this->setStyle('.tablag', 'overflow', 'auto');
        $this->setStyle('.tablag', 'font-family', '"Arial", sans-serif');
        $this->setStyle('.tablag', 'width', 'auto');
        $this->setStyle('.tablag', 'height', 'auto');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.tablag', 'font-size', '100%'); //afecta a la table entera IE
            $this->setStyle('.tablag tbody tr th', 'font-size', '100%'); //Titulos de las tablas
            $this->setStyle('.tablag tbody tr th b', 'text-decoration', 'none');
            $this->setStyle('.tablag', 'border-collapse', 'collapse');
            $this->setStyle('.tablag tbody td', 'font-size', '100%');
        } else {
            $this->setStyle('.tablag tbody td', 'font-size', '100%');
            $this->setStyle('.tablag tbody', 'border-collapse', 'collapse');
            $this->setStyle('.tablag', 'border-collapse', 'collapse');
        }

        $this->setStyle('.tablag tbody', 'margin-left', '0');
        //$this->setStyle('.tablag tbody', 'overflow', 'scroll');
        $this->setStyle('.tablag tbody', 'text-align', 'left');
        $this->setStyle('.tablag tbody tr td b', 'font-weight', 'normal'); //Filas
        //$this->setStyle('.tablag tbody tr', 'border-bottom', '1px dashed', $sColorTextoTerciario);
        $this->setStyle('.tablag td', 'color', $sColorTextoTerciario);
        $this->setStyle('.tablag td', 'padding-top', '1px');
        $this->setStyle('.tablag td', 'padding-bottom', '1px');
        $this->setStyle('.tablag td', 'padding-left', '15px');
        $this->setStyle('.tablag td', 'padding-right', '15px');


        $this->setStyle('.tablag th', 'font-size', '100%');
        $this->setStyle('.tablag th', 'padding-left', '15px');
        $this->setStyle('.tablag th', 'padding-right', '15px');
        $this->setStyle('.tablag th', 'background-color', $sCabeceraTabla);
        $this->setStyle('.tablag th', 'text-transform', 'uppercase');
        $this->setStyle('.tablag th', 'color', $sColorTextoPrimario);
        $this->setStyle('.tablag th', 'overflow', 'hidden');

        $this->setStyle('#contenedor .subtabla', 'font-weight', 'bold');
        $this->setStyle('#contenedor .subtabla', 'margin-top', '2px');
        $this->setStyle('#contenedor .subtabla', 'margin-bottom', '2px');
        $this->setStyle('#contenedor .subtabla', 'color', $sColorSecundario);

        $this->setStyle('#contenedor.subtabla', 'overflow', 'hidden');
        $this->setStyle('#contenedor.subtabla', 'width', ($iAnchoContenedor) . 'px');

        $this->setStyle('#contenedor.busqueda', 'font-size', '100%');
        $this->setStyle('#contenedor.busqueda', 'font-family', '"Arial", sans-serif');
        $this->setStyle('#contenedor.busqueda', 'font-weight', 'bold');
        $this->setStyle('.busqueda', 'margin-top', '0.6%');
        $this->setStyle('#contenedor.busqueda', 'overflow', 'hidden');

        $this->setStyle('#contenedor.busqueda', 'color', $sColorTerciario);
        $this->setStyle('#contenedor.busqueda', 'width', ($iAnchoContenedor - 10) . 'px');

        $this->setStyle('.subtabla', 'font-size', '100%');

        $this->setStyle('.subtabla table tbody tr', 'font-weight', 'normal');
        $this->setStyle('.subtabla table tbody tr', 'font-family', '"Arial", sans-serif');
        $this->setStyle('.subtabla table tbody tr', 'font-size', '100%');

        $this->setStyle('.subtabla center tbody', 'font-family', 'Arial');
        $this->setStyle('.subtabla center tbody', 'font-size', '100%');

        /**
         * Fuente de la tabla
         */

        /**
         * Filas que se alternan
         */

        $this->setStyle('.tablag .filapar', 'background-color', $sFilaPar);
        $this->setStyle('.tablag .filaimpar', 'background-color', $sFilaImpar);

        /********************
         * Calendario Anual *
         ********************/

        $this->setStyle('#cal_grande', 'width', ($iAnchoCalendario) . 'px');
        $this->setStyle('#cal_grande', 'position', 'relative');
        $this->setStyle('#cal_grande', 'top', '0px');
        $this->setStyle('#cal_grande', 'left', '0px');
        $this->setStyle('#cal_grande', 'overflow', 'hidden');
        $this->setStyle('#cal_grande .anual', 'margin-left', '0');
        $this->setStyle('#cal_grande .anual', 'max-height', '30px');
        $this->setStyle('#cal_grande .anual', 'width', ($iAnchoCalendario) . 'px');
        $this->setStyle('#cal_grande .anual', 'overflow', 'hidden');
        $this->setStyle('#cal_grande .anual', 'top', '20px');
        $this->setStyle('#cal_grande .anual', 'font-size', '100%');
        $this->setStyle('#cal_grande .anual', 'position', 'absolute');
        $this->setStyle('#cal_grande .dias caption', 'font-weight', 'bold');
        $this->setStyle('#cal_grande .dias', 'text-align', 'center');
        $this->setStyle('#cal_grande .dias', 'position', 'relative');
        $this->setStyle('#cal_grande .dias', 'top', '45px');


        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#contenedor .anual', 'font-size', '90%');
            $this->setStyle('#contenedor .dias tr', 'font-size', '70%');
            $this->setStyle('#contenedor .dias th', 'font-size', '70%');
            $this->setStyle('#contenedor .dias td', 'font-size', '70%');
            $this->setStyle('#contenedor .dias caption', 'font-family', '"helvetica", sans-serif');
            $this->setStyle('#contenedor .dias caption', 'font-size', '60%');
            $this->setStyle('#cal_grande .dias', 'margin-bottom', '5%');
            $this->setStyle('#cal_grande .dias', 'margin-left', '10%');
            //en segun que explorer, sale diferente
        } else {
            $this->setStyle('#cal_grande .dias', 'margin-left', '8%');
            $this->setStyle('#contenedor .dias caption', 'font-size', '90%');
            $this->setStyle('#cal_grande .dias', 'margin-bottom', '10%');
        }


        //-------------------------------------------------------------------------------

        /***************************
         * Id central: Formularios *
         ***************************/

        //$this->setStyle('#central', 'top', '176px');
        //$this->setStyle('#central', 'left', '18px');
        //$this->setStyle('#central', 'position', 'absolute');
        $this->setStyle('#central', 'color', $sColorPrimario);
        $this->setStyle('#central', 'font-size', '100%');
        $this->setStyle('#central','width','100%');
        /*if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#central', 'width', ($iAnchura - 57) . "px");
        } else {
            $this->setStyle('#central', 'width', ($iAnchura - 51) . "px");
        }
        /**if ($sBrowser == "Microsoft Internet Explorer") {
         * $this->setStyle('#central','height', ($iAlturaCont - 17)."px");
         * }
         * else
         * {
         * $this->setStyle('#central','height', ($iAlturaCont - 21)."px");
         * }*/
        //$this->setStyle('#central', 'height', '100%');
        //$this->setStyle('#central', 'padding', '10px');

        /*******************************
         * Div: formularios del iframe *
         *******************************/

        $this->setStyle('#form', 'color', $sColorPrimario);
        $this->setStyle('#form', 'top', '0px');
        $this->setStyle('#form', 'left', '0px');
        $this->setStyle('#form', 'position', 'relative');
        $this->setStyle('#form', 'overflow', 'auto');
        $this->setStyle('#form', 'font-size', '8pt');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#form', 'height', ($iAlturaCont - 17) . "px");
        } else {
            $this->setStyle('#form', 'height', ($iAlturaCont - 21) . "px");
        }
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#form', 'width', ($iAnchura - 57) . "px");
        } else {
            $this->setStyle('#form', 'width', ($iAnchura - 51) . "px");
        }
        $this->setStyle('#form', 'z-index', '2000');


        $this->setStyle('#formulario', 'overflow', 'auto');
        $this->setStyle('#formulario', 'color', '#3399CC');
        $this->setStyle('#formulario', 'font-weight', '    regular');
        $this->setStyle('#formulario', 'font-family', '"Arial", sans-serif');
        $this->setStyle('#formulario', 'position', 'relative');
        $this->setStyle('#formulario', 'top', '20px');
        $this->setStyle('#formulario', 'left', '5px');
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#formulario', 'height', ($iAlturaCont - 20) . "px");
        } else {
            $this->setStyle('#formulario', 'height', ($iAlturaCont - 61) . "px");
        }
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#formulario', 'width', ($iAnchura - 67) . "px");
        } else {
            $this->setStyle('#formulario', 'width', ($iAnchura - 61) . "px");
        }

        $this->setStyle('#formulario', 'padding', '10px');

        //$this->setStyle('.quickform', 'overflow', 'scroll');
        $this->setStyle('.quickform', 'font-size', '8pt');
        $this->setStyle('.quickform', 'overflow', 'auto');
        $this->setStyle('.quickform', 'top', '0px');
        $this->setStyle('.quickform', 'left', '0px');
        $this->setStyle('.quickform', 'color', '#BE1622');
        $this->setStyle('.quickform input tbody tr td', 'background-color', $sColorTerciario);
        $this->setStyle('.quickform input tbody tr td', 'color', $sColorTextoSecundario);
        $this->setStyle('.quickform tbody tr td', 'color', '#BE1622');
        $this->setStyle('.quickform tbody tr td', 'padding', '10px 0');
        $this->setStyle('.quickform tbody tr td', 'font-size', '9pt');
        $this->setStyle('.quickform tbody tr td b', 'font-family', '"Arial", sans-serif');
        $this->setStyle('.quickform tbody tr td b', 'font-weight', 'normal');
        $this->setStyle('.quickform tbody tr td b', 'text-transform', 'uppercase');
        $this->setStyle('.quickform tbody tr td b', 'letter-spacing', '0.1em');
        $this->setStyle('.quickform tbody tr td b', 'padding-right', '20px');
        $this->setStyle('.quickform tbody tr td b', 'padding-top', '7px');
        $this->setStyle('.quickform select', 'width', '100%');
        $this->setStyle('.quickform select', 'background-image', 'url("images/arrow-down.png")');
        $this->setStyle('.quickform select', 'background-repeat', 'no-repeat');
        $this->setStyle('.quickform select', 'background-attachment', 'scroll');
        $this->setStyle('.quickform select', 'background-position', 'center right');
        $this->setStyle('.quickform select,.quickform input', 'padding', '5px');
        $this->setStyle('.quickform select,.quickform input', 'appearance', 'none');
        $this->setStyle('.quickform select,.quickform input', '-moz-appearance', 'none');
        $this->setStyle('.quickform select,.quickform input', '-webkit-appearance', 'none');
        $this->setStyle('.quickform select,.quickform input', 'border-radius', '2px');
        $this->setStyle('.quickform select,.quickform input', 'border', '1px solid #ccc');

        $this->setStyle('#header', 'align', 'center');
        $this->setStyle('#header', 'background-color', $sFondoPrimario);
        $this->setStyle('#header', 'white-space', 'nowrap');
        $this->setStyle('#header', 'border', 'none');
        $this->setStyle('#header', 'font-size', '110%');
        $this->setStyle('#header', 'font-weight', 'bold');
        $this->setStyle('#header', 'color', $sColorTextoSecundario);


        $this->setStyle('#Formulario table tbody', 'font-family', '"Arial", sans-serif');
        $this->setStyle('#Formulario table tbody', 'font-weight', 'bold'); //lighter
        $this->setStyle('#Formulario b', 'color', $sColorSecundario);

        /***********************
         * Calendario mensual  *
         ***********************/

        $this->setStyle('#divcalendario', 'color', '#66377e');
        $this->setStyle('#divcalendario', 'font-family', '"Arial", sans-serif');

        $this->setStyle('#divcalendario', 'top', '60px');
        $this->setStyle('#divcalendario', 'font-size', '90%');
        $this->setStyle('.mensual', 'font-size', '100%');
        $this->setStyle('.nombre_mes', 'font-size', '100%');
        $this->setStyle('.hoy', 'font-size', '100%');
        $this->setStyle('.mes', 'font-size', '100%');
        $this->setStyle('#divcalendario', 'position', 'absolute');
        $this->setStyle('#divcalendario', 'right', '60px');
        $this->setStyle('#divcalendario', 'width', '200px');

        $this->setStyle('#divcalendario', 'background-attachment', 'fixed');

        $this->setStyle('.nombre_mes', 'font-weight', 'bold');
        $this->setStyle('.nombre_mes', 'text-align', 'center');

        $this->setStyle('.hoy', 'background', $sColorSecundario);
        $this->setStyle('.hoy', 'color', $sColorPrimario);

        $this->setStyle('.mensual', 'background', $sColorTerciario);

        /*
         * Cursores del calendario mensual
             */

        $this->setStyle('.mes tbody tr th', 'cursor', 'default');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.hoy tr td center b', 'cursor', 'hand');
            $this->setStyle('.mes tbody tr td center b', 'cursor', 'hand');
        } else {
            $this->setStyle('.hoy tr td center b', 'cursor', 'pointer');
            $this->setStyle('.mes tbody tr td center b', 'cursor', 'pointer');
        }

        /*******************************
         * Nodos del rbol de permisos *
         *******************************/

        /*
         * Nodo1
         */

        $this->setStyle('.a1', 'color', $sColorSecundario);
        $this->setStyle('.a1', 'font-size', '100%');
        $this->setStyle('.a1', 'font-weight', 'bolder');

        $this->setStyle('.a1', 'font-family', '"verdana", sans-serif');

        if ($sBrowser != "Microsoft Internet Explorer") {
            $this->setStyle('.a1', 'cursor', 'default');
            $this->setStyle('.a2', 'cursor', 'default');
            $this->setStyle('.a3', 'cursor', 'default');
        } else {
            $this->setStyle('#arbol.a1', 'cursor', 'default');
            $this->setStyle('#arbol.a2', 'cursor', 'default');
            $this->setStyle('#arbol.a3', 'cursor', 'default');
        }

        /*
         * Nodo2
         */

        $this->setStyle('.a2', 'color', $sColorSecundario);
        $this->setStyle('.a2', 'font-size', '100%');
        $this->setStyle('.a2', 'font-weight', 'bold');

        $this->setStyle('.a2', 'font-family', '"verdana", sans-serif');

        /*
         * Nodo3
         */

        $this->setStyle('.a3', 'color', $sColorTerciario);
        $this->setStyle('.a3', 'font-size', '100%');
        $this->setStyle('.a3', 'font-weight', 'bold');

        $this->setStyle('.a3', 'font-family', '"verdana", sans-serif');

        if ($sBrowser != "Microsoft Internet Explorer") {
            $this->setStyle('div nobr input', 'cursor', 'pointer');
        } else {
            $this->setStyle('div nobr input', 'cursor', 'hand');
        }

        /**
         * IDs para cambiar el color de los nodos del arbol segun su des/activacion
         */

        $this->setStyle('.enabled', 'color', '#582f6c');
        $this->setStyle('.disabled', 'color', '#CCCCCC');

        /*******************************
         * Contenido de los documentos *
         *******************************/

        $this->setStyle('#documentacion', 'width', $iAnchoContenedor . 'px');
        $this->setStyle('#documentacion', 'overflow', 'hidden');
        $this->setStyle('#documentacion', 'margin-bottom', '5%');
        $this->setStyle('#documentacion', 'padding-bottom', '5%');

        $this->setStyle('#documentacion', 'color', $sColorTextoPrimario);


        $this->setStyle('.documento', 'color', $sColorSecundario);
        $this->setStyle('.documento', 'font-size', '100%');
        $this->setStyle('.documento td', 'width', ($iAnchoContenedor / 3) . 'px');
        $this->setStyle('.documento tbody td', 'padding-left', '5px');
        $this->setStyle('.documento tbody td', 'padding-bottom', '5px');

        if ($sBrowser == "Netscape") {
            $this->setStyle('.cuerpo_doc', 'font-size', '100%');
        } else {
            $this->setStyle('.cuerpo_doc', 'font-size', '100%');
            $this->setStyle('.cuerpo_doc a', 'font-size', '100%');
        }

        $this->setStyle('.campo', 'color', $sColorSecundario);
        $this->setStyle('.campo', 'font-weight', 'bold');
        $this->setStyle('.doc_largo', 'width', '100%');


        $this->setStyle('.ver_docs td', 'padding', '15px');
        $this->setStyle('.ver_docs td', 'color', 'black');

        $this->setStyle('.alineado1', 'text-align', 'right');
        $this->setStyle('.alineado2', 'text-align', 'left');

        //-----------------------------

        /***********
         * Botones *
         ***********/


        //$this->setStyle('.b_activo', 'background-image', 'url("images/Patron_botones.gif")');
        //$this->setStyle('.b_activo', 'background-repeat', 'repeat-x');
        //$this->setStyle('.b_activo', 'background-attachment', 'scroll');
        //$this->setStyle('.b_activo', 'background-position', 'top');
        $this->setStyle('.b_activo', 'color', '#F0F0F0');
        $this->setStyle('.b_activo', 'font-weight', 'normal');
        $this->setStyle('.b_activo', 'background-color', '#BE1622');
        $this->setStyle('.b_activo', 'width', 'auto');
        $this->setStyle('.b_activo', 'min-width', '8em');
        $this->setStyle('.b_activo', 'cursor', 'pointer');
        $this->setStyle('.b_activo', 'display', 'inline-block');
        $this->setStyle('.b_activo', 'text-transform', 'uppercase');
        $this->setStyle('.b_activo', 'padding', '10px 0');
        $this->setStyle('.b_activo', 'border-radius', '3px');
        $this->setStyle('.b_activo', 'border', 'none');
        $this->setStyle('.b_activo', 'box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('.b_activo', '-moz-box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('.b_activo', '-webkit-box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('.b_activo:hover', 'background-color', '#575756');

        $this->setStyle('#contenido .b_activo', 'color', '#F0F0F0');
        $this->setStyle('#contenido .b_activo', 'font-weight', 'normal');
        $this->setStyle('#contenido .b_activo', 'background-color', '#BE1622');
        $this->setStyle('#contenido .b_activo', 'width', '250px');
        $this->setStyle('#contenido .b_activo', 'cursor', 'pointer');
        $this->setStyle('#contenido .b_activo', 'text-transform', 'uppercase');
        $this->setStyle('#contenido .b_activo', 'padding', '10px 0!important');
        $this->setStyle('#contenido .b_activo', 'border-radius', '3px!important');
        $this->setStyle('#contenido .b_activo', 'border', 'none!important');
        $this->setStyle('#contenido .b_activo', 'box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('#contenido .b_activo', '-moz-box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('#contenido .b_activo', '-webkit-box-shadow', '0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('#contenido .b_activo:hover', 'background-color', '#575756');



        $this->setStyle('.b_inactivo', 'background-image', 'url("images/Patron_botones_inactivo.gif")');
        $this->setStyle('.b_inactivo', 'background-repeat', 'repeat-x');
        $this->setStyle('.b_inactivo', 'background-attachment', 'scroll');
        $this->setStyle('.b_inactivo', 'background-position', 'top');
        //$this->setStyle('.b_inactivo', 'color', '#F0F0F0');
        //$this->setStyle('.b_inactivo', 'background-color', '#393938');
        //$this->setStyle('.b_inactivo', 'box-shadow', 'inset 0px -1px 4px 0px rgba(0,0,0,0.3)');
        //$this->setStyle('.b_inactivo', '-moz-box-shadow', 'inset 0px -1px 4px 0px rgba(0,0,0,0.3)');
        //$this->setStyle('.b_inactivo', '-webkit-box-shadow', 'inset 0px -1px 4px 0px rgba(0,0,0,0.3)');
        $this->setStyle('.b_inactivo', 'visibility', 'hidden');
        $this->setStyle('.b_inactivo', 'border', 'none');

        /*
        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.b_activo', 'color', $sColorTextoSecundario);
            $this->setStyle('.b_activo', 'border', '1px solid #999999');
            $this->setStyle('.b_activo', 'margin', '3px');
            $this->setStyle('.b_activo', 'background-image', 'url("images/Patron_botones_bg.gif")');
            $this->setStyle('.b_activo', 'background-repeat', 'repeat-x');
            $this->setStyle('.b_activo', 'background-attachment', 'scroll');
            $this->setStyle('.b_activo', 'background-position', 'top');

            $this->setStyle('.b_focus', 'color', $sColorTextoTerciario);
            $this->setStyle('.b_focus', 'border', '1px solid #999999');
            $this->setStyle('.b_focus', 'margin', '3px');
            $this->setStyle('.b_focus', 'background-image', 'url("images/Patron_botones.gif")');
            $this->setStyle('.b_focus', 'background-repeat', 'repeat-x');
            $this->setStyle('.b_focus', 'background-attachment', 'scroll');
            $this->setStyle('.b_focus', 'background-position', 'top');

            $this->setStyle('.b_inactivo', 'background-image', 'url("images/Patron_botones_inactivo.gif")');
            $this->setStyle('.b_inactivo', 'background-repeat', 'repeat-x');
            $this->setStyle('.b_inactivo', 'background-attachment', 'scroll');
            $this->setStyle('.b_inactivo', 'background-position', 'top');
            $this->setStyle('.b_inactivo', 'border', '1px solid #999999');
            $this->setStyle('.b_inactivo', 'margin', '3px');
        } else {
            $this->setStyle('.b_activo', 'color', $sColorTextoSecundario);
            $this->setStyle('.b_activo', 'border', '1px solid #999999');
            $this->setStyle('.b_activo', 'margin', '3px');
            $this->setStyle('.b_activo', 'background-image', 'url("images/Patron_botones_bg.gif")');
            $this->setStyle('.b_activo', 'background-repeat', 'repeat-x');
            $this->setStyle('.b_activo', 'background-attachment', 'scroll');
            $this->setStyle('.b_activo', 'background-position', 'top');
            $this->setStyle('.b_activo:hover', 'color', $sColorTextoTerciario);
            $this->setStyle('.b_activo:hover', 'background-image', 'url("images/Patron_botones.gif")');
            $this->setStyle('.b_activo:hover', 'background-repeat', 'repeat-x');
            $this->setStyle('.b_activo:hover', 'background-attachment', 'scroll');
            $this->setStyle('.b_activo:hover', 'background-position', 'top');
        }*/
        $this->setStyle('.b_activo', 'font-size', '100%');
        $this->setStyle('.b_focus', 'font-size', '100%');
        $this->setStyle('.b_inactivo', 'font-size', '100%');


        /*****************************
         * FCKeditor: Medio Ambiente *
         *****************************/

        $this->setStyle('.fckeditor', 'font-weight', 'bold');

        /****************
         * Cuestionario *
         ****************/

        $this->setStyle('.cuestionario', 'width', ($iAnchoContenedor / 2) . 'px');
        $this->setStyle('.cuestionario', 'height', '20px');
        $this->setStyle('.cuestionario', 'color', $sColorSecundario); //#46154F
        $this->setStyle('.cuestionario', 'background-color', $sFondoPrimario);
        $this->setStyle('.cuestionario', 'font-weight', 'bold');
        $this->setStyle('.cuestionario', 'font-family', '"helvetica", sans-serif');
        $this->setStyle('.cuestionario', 'padding-left', '5px');
        $this->setStyle('.cuestionario', 'font-size', '105%');


        /**************************
         * Legislacin: Historico *
         **************************/

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.preguntas', 'font-size', '105%');
        } else {
            $this->setStyle('.preguntas', 'font-size', '105%');
        }

        $this->setStyle('.preguntas', 'width', '50%');
        $this->setStyle('.tpreguntas', 'height', '30px');
        $this->setStyle('.preguntas tbody', 'color', $sColorTextoSecundario);
        $this->setStyle('.tpregunta', 'background-color', $sColorTextoSecundario);
        $this->setStyle('.tpregunta', 'color', $sColorTextoSecundario);
        $this->setStyle('.preguntas', 'border-collapse', 'collapse');
        $this->setStyle('.tpregunta', 'text-align', 'left');

        $this->setStyle('.preguntas.tpregunta.pregunta', 'padding', '0');
        $this->setStyle('.tpregunta', 'text-transform', 'uppercase');
        $this->setStyle('.tpregunta', 'font-weight', 'bold');


        /*********************
         * Revisar Productos *
         *********************/

        $this->setStyle('#criterios', 'cursor', 'default');
        $this->setStyle('#criterios', 'border-collapse', 'collapse');

        $this->setStyle('.tcriterios', 'cursor', 'default');
        $this->setStyle('.tcriterios', 'background-color', '#FFB902');
        $this->setStyle('.tcriterios', 'text-align', 'left');
        $this->setStyle('.tcriterios', 'color', $sColorTextoPrimario);

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#criterios input', 'cursor', 'hand');
            $this->setStyle('#criterios', 'font-size', '95%');
        } else {
            $this->setStyle('#criterios input', 'cursor', 'pointer');
        }

        $this->setStyle('#criterios tbody .filapar', 'background-color', $sFondoPrimario);
        $this->setStyle('#criterios tbody .filaimpar', 'background-color', $sFondoSecundario);

        $this->setStyle('#criterios tbody td', 'padding-left', '5px');
        $this->setStyle('#criterios', 'width', $iAnchoContenedor . 'px');

        $this->setStyle('.homologado', 'background-color', '#0066d9'); //#0000d3
        $this->setStyle('.homologado', 'color', $sColorTextoPrimario);
        $this->setStyle('.homologado', 'width', '500px');
        $this->setStyle('.homologado', 'padding', '2px 0px 2px 2px');
        $this->setStyle('.homologado', 'float', 'right');

        $this->setStyle('.nohomologado', 'float', 'right');
        $this->setStyle('.nohomologado', 'background-color', '#d30000');
        $this->setStyle('.nohomologado', 'color', $sColorTextoPrimario);
        $this->setStyle('.nohomologado', 'width', '500px');
        $this->setStyle('.nohomologado', 'padding', '2px 0px 2px 2px');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.homologado', 'height', '20px');
        } else {
            $this->setStyle('.homologado', 'height', '20px');
        }

        $this->setStyle('.puntuacion', 'color', $sColorTextoPrimario);
        $this->setStyle('.puntuacion', 'height', '25px');
        $this->setStyle('.puntuacion', 'background-color', '#66377e');
        $this->setStyle('.puntuacion', 'width', '500px');
        $this->setStyle('.puntuacion', 'padding', '2px 0px 2px 2px');
        $this->setStyle('.puntuacion', 'float', 'right');


        /***************
         * Documentos  *
         ***************/

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#contenedor .ver_docs', 'font-size', '95%');

            $this->setStyle('.vtitulo', 'top', '0px');
            $this->setStyle('.vtitulo', 'position', 'relative');

            $this->setStyle('.vigente', 'top', '-30px');
            $this->setStyle('.vigente', 'position', 'relative');
            $this->setStyle('.vigente', 'font-size', '95%');

            $this->setStyle('.tborrador', 'position', 'absolute'); //aqui
            $this->setStyle('.borrador', 'font-size', '95%');
            $this->setStyle('.borrador', 'top', '30px');
            $this->setStyle('.borrador', 'position', 'relative');

            $this->setStyle('#mostrar_doc', 'font-size', '95%');

        } else {
            $this->setStyle('.ver_docs', 'font-size', '100%');
        }

        $this->setStyle('#contenedor .vigente tbody tr td', 'padding', '5px 0px 5px 0px');
        $this->setStyle('#contenedor .borrador tbody tr td', 'padding', '6px 0px 6px 0px');

        $this->setStyle('.vtitulo', 'color', $sColorPrimario); //#46154F
        $this->setStyle('.vtitulo', 'background-color', $sColorSecundario);
        $this->setStyle('.vtitulo', 'width', '500px');

        $this->setStyle('.vtitulo', 'font-weight', 'bold');
        $this->setStyle('.vtitulo', 'padding-left', '5px');

        $this->setStyle('.tborrador', 'color', $sColorPrimario);
        $this->setStyle('.tborrador', 'background-color', $sColorSecundario);
        $this->setStyle('.tborrador', 'width', '500px');
        $this->setStyle('.tborrador', 'padding-left', '5px');

        $this->setStyle('.thistorico', 'color', $sColorPrimario);
        $this->setStyle('.thistorico', 'background-color', $sColorSecundario);
        $this->setStyle('.thistorico', 'width', '500px');
        $this->setStyle('.thistorico', 'padding-left', '5px');
        $this->setStyle('.thistorico', 'font-weight', 'bold');

        $this->setStyle('#mostrar_doc hr', 'width', '375px');
        $this->setStyle('#mostrar_doc hr', 'float', 'left');

        /*************
         * Checkbox  *
         *************/

        /*************
         * Procesos  *
         *************/

        $this->setStyle('#proceso', 'color', '#46154F');
        $this->setStyle('#proceso', 'width', $iAnchoContenedor . 'px');

        $this->setStyle('.t_proceso', 'color', '#ffffff');
        $this->setStyle('.t_proceso', 'background-color', '#FFB902');
        $this->setStyle('.t_proceso', 'font-weight', 'bold');
        $this->setStyle('.t_proceso', 'padding-left', '5px');
        $this->setStyle('.t_proceso', 'width', '500px');

        $this->setStyle('.tabla_ficha tr', 'border', '1px solid #dedede');
        $this->setStyle('.tabla_ficha td', 'border', '1px solid #dedede');

        $this->setStyle('.celda', 'width', '35%');
        $this->setStyle('.tabla_ficha', 'border-collapse', 'collapse');
        $this->setStyle('.tabla_ficha', 'width', '100%');


        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.tabla_ficha', 'font-size', '100%');
            $this->setStyle('#proceso.t_proceso', 'color', '#ffffff');
        } else {
            $this->setStyle('.t_proceso', 'color', '#ffffff');
            $this->setStyle('#proceso', 'font-size', '100%');
        }

        $this->setStyle('.tabla_ficha', 'margin', '0');


        /************
         * Equipos  *
         ************/

        $this->setStyle('#ver_equipos td', 'padding', '5px');
        $this->setStyle('#ver_equipos td', 'padding-right', '35px');
        $this->setStyle('#ver_equipos td', 'width', ($iAnchoContenedor / 5) . 'px');


        /***********************
         * Scrollbars para IE  *
         ***********************/

        /*    if($sBrowser == "Microsoft Internet Explorer")
            {
                $this->setStyle('body', 'scrollbar-face-color', '#FFB902');
                $this->setStyle('body', 'scrollbar-shadow-color', '#ffb902');
                $this->setStyle('body', 'scrollbar-highlight-color', '#ffb902');
                $this->setStyle('body', 'scrollbar-3dlight-color', '#ffb902');
                $this->setStyle('body', 'scrollbar-arrow-color', '#0091d5');
                $this->setStyle('body', 'scrollbar-track-color', '#ffb902');
                $this->setStyle('body', 'scrollbar-darkshadow-color', '#FFB902');

                $this->setStyle('#form', 'scrollbar-face-color', '#FFB902');
                $this->setStyle('#form', 'scrollbar-shadow-color', '#ffb902');
                $this->setStyle('#form', 'scrollbar-highlight-color', '#ffb902');
                $this->setStyle('#form', 'scrollbar-3dlight-color', '#ffb902');
                $this->setStyle('#form', 'scrollbar-arrow-color', '#0091d5');
                $this->setStyle('#form', 'scrollbar-track-color', '#ffb902');
                $this->setStyle('#form', 'scrollbar-darkshadow-color', '#FFB902');

                $this->setStyle('.quickform', 'scrollbar-face-color', '#FFB902');
                $this->setStyle('.quickform', 'scrollbar-shadow-color', '#ffb902');
                $this->setStyle('.quickform', 'scrollbar-highlight-color', '#ffb902');
                $this->setStyle('.quickform', 'scrollbar-3dlight-color', '#ffb902');
                $this->setStyle('.quickform', 'scrollbar-arrow-color', '#0091d5');
                $this->setStyle('.quickform', 'scrollbar-track-color', '#ffb902');
                $this->setStyle('.quickform', 'scrollbar-darkshadow-color', '#9a3442');
             }*/

        /**************
         * Auditorias *
         **************/

        $this->setStyle('#auditoria', 'color', '#000000');
        $this->setStyle('#auditoria', 'width', '50%');

        $this->setStyle('.tauditoria', 'padding', '0');
        $this->setStyle('.tauditoria', 'font-size', '110%');
        $this->setStyle('.tauditoria', 'font-weight', 'bold');

        $this->setStyle('.tauditoria', 'color', '#ffffff');
        $this->setStyle('.tauditoria', 'background-color', '#9a3442');
        $this->setStyle('.tauditoria', 'width', '100%');


        $this->setStyle('#auditoria td', 'padding-right', '15px');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#auditoria', 'font-size', '100%');
            $this->setStyle('#auditoria', 'top', '-100px');
            $this->setStyle('#auditoria', 'position', 'absolute');
            $this->setStyle('#auditoria', 'padding', '2%');

            $this->setStyle('.plan_auditoria', 'position', 'relative');
            $this->setStyle('.plan_auditoria', 'top', '-50px');
            $this->setStyle('.plan_auditoria', 'padding', '2%');

            $this->setStyle('.tauditoria', 'margin-bottom', '25px');

        } else {
            $this->setStyle('#auditoria', 'font-size', '100%');
            $this->setStyle('.tauditoria', 'margin-bottom', '50px');
        }


        $this->setStyle('.plan_auditoria', 'color', '#000000');

        $this->setStyle('.plan_auditoria', 'width', '40%');
        $this->setStyle('.plan_auditoria td', 'padding-right', '15px');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.plan_auditoria', 'font-size', '100%');
        } else {
            $this->setStyle('.plan_auditoria', 'font-size', '100%');
        }


        /***************
         * Proveedores *
         ***************/

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.proveedores', 'font-size', '95%');
        } else {
            $this->setStyle('.proveedores', 'font-size', '100%');
        }

        $this->setStyle('.proveedores', 'color', '#000000');
        $this->setStyle('.proveedores', 'width', '90%');
        $this->setStyle('.proveedores.campo', 'color', '#66377e');
        $this->setStyle('.proveedores td', 'padding', '15px');


        /***************
         * Incidencias *
         ***************/


        $this->setStyle('.incidencias td', 'padding', '15px');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.incidencias td', 'font-size', '95%');
        } else {
            $this->setStyle('.incidencias td', 'font-size', '100%');
        }


        /***************
         * Contactos   *
         ***************/

        $this->setStyle('.contactos td', 'padding', '15px');


        /***************
         * Productos   *
         ***************/

        $this->setStyle('.productos td', 'padding', '15px');
        $this->setStyle('.productos campo', 'color', '#66377e');


        /***************
         * Productos   *
         ***************/

        $this->setStyle('.matriz', 'width', '100%');
        $this->setStyle('.matriz', 'font-family', '"verdana", sans-serif');

        $this->setStyle('.matriz', 'border', '1px solid #003399');
        $this->setStyle('.matriz', 'color', '#003399');
        $this->setStyle('.matriz', 'border-collapse', 'collapse');

        $this->setStyle('.matriz td', 'border', '1px solid #3399cc');


        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.matriz', 'font-size', '90%');
        } else {
            $this->setStyle('.matriz', 'font-size', '95%');
        }


        $this->setStyle('.titulos_matriz', 'color', '#ec9800');
        $this->setStyle('.titulos_matriz', 'font-weight', 'bold');

        /***************
         * Tareas       *
         ***************/

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.tareas', 'font-size', '95%');
        } else {
            $this->setStyle('.tareas', 'font-size', '100%');
        }
        $this->setStyle('.tareas', 'color', '#000000');
        $this->setStyle('.tareas td', 'padding', '10px');


        /**********************
         * Imprimir informe      *
         **********************/

        $this->setStyle('.imprimir', 'width', '50%');
        $this->setStyle('.imprimir', 'border', '1px solid #dedede');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.imprimir', 'font-size', '90%');
        } else {
            $this->setStyle('.imprimir', 'font-size', '100%');
        }


        /***************
         * Valores       *
         ***************/

        $this->setStyle('.valores', 'color', '#66377e');

        $this->setStyle('.formsubir', 'color', '#66377e');
        $this->setStyle('.formsubir', 'font-weight', 'bold');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.formsubir', 'font-size', '100%');
        } else {
            $this->setStyle('.formsubir', 'font-size', '100%');
        }

        $this->setStyle('#formsubir', 'color', '#66377e');
        $this->setStyle('#formsubir', 'font-weight', 'bold');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#formsubir', 'font-size', '100%');
        } else {
            $this->setStyle('#formsubir', 'font-size', '100%');
        }
        $this->setStyle('#formsubir', 'width', '100%');
        $this->setStyle('#formsubir', 'height', '100%');
        $this->setStyle('#formsubir', 'z-index', '10000');


        /***************
         * Indicadores *
         ***************/

        $this->setStyle('#indicadores', 'width', '100%');
        $this->setStyle('#indicadores', 'font-family', '"verdana", sans-serif');

        $this->setStyle('#indicadores', 'border', '1px solid #003399');
        $this->setStyle('#indicadores', 'color', '#003399');
        $this->setStyle('#indicadores', 'border-collapse', 'collapse');

        $this->setStyle('#indicadores td', 'border', '1px solid #3399cc');
        $this->setStyle('#indicadores td', 'height', '12px');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('#indicadores', 'font-size', '90%');
        } else {
            $this->setStyle('#indicadores', 'font-size', '90%');
        }


        $this->setStyle('.tit_indicadores', 'color', '#ec9800');
        $this->setStyle('.tit_indicadores', 'font-weight', 'bold');


        /***********
         * Iframes *
         ***********/


        $this->setStyle('#formmedio', 'width', '100%');
        $this->setStyle('#diviframe', 'width', '100%');
        $this->setStyle('#form', 'width', '100%');

        $this->setStyle('#docext', 'width', '100%');


        /***********************
         * rbol de permisos   *
         ***********************/

        // Mirar en IE

        $this->setStyle('#arbol_centro', 'position', 'relative');
        $this->setStyle('#arbol_centro', 'top', '0px');
        $this->setStyle('#arbol_centro', 'margin-left', '38%');
        $this->setStyle('#arbol_centro', 'overflow', 'auto');

        $this->setStyle('#arbol', 'position', 'absolute');
        $this->setStyle('#arbol', 'left', '0px');

        $this->setStyle('#centra_botones', 'text-align', 'center');

        $this->setStyle('#arbol', 'top', '20px');

        $this->setStyle('#centra_botones', 'width', '100%');

        $this->setStyle('#arbol', 'width', ($iAnchoContenedor) . 'px');
        $this->setStyle('#arbol', 'height', ($iAltura * 1.8) . 'px');
        $this->setStyle('#arbol', 'overflow', 'auto');
        $this->setStyle('#arbol', 'position', 'absolute');
        $this->setStyle('#arbol', 'top', '0px');
        $this->setStyle('#arbol', 'left', '0px');

        /*        $this->setStyle('#verpermisos', 'position', 'absolute');
                $this->setStyle('#verpermisos', 'top', '0px');
                $this->setStyle('#verpermisos', 'right', '100px');
                $this->setStyle('#verpermisos', 'width', ($iAnchoContenedor/3).'px');
                $this->setStyle('#verpermisos #divcalendario', 'height', ($iAltura*8).'px');
                $this->setStyle('#formulario #verpermisos', 'overflow', 'scroll');*/
        $this->setStyle('.correcto', 'font-size', '20px');

        /*
         * Estilo del formulario de subir ficheros
         */
        $this->setStyle('#subirfichero', 'height', '60%');
        $this->setStyle('#subirfichero', 'width', '60%');
        $this->setStyle('#subirfichero', 'overflow', 'hidden');
        $this->setStyle('#subirfichero', 'margin-left', 'auto');
        $this->setStyle('#subirfichero', 'margin-right', 'auto');
        $this->setStyle('#subirfichero', 'background-color', $sFondoSecundario);
        $this->setStyle('#subirfichero', 'color', $sColorPrimario);
        $this->setStyle('#subirfichero', 'position', 'relative');
        $this->setStyle('#subirfichero', 'top', '20%');

        /*
         *  Dhtml_goodies_ayuda
         */

        $this->setStyle('#divayuda ul', 'padding-left', '20px');
        $this->setStyle('#divayuda ul', 'margin-left', '0px');

        $this->setStyle('#divayuda div', 'padding', '3px');

        $this->setStyle('#divayuda', 'background-color', '#3c94c8'); //azul
        $this->setStyle('#divayuda', 'color', '#FFF');
        $this->setStyle('#divayuda', 'font-family', 'Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif');
        $this->setStyle('#divayuda', 'height', '100%');
        $this->setStyle('#divayuda', 'top', '100%');
        $this->setStyle('#divayuda', 'left', '0px');
        $this->setStyle('#divayuda', 'z-index', '10000');
        $this->setStyle('#divayuda', 'position', 'absolute');
        $this->setStyle('#divayuda', 'display', 'none');

        $this->setStyle('#divayuda #leftPanelContent', 'padding', '0px');

        $this->setStyle('#divayuda .closeLink', 'padding-left', '2px');
        $this->setStyle('#divayuda .closeLink', 'padding-right', '2px');
        $this->setStyle('#divayuda .closeLink', 'background-color', '#FFF');
        $this->setStyle('#divayuda .closeLink', 'position', 'absolute');
        $this->setStyle('#divayuda .closeLink', 'top', '2px');
        $this->setStyle('#divayuda .closeLink', 'right', '2px');
        $this->setStyle('#divayuda .closeLink', 'border', '1px solid #000');
        $this->setStyle('#divayuda .closeLink', 'color', '#000');
        $this->setStyle('#divayuda .closeLink', 'font-size', '0.8em');

        $this->setStyle('#divayuda .closeLink:hover', 'color', '#FFF');
        $this->setStyle('#divayuda .closeLink:hover', 'background-color', '#000');


        /*
         * Dhtml_goodies_calendar
         */
        $this->setStyle('#calendarDiv', 'position', 'absolute');
        $this->setStyle('#calendarDiv', 'width', '205px');
        $this->setStyle('#calendarDiv', 'border', '1px solid #317082');
        $this->setStyle('#calendarDiv', 'padding', '1px');
        $this->setStyle('#calendarDiv', 'background-color', '#FFF');
        $this->setStyle('#calendarDiv', 'font-family', 'arial');
        $this->setStyle('#calendarDiv', 'font-size', '10px');
        $this->setStyle('#calendarDiv', 'padding-bottom', '20px');
        $this->setStyle('#calendarDiv', 'visibility', 'hidden');

        $this->setStyle('#calendarDiv span,#calendarDiv img', 'float', 'left');

        $this->setStyle('#calendarDiv .selectBox,#calendarDiv .selectBoxOver', 'line-height', '12px');
        $this->setStyle('#calendarDiv .selectBox,#calendarDiv .selectBoxOver', 'padding', '1px');
        $this->setStyle('#calendarDiv .selectBox,#calendarDiv .selectBoxOver', 'cursor', 'pointer');
        $this->setStyle('#calendarDiv .selectBox,#calendarDiv .selectBoxOver', 'padding-left', '2px');


        $this->setStyle('#calendarDiv .selectBoxTime,#calendarDiv .selectBoxTimeOver', 'line-height', '12px');
        $this->setStyle('#calendarDiv .selectBoxTime,#calendarDiv .selectBoxTimeOver', 'padding', '1px');
        $this->setStyle('#calendarDiv .selectBoxTime,#calendarDiv .selectBoxTimeOver', 'cursor', 'pointer');
        $this->setStyle('#calendarDiv .selectBoxTime,#calendarDiv .selectBoxTimeOver', 'padding-left', '2px');
        $this->setStyle('#calendarDiv td', 'padding', '2px');
        $this->setStyle('#calendarDiv td', 'margin', '0px');
        $this->setStyle('#calendarDiv td', 'font-size', '10px');

        $this->setStyle('#calendarDiv .selectBox', 'border', '1px solid #E2EBED');
        $this->setStyle('#calendarDiv .selectBox', 'color', '#E2EBED');
        $this->setStyle('#calendarDiv .selectBox', 'position', 'relative');

        $this->setStyle('#calendarDiv .selectBoxOver', 'border', '1px solid #FFF');
        $this->setStyle('#calendarDiv .selectBoxOver', '    background-color', '#317082');
        $this->setStyle('#calendarDiv .selectBoxOver', 'color', '#FFF');
        $this->setStyle('#calendarDiv .selectBoxOver', 'position', 'relative');

        $this->setStyle('#calendarDiv .selectBoxTime', 'border', '1px solid #317082');
        $this->setStyle('#calendarDiv .selectBoxTime', 'color', '#317082');
        $this->setStyle('#calendarDiv .selectBoxTime', 'position', 'relative');

        $this->setStyle('#calendarDiv .selectBoxTimeOver', 'border', '1px solid #216072');
        $this->setStyle('#calendarDiv .selectBoxTimeOver', 'color', '#216072');
        $this->setStyle('#calendarDiv .selectBoxTimeOver', 'position', 'relative');

        $this->setStyle('#calendarDiv .topBar', 'height', '16px');
        $this->setStyle('#calendarDiv .topBar', 'padding', '2px');
        $this->setStyle('#calendarDiv .topBar', 'background-color', '#317082');
        $this->setStyle('#calendarDiv .activeDay', 'color', '#FF0000');

        $this->setStyle('#calendarDiv .todaysDate', 'height', '17px');
        $this->setStyle('#calendarDiv .todaysDate', 'line-height', '17px');
        $this->setStyle('#calendarDiv .todaysDate', 'padding', '2px');
        $this->setStyle('#calendarDiv .todaysDate', 'background-color', '#E2EBED');
        $this->setStyle('#calendarDiv .todaysDate', 'text-align', 'center');
        $this->setStyle('#calendarDiv .todaysDate', 'position', 'absolute');
        $this->setStyle('#calendarDiv .todaysDate', 'bottom', '0px');
        $this->setStyle('#calendarDiv .todaysDate', 'width', '201px');

        $this->setStyle('#calendarDiv .todaysDate div', 'float', 'left');

        $this->setStyle('#calendarDiv .timeBar', 'height', '17px');
        $this->setStyle('#calendarDiv .timeBar', 'line-height', '17px');
        $this->setStyle('#calendarDiv .timeBar', 'background-color', '#E2EBED');
        $this->setStyle('#calendarDiv .timeBar', 'width', '72px');
        $this->setStyle('#calendarDiv .timeBar', 'color', '#FFF');
        $this->setStyle('#calendarDiv .timeBar', 'position', 'absolute');
        $this->setStyle('#calendarDiv .timeBar', 'right', '0px');

        $this->setStyle('#calendarDiv .timeBar div', 'float', 'left');
        $this->setStyle('#calendarDiv .timeBar div', 'margin-right', '1px');


        $this->setStyle('#calendarDiv .monthYearPicker', 'background-color', '#E2EBED');
        $this->setStyle('#calendarDiv .monthYearPicker', 'border', '1px solid #AAAAAA');
        $this->setStyle('#calendarDiv .monthYearPicker', 'position', 'absolute');
        $this->setStyle('#calendarDiv .monthYearPicker', 'color', '#317082');
        $this->setStyle('#calendarDiv .monthYearPicker', 'left', '0px');
        $this->setStyle('#calendarDiv .monthYearPicker', 'top', '15px');
        $this->setStyle('#calendarDiv .monthYearPicker', 'z-index', '1000');
        $this->setStyle('#calendarDiv .monthYearPicker', 'display', 'none');

        $this->setStyle('#calendarDiv #monthSelect', 'width', '70px');

        $this->setStyle('#calendarDiv .monthYearPicker div', 'float', 'none');
        $this->setStyle('#calendarDiv .monthYearPicker div', 'clear', 'both');
        $this->setStyle('#calendarDiv .monthYearPicker div', 'padding', '1px');
        $this->setStyle('#calendarDiv .monthYearPicker div', 'margin', '1px');
        $this->setStyle('#calendarDiv .monthYearPicker div', 'cursor', 'pointer');

        $this->setStyle('#calendarDiv .monthYearActive', 'background-color', '#317082');
        $this->setStyle('#calendarDiv .monthYearActive', 'color', '#E2EBED');

        $this->setStyle('#calendarDiv td', 'text-align', 'right');
        $this->setStyle('#calendarDiv td', 'cursor', 'pointer');

        $this->setStyle('#calendarDiv .topBar img', 'cursor', 'pointer');

        $this->setStyle('#calendarDiv .topBar div', 'float', 'left');
        $this->setStyle('#calendarDiv .topBar div', 'margin-right', '1px');
    }
}
