<?php
namespace Tuqan\Classes;

/**
 * Created on 19-oct-2005
 *
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 * Aquí incluiremos el código de los formularios de la aplicación.
 * Serán extensiones de la clase HTML_QuickForm de PEAR.
 *
 */


/**
 * Aquí extendemos la clase de HTML_QuickForm para crear nuestro formulario de login.
 * Incluiremos un único constructor, el cual ya nos devuelve completo el formulario.
 */
class Formulario_Identificacion extends \HTML_QuickForm2
{
    public function __construct($name, $method, $action) {
        $aAttributes = array('action' => $action);
        parent::__construct($name, $method, $aAttributes);
    }


    /**
     * @param $sSesion
     * @param $iNumeroprocesa
     * @param $sLoginEmp
     * @param $sPassEmp
     * @param $sDbEmp
     * @param $iTipoLog
     * @throws \HTML_QuickForm2_InvalidArgumentException
     * @throws \HTML_QuickForm2_NotFoundException
     */

    public function inicializar($sSesion, $iNumeroprocesa, $sLoginEmp, $sPassEmp, $sDbEmp, $iTipoLog)
    {
        // $iTipoLog para saber en que pantalla se encuentra, si en login 1:empresa o 2:usuario
        if ($iTipoLog == 1) {
            // para logueo de empresa

            $oDb = new Manejador_Base_Datos($sLoginEmp, $sPassEmp, $sDbEmp);

            //Sacamos el idioma seleccionado para que salga el primero en el check
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id', 'nombre'));
            $oDb->construir_Tablas(array('idiomas'));
            $oDb->construir_Where(array("nombre='" . $_SESSION['idioma'] . "'"));
            $oDb->consulta();
            $aIdioma = array();

            if ($aIterador = $oDb->coger_Fila()) {
                $aIdioma[$aIterador[0]] = $aIterador[1];
            }
            //Sacamos los demas idiomas

            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id', 'nombre'));
            $oDb->construir_Tablas(array('idiomas'));
            $oDb->construir_Where(array("nombre<>'" . $_SESSION['idioma'] . "'"));
            $oDb->consulta();

            while ($aIterador = $oDb->coger_Fila()) {
                $aIdioma[$aIterador[0]] = $aIterador[1];
            }

            // Sacamos las empresas

            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('login_name'));
            $oDb->construir_Tablas(array('qnova_acl'));
            $oDb->consulta();

            $aEmpresas=array();
            while ($aIterador = $oDb->coger_Fila()) {
                $aEmpresas[$aIterador[0]] = $aIterador[0];
            }

            if ($sNavegador == "Microsoft Internet Explorer") {
                $aEventos = array("class" => "b_activo",
                    "onMouseOver" => "this.className='b_focus'",
                    "onMouseOut" => "this.className='b_activo'"
                );
            } else {
                $aEventos = array("class" => "b_activo");
            }
            $aEmpresa = array('options' => $aEmpresas);
            $aIdiomas = array('options' => $aIdioma);
            $aEventos['label']=gettext("sBotonAceptar");


            $this->addElement('select', 'nombre',
                array(), $aEmpresa)->setLabel(gettext("sSelecEmpresa"));
            $this->addElement('password', 'clave')->setLabel(gettext('sClave'));
            $this->addElement('select', 'idioma',
                array(), $aIdiomas)->setLabel(gettext("sSelecIdioma"));
            $this->addElement('submit', 'submit', $aEventos)->setLabel(gettext('Submit'));

            $this->addElement('hidden', 'numero', array('value' => $iNumeroprocesa));
            $this->addElement('hidden', 'sesion', array('value' => $sSesion));
        } else {
            $aNombre = array("id" => "enfocar");
            // para logueo de usuario

            if ( $_SESSION['navegador'] == "Microsoft Internet Explorer") {
                $aEventos = array("class" => "b_activo",
                    "onMouseOver" => "this.className='b_focus'",
                    "onMouseOut" => "this.className='b_activo'"
                );
            } else {
                $aEventos = array("class" => "b_activo");
            }
            $this->addElement('text', 'nombre', $aNombre)->setLabel(gettext("sUsuario"));
            $this->addElement('password', 'clave')->setLabel(gettext("sClave"));
            $this->addElement('submit', 'submit', $aEventos)->setLabel(gettext("sBotonAceptar"));

            $this->addElement('hidden', 'numero', $iNumeroprocesa);
            $this->addElement('hidden', 'sesion', $sSesion);

        }
    }
}

