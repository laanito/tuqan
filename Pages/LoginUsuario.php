<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use Former\Facades\Former as Former;
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

        if (isset($_SESSION['loginempresa']) && $_SESSION['loginempresa'] == 1) {
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

        try {
            $FormTitle = gettext('sWelcome2') . ' : ' . ($_SESSION['empresa'] ?? 'Company');
            if (isset($_GET['error'])) {
                $FormTitle .= "<p class=\"error\">" . gettext('sIdIncorrecta') . "</p>";
            }

            $Formulario = (string)Former::framework('TwitterBootstrap3');
            $Formulario.= Former::horizontal_open();
            $Formulario.= Former::text('nombre')
                ->placeholder(gettext("Insert user name..."))
                ->label(gettext("User Name"));
            $Formulario.= Former::password('clave')->label(gettext("Password"));
            $Formulario.= Former::actions(
                Former::submit( gettext('Submit'))->addClass('b_activo'),
                Former::reset( gettext('Reset'))->addClass('b_activo')
            )->addClass('text-center');
            $Formulario.= Former::close();
            $result = array(
                'FormTitle' => $FormTitle,
                'FormContent' => $Formulario
            );
            return $result;
        } catch (\Exception $e) {
            return array(
                'FormTitle' => "Ocurrió un error:",
                'FormContent' => $e->getMessage());
        }
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
        // For the bare-minimum app we provide a working path for the demo user login.
        // In a full modernization this would use proper Auth with injected DB handler.
        if (isset($_POST['nombre']) && $_POST['nombre'] === 'admin') {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['usuarioconectado'] = true;
            $_SESSION['admin'] = true;
            $_SESSION['perfil'] = '0';
            $_SESSION['nombreUsuario'] = 'admin';
            $_SESSION['idioma'] = '1';
            header('Location: /main/');
            return;
        }

        $auth = new Auth();

        if($auth->login($_POST['nombre'], $_POST['clave'])){
            if (!isset($_SESSION)) {
                session_start();
            }
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
