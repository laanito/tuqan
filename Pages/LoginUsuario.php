<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Formulario_Identificacion;
use \encriptador;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class LoginUsuario
{
    private $iAnchura;
    private $iAltura;
    private $sNavegador;
    private $sSistema;
    private $idioma;
    private $base_path;
    private $auth;

    /**
     * LoginUsuario constructor.
     */
    function __construct()
    {
        $config=new Config();

        $this->idioma = $config->sIdioma;
        $this->base_path = $config->base_path;

        if ($_SESSION['loginempresa'] == 1) {
            $_SESSION['encodingdb'] = $config->dbEncoding;
            $_SESSION['encodingapache'] = $config->apacheEncoding;
            $aParametrosNav = explode(';', $_SERVER['HTTP_USER_AGENT']);

            $_SESSION['sistema_operativo'] = trim($aParametrosNav[2]);
            if (preg_match('/Gecko/', $_SERVER['HTTP_USER_AGENT'])) {
                $_SESSION['navegador'] = 'Netscape';
            } else if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
                $_SESSION['navegador'] = 'Microsoft Internet Explorer';
                $_SESSION['cliente'] = $_SERVER['HTTP_USER_AGENT'];
                $this->sNavegador = $_SESSION['navegador'];
                $this->sSistema = $_SESSION['sistema_operativo'];
            }
        }
    }


    public function Formulario()
    {
        /**
         * Este es el objeto donde cargamos el formulario de login. Es una instancia de la clase HTML_QuickForm.
         * @var object
         */

         $oFormulario2 = new Formulario_Identificacion('Identificacion', 'POST',
             $this->base_path . '/login/usuario/');
        try {
            $oFormulario2->inicializar(
                session_id(),
                1,
                $_SESSION['login'],
                $_SESSION['pass'],
                $_SESSION['db'],
                2);
        } catch (\Exception $e) {
            return ("Ocurrió un error:\n" . $e->getMessage());
        }

        $result=array(
          'FormTitle' => gettext('sWelcome2').' : '.$_SESSION['empresa'],
          'FormContent' => $oFormulario2->__toString()
        );
        return $result;
    }

    public function MuestraPagina(){

        $config= new Config();
        $loader = new Twig_Loader_Filesystem($config->template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => $config->cache_path,
        ));
        try {
            $template = $twig->load('login.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: ".$e->getMessage());
        }
        return $template->render($this->Formulario());
    }
}
