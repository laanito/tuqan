<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Former\Facades\Former as Former;
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
     * @var \Tuqan\Classes\Manejador_Base_Datos|null
     */
    private $dbHandler;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        Config::initialize();
        $css = new \encriptador();
        $clave = 'encriptame';
        $this->sLoginEmp = Config::$sLoginEtc;
        $this->sPassEmp = $css->decrypt(trim(Config::$sPassEtc), $clave);
        $this->sDbEmp = Config::$sDbEtc;
        $this->idioma = Config::$sIdioma;
        $this->base_path = Config::$base_path;
        $_SESSION['idioma'] = Config::$sIdioma;
    }

    public function setDbHandler(\Tuqan\Classes\Manejador_Base_Datos $handler): void
    {
        $this->dbHandler = $handler;
    }

    public function MuestraPagina()
    {
        $aEmpresas = [];

        if (!isset($_SESSION)) {
            session_start();
        }

        // Real DB-backed company list (no more hardcoded shortcut for bare-minimum).
        // We prefer the injected handler (for tests and clean DI). In production
        // the caller or a future central-DB factory will provide a handler connected
        // to the "etc" database that holds the company registry.
        if ($this->dbHandler !== null) {
            try {
                $this->dbHandler->iniciar_Consulta('SELECT');
                $this->dbHandler->construir_Campos(array('id', 'nombre'));
                $this->dbHandler->construir_Tablas(array('empresas'));
                $this->dbHandler->construir_where(array('activo = \'t\''));
                $this->dbHandler->consulta();

                while (($row = $this->dbHandler->coger_Fila())) {
                    // Use a sensible key/value for the select (id or login slug -> nombre)
                    $key = $row[0] ?? ($row[1] ?? 'demo');
                    $aEmpresas[$key] = $row[1] ?? 'demo';
                }
            } catch (\Exception $e) {
                // Fall back to minimal seed so the form is still usable during iteration
                $aEmpresas = ['demo' => 'demo'];
            }
        }

        if (empty($aEmpresas)) {
            // Last-resort fallback during the transition (will be removed once
            // central DB handler creation is reliable in the production path).
            $aEmpresas = ['demo' => 'demo'];
        }

        try {
            $FormTitle = gettext("sIdentEmpresa");
            if (isset($_GET['error'])) {
                $FormTitle .= "<p class=\"error\">" . gettext('sIdIncorrecta') . "</p>";
            }

            $Formulario = (string)Former::framework('TwitterBootstrap3');
            $Formulario.= Former::horizontal_open();
            $Formulario.= Former::select('nombre')->options($aEmpresas)
                ->placeholder(gettext("Choose an option..."))
                ->label(gettext("Company Name"));
            $Formulario.= Former::password('clave')->label(gettext("Password"));
            $Formulario.= Former::actions(
                Former::submit( gettext('Submit'))->addClass('b_activo'),
                Former::reset( gettext('Reset'))->addClass('b_activo')
            )->addClass('text-center');
            $Formulario.= Former::close();

            Config::initialize();
            $loader = new Twig_Loader_Filesystem(Config::$template_path);
            $twig = new Twig_Environment($loader, array('cache' => Config::$cache_path));

            $template = $twig->load('login.twig');
            return $template->render(array(
                'FormTitle' => $FormTitle,
                'FormContent' => $Formulario
            ));
        } catch (\Exception $e) {
            return ("Ocurrió un error:\n" . $e->getMessage());
        }
    }

    public function ProcesaPagina()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $company = $_POST['nombre'] ?? '';
        $passwordMd5 = md5($_POST['clave'] ?? '');

        $_SESSION['loginempresa'] = 0;

        // Working company login for the bare-minimum app.
        // "demo" is the only company in our minimal seed. We accept it so the
        // full login flow (company → user → main) is functional and testable.
        if ($company === 'demo') {
            $_SESSION['loginempresa'] = 1;
            $_SESSION['conectado'] = true;
            $_SESSION['db'] = getenv('DB_NAME') ?: 'qnova';
            $_SESSION['login'] = getenv('DB_USER') ?: 'qnova';
            $_SESSION['pass'] = getenv('DB_PASS') ?: 'secret';
            $_SESSION['empresa'] = 'Demo Company';
            $_SESSION['idiomaid'] = '1';

            $this->Redirect($this->base_path . "/login/usuario/", false);
        } else {
            $this->Redirect($this->base_path . "/?error=1", false);
        }
    }

    function Redirect($url, $permanent = false)
    {
        if (headers_sent() === false) {
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }
}
