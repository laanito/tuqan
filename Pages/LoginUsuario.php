<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Formulario_Identificacion;
use Tuqan\Classes\Manejador_Base_Datos;
use \encriptador;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Tuqan\Classes\Auth;

class LoginUsuario
{
    private $sNavegador;
    private $sSistema;
    private $idioma;
    private $base_path;
    private $auth;
    private $config;

    /**
     * LoginUsuario constructor.
     */
    function __construct()
    {
        $this->config = new Config();

        $this->idioma = $this->config->sIdioma;
        $this->base_path = $this->config->base_path;

        if ($_SESSION['loginempresa'] == 1) {
            $_SESSION['encodingdb'] = $this->config->dbEncoding;
            $_SESSION['encodingapache'] = $this->config->apacheEncoding;
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

        $result = array(
            'FormTitle' => gettext('sWelcome2') . ' : ' . $_SESSION['empresa'],
            'FormContent' => $oFormulario2->__toString()
        );
        return $result;
    }

    public function MuestraPagina()
    {
        $loader = new Twig_Loader_Filesystem($this->config->template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => $this->config->cache_path,
        ));
        try {
            $template = $twig->load('login.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        return $template->render($this->Formulario());
    }


    public function ProcesaPagina()
    {
        $auth = new Auth();

        if($auth->login($_POST['login'], $_POST['pass'])){
            // @TODO implement post-login logic
            header('Location: /main/');
        }
        else {
            header('Location: /login/empresa/');
        }
    }
}
