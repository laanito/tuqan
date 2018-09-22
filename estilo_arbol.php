<?php
/*
 * Created on 17-ene-2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once 'HTML/CSS.php';

/**
* LICENSE see LICENSE.md file
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 *
 * Este es la clase que cargará el estilo general de la aplicación.
 * Tendrá un solo constructor que nos devolverá el objeto con el estilo cargado.
 */
class Estilo_Arbol extends HTML_CSS
{

    public function __construct($iAncho, $sBrowser)
    {


        /**
         * Botones
         */


        $this->setStyle('.b_activo', 'background-color', '#FFB902'); //#eb9530
        $this->setStyle('.b_activo', 'color', '#ffffff'); //#0926C6 #02338d


        $this->setStyle('.b_inactivo', 'background-color', '#ffffff');
        $this->setStyle('.b_inactivo', 'color', '#ffffff');
        $this->setStyle('.b_inactivo', 'visibility', 'hidden');
        $this->setStyle('.b_inactivo', 'border', 'none');

        if ($sBrowser == "Microsoft Internet Explorer") {
            $this->setStyle('.b_activo', 'border', 'none');
            $this->setStyle('.b_activo', 'margin-left', '2px');
            $this->setStyle('.b_activo', 'margin-right', '2px');
            $this->setStyle('.b_activo', 'padding', '0px 5px 0px 5px');

            $this->setStyle('.b_focus', 'background', 'transparent url("images/over.jpg") repeat-x top left'); //#8414ff');
            $this->setStyle('.b_focus', 'color', '#432452');
            $this->setStyle('.b_focus', 'border', 'none');
            $this->setStyle('.b_focus', 'padding', '0px 5px 0px 5px');
            $this->setStyle('.b_focus', 'margin-left', '2px');
            $this->setStyle('.b_focus', 'margin-right', '2px');

            $this->setStyle('.b_inactivo', 'margin-left', '2px');
            $this->setStyle('.b_inactivo', 'margin-right', '2px');
            $this->setStyle('.b_inactivo', 'padding', '0px 5px 0px 5px');
        } else {
            $this->setStyle('.b_activo', 'border', '1px solid #ffffff');
            $this->setStyle('.b_activo:hover', 'background', 'transparent url("images/over.jpg") repeat-x top left'); //#8414ff');
            $this->setStyle('.b_activo:hover', 'color', '#432452');
            $this->setStyle('.b_activo:hover', 'border', '1px solid #ffffff');
        }


        switch ($iAncho) {
            case ($iAncho > 1050):
                if ($sBrowser == "Microsoft Internet Explorer") {
                    $this->setStyle('.b_activo', 'font-size', '100%');
                    $this->setStyle('.b_focus', 'font-size', '100%');
                    $this->setStyle('.b_inactivo', 'font-size', '100%');
                } else {
                    $this->setStyle('.b_activo', 'font-size', '100%');
                    $this->setStyle('.b_activo:hover', 'font-size', '100%');
                    $this->setStyle('.b_inactivo', 'font-size', '100%');
                }
                break;

            case(800 < $iAncho && $iAncho <= 1050):
                if ($sBrowser == "Microsoft Internet Explorer") {
                    $this->setStyle('.b_activo', 'font-size', '0.95em');
                    $this->setStyle('.b_focus', 'font-size', '0.95em');
                    $this->setStyle('.b_inactivo', 'font-size', '0.95em');
                } else {
                    $this->setStyle('.b_activo', 'font-size', '0.95em');
                    $this->setStyle('.b_activo:hover', 'font-size', '0.95em');
                    $this->setStyle('.b_inactivo', 'font-size', '0.95em');
                }
                break;

            case(600 < $iAncho && $iAncho <= 800):
                if ($sBrowser == "Microsoft Internet Explorer") {
                    $this->setStyle('.b_activo', 'font-size', '0.95em');
                    $this->setStyle('.b_focus', 'font-size', '0.95em');
                    $this->setStyle('.b_inactivo', 'font-size', '0.95em');
                } else {
                    $this->setStyle('.b_activo', 'font-size', '0.95em');
                    $this->setStyle('.b_activo:hover', 'font-size', '0.95em');
                    $this->setStyle('.b_inactivo', 'font-size', '0.95em');
                }
                break;


        }


    }
}
