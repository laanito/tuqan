<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Former\Facades\Former as Former;
use Twig\Environment;
use Tuqan\Classes\Auth;

class LoginUsuario
{
    private $idioma;
    private $base_path;

    function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        Config::initialize();

        $this->idioma = Config::$sIdioma;
        $this->base_path = Config::$base_path;

        if (isset($_SESSION['loginempresa']) && $_SESSION['loginempresa'] == 1) {
            $_SESSION['encodingdb'] = Config::$dbEncoding;
            $_SESSION['encodingapache'] = Config::$apacheEncoding;
            $aParametrosNav = explode(';', $_SERVER['HTTP_USER_AGENT'] ?? '');

            $_SESSION['sistema_operativo'] = trim($aParametrosNav[2] ?? '');
            if (preg_match('/Gecko/', $_SERVER['HTTP_USER_AGENT'] ?? '')) {
                $_SESSION['navegador'] = 'Netscape';
            } else if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'] ?? '')) {
                $_SESSION['navegador'] = 'Microsoft Internet Explorer';
                $_SESSION['cliente'] = $_SERVER['HTTP_USER_AGENT'];
            }
        }
    }

    public function Formulario()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

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

            return array(
                'FormTitle' => $FormTitle,
                'FormContent' => $Formulario
            );
        } catch (\Exception $e) {
            return array(
                'FormTitle' => "Ocurrió un error:",
                'FormContent' => $e->getMessage());
        }
    }

    public function MuestraPagina()
    {
        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);
        try {
            $template = $twig->load('login.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        return $template->render($this->Formulario());
    }

    public function ProcesaPagina()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $username = $_POST['nombre'] ?? '';
        $password = $_POST['clave'] ?? '';
        $passwordMd5 = md5($password);

        // Real database-backed user authentication (after company context switch).
        // Queries the company DB (set by LoginEmpresa) for the usuarios table.
        if (!isset($_SESSION['db']) || !isset($_SESSION['login']) || !isset($_SESSION['pass'])) {
            session_write_close();
            header('Location: /login/empresa/');
            return;
        }

        try {
            // Use host stored during company login if available (supports future per-company hosts).
            // Falls back to current Docker env (DB_HOST=db inside container).
            $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
            $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

            $userDbHandler = new \Tuqan\Classes\Manejador_Base_Datos(
                $_SESSION['login'],
                $_SESSION['pass'],
                $_SESSION['db'],
                $host,
                $port
            );

            $userDbHandler->consultaPreparada(
                "SELECT id, login, perfil, nombre FROM usuarios WHERE login = ? AND pass = ? AND activo = 't'",
                [$username, $passwordMd5]
            );
            $userRow = $userDbHandler->coger_Fila();

            if ($userRow) {
                $_SESSION['usuarioconectado'] = true;
                $_SESSION['admin'] = ((int)($userRow[2] ?? 0) === 0);
                $_SESSION['perfil'] = (string)($userRow[2] ?? '0');
                $_SESSION['nombreUsuario'] = $userRow[1] ?? $username;
                $_SESSION['idioma'] = $_SESSION['idioma'] ?? '1';

                $userDbHandler->desconexion();
                session_write_close();
                header('Location: /main/');
            } else {
                session_write_close();
                header('Location: /login/usuario/?error=1');
            }
        } catch (\Exception $e) {
            // Real error path — no more magic 'admin' shortcut. The try block with real DB query
            // (using the minimal seed) is now the only success path.
            if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                \Tuqan\Classes\TuqanLogger::debug('LoginUsuario ProcesaPagina DB error', [
                    'username' => $username,
                    'error' => $e->getMessage()
                ]);
            }
            header('Location: /login/usuario/?error=1');
        }
    }
}
