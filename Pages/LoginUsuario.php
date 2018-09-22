<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Formulario_Identificacion;
use Tuqan\Classes\Manejador_Base_Datos;
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


        $sLoginEmp = $_SESSION['login'];
        $sDbEmp = $_SESSION['db'];


        //Creamos el encriptador, las claves de acceso a la DB van encriptadas
        $css =& new \encriptador();
        $clave = 'encriptame';
        $pass = (string)$css->decrypt(trim($this->config->sPassEtc), $clave);


        //Cadena de conexión a la BD
        $dsn = 'pgsql://' . $sLoginEmp . ':' . $pass . '@' . $this->config->sServidorEtc . '/' . $sDbEmp;

        $options = array(
            'dsn' => $dsn,
            'table' => 'usuarios',
            'usernamecol' => 'login',
            'passwordcol' => 'password',
            'cryptType' => 'md5'
        );

        $optional = true;

        //Creamos objeto de autentificación e iniciamos sesion.
        $a = new \Auth("DB", $options, "\Tuqan\loginFunction", $optional);
        $a->start();

//Auth configuration
        $a->setAdvancedSecurity(TRUE);

//Si usuario logeado
        if ($a->getAuth()) {
            //Si se ha echo un logout -> cerramos sesion.
            if ($_GET['action'] == "logout") {
                $a->logout();
                $a->start();
            } else
                if (isset($_POST['password'])) {
                    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

                    $sClaveMd5 = md5($_POST['password']);

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area'));
                    $oBaseDatos->construir_Tablas(array('usuarios'));
                    $oBaseDatos->construir_Where(array('(login=\'' . $_POST["username"] . '\')', '(activo=\'t\')', '(password=\'' . $sClaveMd5 . '\')'));
                    $oBaseDatos->consulta();

                    $aIterador = $oBaseDatos->coger_Fila();
                    $_SESSION['usuarioconectado'] = true;
                    $_SESSION['userid'] = $aIterador[0];
                    $_SESSION['nombreUsuario'] = $aIterador[1];
                    $_SESSION['perfil'] = $aIterador[2];
                    $_SESSION['areausuario'] = $aIterador[3];

                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_Tablas(array('usuarios'));
                    $oBaseDatos->construir_Where(array("id='" . $_SESSION['userid'] . "'"));
                    $oBaseDatos->construir_SetSin(array('ultimo_acceso', 'numero_accesos'),
                        array('now()', 'numero_accesos+1'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->desconexion();
                    header('Location:qnova.php');
                } else {
                    header('Location:qnova.php');
                }
        }

    }
}
