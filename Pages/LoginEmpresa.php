<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Manejador_Base_Datos;
use Former\Former;
use \Twig_Loader_Filesystem;
use \Twig_Environment;


class LoginEmpresa
{

    private $sLoginEmp;
    private $sPassEmp;
    private $sDbEmp;
    private $idioma;
    private $base_path;

    /**
     * LoginEmpresa constructor.
     */
    public function __construct()
    {
        Config::initialize();
        $css =& new \encriptador();
        $clave = 'encriptame';
        $this->sLoginEmp = Config::$sLoginEtc;
        $this->sPassEmp = $css->decrypt(trim(Config::$sPassEtc), $clave);
        $this->sDbEmp = Config::$sDbEtc;
        $this->idioma = Config::$sIdioma;
        $this->base_path = Config::$base_path;
        $_SESSION['idioma'] = Config::$sIdioma;
    }

    /**
     * @return string
     */
    public function MuestraPagina()
    {
        try {
            $oDb = new Manejador_Base_Datos(
                $this->sLoginEmp,
                $this->sPassEmp,
                $this->sDbEmp
            );

            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('login_name'));
            $oDb->construir_Tablas(array('qnova_acl'));
            $oDb->consulta();

            $aEmpresas=array();
            while ($aIterador = $oDb->coger_Fila()) {
                $aEmpresas[$aIterador[0]] = $aIterador[0];
            }

            $FormTitle = gettext("sIdentEmpresa");
            if (isset($_GET['error'])) {
                $FormTitle .= "<p class=\"error\">" . gettext('sIdIncorrecta') . "</p>";
            }


            Former::framework('TwitterBootstrap3');
            $Formulario = (string)Former::horizontal_open([
                'url' => '/login/empresa',
                'method' => 'POST'
                ]);

            $Formulario.= Former::select('nombre')->options($aEmpresas)
                ->placeholder(gettext("Choose an option..."))
                ->label(gettext("Company Name"));
            $Formulario.= Former::password('clave')->label(gettext("Password"));
            $Formulario.= Former::close();
            Config::initialize();
            $loader = new Twig_Loader_Filesystem(Config::$template_path);
            $twig = new Twig_Environment(
                $loader, array('cache' => Config::$cache_path,)
            );

            $template = $twig->load('login.twig');
            return $template->render(
                array(
                    'FormTitle' => $FormTitle,
                    'FormContent' => $Formulario
                )
            );
        } catch (\Exception $e) {
            return ("Ocurrió un error:\n" . $e->getMessage());
        }
    }

    /**
     * Form processing
     */
    public function ProcesaPagina()
    {
        /**
         * Recuperamos la sesion
         */
        if (!isset($_SESSION)) {
            session_start();
        }
        /**
         *     convertimos el campo password que nos pasan por post a md5)
         * @var string
         */

        $sClaveMd5 = md5($_POST['clave']);

        /**
         * Creamos un objeto para que realice las llamadas a las funciones de nuestra clase de base de datos
         * @var object
         */

        $_SESSION['loginempresa'] = 0;
        $oBaseDatos = new Manejador_Base_Datos(
            $this->sLoginEmp,
            $this->sPassEmp,
            $this->sDbEmp);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id'));
        $oBaseDatos->construir_Tablas(array('qnova_acl'));
        $oBaseDatos->construir_Where(array('(login_name=\'' . $_POST['nombre'] . '\')', '(login_pass=\'' . $sClaveMd5 . '\')'));
        $oBaseDatos->consulta();

        /**
         *  Si ha devuelto algo es que el login es correcto y redireccionamos a principal
         *  En caso contrario volvemos a index
         */

        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $_SESSION['loginempresa'] = 1;
            /**
             *  Si nos hemos logeado bien obtenemos los parametros de conexion a la base de datos
             *  del usuario
             */

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('nombre_bbdd', 'login_bbdd', 'pass_bbdd', 'empresa'));
            $oBaseDatos->construir_Tablas(array('qnova_bbdd'));
            $oBaseDatos->construir_Where(array('(id=\'' . $aIterador[0] . '\')'));
            $oBaseDatos->consulta();

            if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {

                /**
                 *  Si hemos obtenido correctamente los datos de conexion mostramos el segundo login
                 *  previamente ponemos las variables de sesion pertinentes para poder
                 *  realizar la conexion posteriormente
                 */
                $css =& new \encriptador();
                $clave = 'encriptame';
                $_SESSION['conectado'] = true;
                $_SESSION['db'] = $aIteradorInterno[0];
                $_SESSION['login'] = $aIteradorInterno[1];
                $_SESSION['pass'] = $css->decrypt(trim($aIteradorInterno[2]), $clave);
                $_SESSION['empresa'] = $aIteradorInterno[3];

                $this->Redirect($this->base_path .
                    "/login/usuario/", false);
            }
        } else {
            $this->Redirect($this->base_path .
                "/index.php?error=1", false);
        }
        $this->Redirect($this->base_path .
            "/index.php?error=1", false);
    }

    function Redirect($url, $permanent = false)
    {
        if (headers_sent() === false) {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }
}
