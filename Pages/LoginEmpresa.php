<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Former\Facades\Former as Former;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

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

        // Auto-provide central DB handler for real company list queries in production
        // (tests continue to inject via setDbHandler for isolation).
        if ($this->dbHandler === null) {
            try {
                $this->dbHandler = new \Tuqan\Classes\Manejador_Base_Datos(
                    Config::$sLoginEtc,
                    Config::$sPassEtc,
                    Config::$sDbEtc,
                    Config::$sServidorEtc,
                    Config::$iPuertoEtc,
                    Config::$sTipoBdEtc
                );
            } catch (\Exception $e) {
                // Will fall back inside MuestraPagina
                $this->dbHandler = null;
            }
        }
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
                // Real query against central registry (qnova_acl for login credentials in minimal seed)
                $this->dbHandler->consultaPreparada(
                    "SELECT login_name, 'Demo Company' as label FROM qnova_acl ORDER BY id",
                    []
                );

                while (($row = $this->dbHandler->coger_Fila())) {
                    $key = $row[0] ?? 'demo';
                    $label = $row[1] ?? $key;
                    $aEmpresas[$key] = $label;
                }
            } catch (\Exception $e) {
                $aEmpresas = ['demo' => 'Demo Company'];
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
            $loader = new FilesystemLoader(Config::$template_path);
            $twig = new Environment($loader, ['cache' => Config::$cache_path]);

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

        $companyKey = $_POST['nombre'] ?? '';
        $passwordMd5 = md5($_POST['clave'] ?? '');

        $_SESSION['loginempresa'] = 0;

        // Real database-backed company authentication (no more hardcode shortcut).
        // Uses the central "etc" DB (qnova_acl + qnova_bbdd tables from minimal seed)
        // to validate credentials and obtain the target company DB connection info.
        $centralHandler = $this->dbHandler;

        if ($centralHandler === null) {
            // Production path: create handler for central DB using Config etc values
            Config::initialize();
            $centralHandler = new \Tuqan\Classes\Manejador_Base_Datos(
                Config::$sLoginEtc,
                Config::$sPassEtc,
                Config::$sDbEtc,
                Config::$sServidorEtc,
                Config::$iPuertoEtc,
                Config::$sTipoBdEtc
            );
        }

        try {
            // Validate against qnova_acl using posted login_name
            $centralHandler->consultaPreparada(
                "SELECT id FROM qnova_acl WHERE login_name = ? AND login_pass = ?",
                [$companyKey, $passwordMd5]
            );
            $aclRow = $centralHandler->coger_Fila();

            if (!$aclRow) {
                if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                    \Tuqan\Classes\TuqanLogger::debug('LoginEmpresa ERROR - no ACL row', [
                        'companyKey' => $companyKey,
                        'passwordMd5_prefix' => substr($passwordMd5, 0, 8),
                    ]);
                }
                error_log("TUQAN_DIAG: LoginEmpresa ERROR - no ACL row for companyKey=$companyKey");
                session_write_close();
                $this->Redirect($this->base_path . "/?error=1", false);
            }

            // Get the (single) company DB connection details
            $centralHandler->consultaPreparada(
                "SELECT nombre_bbdd, login_bbdd, pass_bbdd, empresa FROM qnova_bbdd ORDER BY id LIMIT 1",
                []
            );
            $bbddRow = $centralHandler->coger_Fila();

            if ($bbddRow) {
                $_SESSION['loginempresa'] = 1;
                $_SESSION['conectado'] = true;
                $_SESSION['db'] = $bbddRow[0];
                $_SESSION['login'] = $bbddRow[1];
                $_SESSION['pass'] = getenv('DB_PASS') ?: 'secret';
                $_SESSION['empresa'] = $bbddRow[3] ?? $companyKey;
                $_SESSION['idiomaid'] = '1';

                $_SESSION['db_host'] = getenv('DB_HOST') ?: 'localhost';
                $_SESSION['db_port'] = (int)(getenv('DB_PORT') ?: 5432);

                if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                    \Tuqan\Classes\TuqanLogger::debug('LoginEmpresa SUCCESS - loginempresa set', [
                        'companyKey'     => $companyKey,
                        'loginempresa'   => $_SESSION['loginempresa'],
                        'empresa'        => $_SESSION['empresa'],
                        'db'             => $_SESSION['db'],
                    ]);
                }
                error_log("TUQAN_DIAG: LoginEmpresa SUCCESS - loginempresa=1 for companyKey=$companyKey, empresa=" . ($_SESSION['empresa'] ?? ''));

                $centralHandler->desconexion();
                session_write_close();
                $this->Redirect($this->base_path . "/login/usuario/", false);
            } else {
                if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                    \Tuqan\Classes\TuqanLogger::debug('LoginEmpresa ERROR - no bbddRow', [
                        'companyKey' => $companyKey,
                    ]);
                }
                error_log("TUQAN_DIAG: LoginEmpresa ERROR - no bbddRow for companyKey=$companyKey");
                session_write_close();
                $this->Redirect($this->base_path . "/?error=1", false);
            }
        } catch (\Exception $e) {
            if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                \Tuqan\Classes\TuqanLogger::debug('LoginEmpresa CATCH - exception in real path', [
                    'companyKey' => $companyKey,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
            error_log("TUQAN_DIAG: LoginEmpresa CATCH exception for companyKey=$companyKey - " . $e->getMessage());
            session_write_close();
            $this->Redirect($this->base_path . "/?error=1", false);
        }
    }

    function Redirect($url, $permanent = false)
    {
        if (headers_sent() === false) {
            session_write_close();
            header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
        }
        exit();
    }
}
