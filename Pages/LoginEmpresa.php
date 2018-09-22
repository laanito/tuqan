<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Classes\Formulario_Identificacion;
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
        $config = new Config();
        $css =& new \encriptador();
        $clave = 'encriptame';
        $this->sLoginEmp = $config->sLoginEtc;
        $this->sPassEmp = $css->decrypt(trim($config->sPassEtc), $clave);
        $this->sDbEmp = $config->sDbEtc;
        $this->idioma = $config->sIdioma;
        $this->base_path = $config->base_path;
        $_SESSION['idioma'] = $config->sIdioma;
    }

    /**
     * @return string
     */
    public function MuestraPagina()
    {

        $oDb = new Manejador_Base_Datos(
            $this->sLoginEmp,
            $this->sPassEmp,
            $this->sDbEmp
        );
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('nombre', 'id'));
        $oDb->construir_Tablas(array('idiomas'));
        $oDb->construir_Where(array("(id=" . $_SESSION['idioma'] . ")"));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $_SESSION['idioma'] = $aIterador[0];
            $_SESSION['idiomaid'] = $aIterador[1];
        } else {
            $_SESSION['idiomaid'] = 1;
        }
        $config = new Config();
        $loader = new Twig_Loader_Filesystem($config->template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => $config->cache_path,
        ));


        /**
         * Este es el objeto donde cargamos el formulario de login. Es una instancia de la clase HTML_QuickForm.
         * @var object
         */
        $oFormulario2 = new Formulario_Identificacion('Identificacion', 'POST',
            $this->base_path . '/login/empresa/');
        try {
            $oFormulario2->inicializar(
                session_id(),
                1,
                $this->sLoginEmp,
                $this->sPassEmp,
                $this->sDbEmp,
                1);
        } catch (\Exception $e) {
            return ("Ocurrió un error:\n" . $e->getMessage());
        }
        try {
            $template = $twig->load('login.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        $FormTitle = gettext("sIdentEmpresa");


        $Formulario = $oFormulario2->__toString();
        if (isset($_GET['error'])) {
            $FormTitle .= "<p class=\"error\">" . gettext('sIdIncorrecta') . "</p>";
        }
        return $template->render(
            array(
                'FormTitle' => $FormTitle,
                'FormContent' => $Formulario
            )
        );

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

                $this->Redirect($_SERVER['DOCUMENT_ROOT'].$this->base_path .
                    "/login/usuario/", false);
            }
        } else {
            $this->Redirect($_SERVER['DOCUMENT_ROOT'].$this->base_path .
                "/index.php?error=1", false);
        }
        $this->Redirect($_SERVER['DOCUMENT_ROOT'].$this->base_path .
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
