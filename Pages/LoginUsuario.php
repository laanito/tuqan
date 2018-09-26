<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Formulario_Identificacion;
use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Tuqan\Classes\Auth;

class LoginUsuario
{
    private $sNavegador;
    private $sSistema;
    private $idioma;
    private $base_path;

    /**
     * LoginUsuario constructor.
     */
    function __construct()
    {
        Config::initialize();

        $this->idioma = Config::$sIdioma;
        $this->base_path = Config::$base_path;

        if ($_SESSION['loginempresa'] == 1) {
            $_SESSION['encodingdb'] = Config::$dbEncoding;
            $_SESSION['encodingapache'] = Config::$apacheEncoding;
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
        $loader = new Twig_Loader_Filesystem(Config::$template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => Config::$cache_path,
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

        if($auth->login($_POST['nombre'], $_POST['clave'])){
            $_SESSION['usuarioconectado']=true;
            $_SESSION['admin']=true;
            $_SESSION['perfil']='0';
            header('Location: /main/');
        }
        else {
            header('Location: /login/empresa/');
        }
    }
}
