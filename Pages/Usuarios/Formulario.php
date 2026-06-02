<?php

namespace Tuqan\Pages\Usuarios;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Formulario
{
    public function ShowPage($id = null)
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        // Prefer the route parameter. Fall back to ?id= for any old links.
        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;
        $usuario = null;

        if ($id > 0) {
            // Load existing user (basic version for now)
            $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
            $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

            $db = new \Tuqan\Classes\Manejador_Base_Datos(
                $_SESSION['login'] ?? '',
                $_SESSION['pass'] ?? '',
                $_SESSION['db'] ?? '',
                $host,
                $port
            );

            $db->consultaPreparada(
                "SELECT id, login, nombre, apellido, email, perfil, activo FROM usuarios WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $usuario = [
                    'id'       => $row[0],
                    'login'    => $row[1],
                    'nombre'   => $row[2],
                    'apellido' => $row[3],
                    'email'    => $row[4],
                    'perfil'   => $row[5],
                    'activo'   => $row[6],
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        // Load active perfiles for the dropdown (now that Perfiles module + POST is live)
        $perfilesList = [];
        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);
        $db2 = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );
        $db2->consulta("SELECT id, nombre FROM perfiles WHERE activo ORDER BY id");
        while ($r = $db2->coger_Fila()) {
            $perfilesList[] = ['id' => $r[0], 'nombre' => $r[1]];
        }
        $db2->desconexion();

        // Pick up validation error from previous POST attempt (so layout can show centralized flash)
        $flashError = $_SESSION['usuario_form_error'] ?? null;
        unset($_SESSION['usuario_form_error']);

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            'usuario'     => $usuario,
            'perfiles'    => $perfilesList,
            'isEdit'      => (bool)$usuario,
            'pageTitle'   => $usuario ? 'Editar Usuario' : 'Nuevo Usuario',
            'flashError'  => $flashError,
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('usuarios/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar el formulario: " . $e->getMessage();
        }
    }

    /**
     * POST handler for Usuarios create/update (Stage 8.6).
     * Handles password with md5 (matching existing seed/auth), perfil FK, etc.
     */
    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: /admin/usuarios');
            exit;
        }

        Config::initialize();

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        }
        $id = (int)$id;

        $login    = trim($_POST['login'] ?? '');
        $nombre   = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $perfil   = (int)($_POST['perfil'] ?? 1);
        $activo   = !empty($_POST['activo']) ? 't' : 'f';
        $password = $_POST['password'] ?? '';

        $errors = [];
        if ($login === '') $errors[] = 'Login es obligatorio.';
        if ($nombre === '') $errors[] = 'Nombre es obligatorio.';
        if (!$id && $password === '') $errors[] = 'Contraseña es obligatoria para nuevo usuario.';

        if (!empty($errors)) {
            $_SESSION['usuario_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "/admin/usuarios/editar/$id" : '/admin/usuarios/nuevo';
            header("Location: $target");
            exit;
        }

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        if ($id > 0) {
            if ($password !== '') {
                $passMd5 = md5($password);
                $db->consultaPreparada(
                    "UPDATE usuarios SET login = ?, nombre = ?, apellido = ?, email = ?, perfil = ?, activo = ?, pass = ? WHERE id = ?",
                    [$login, $nombre, $apellido, $email, $perfil, $activo, $passMd5, $id]
                );
            } else {
                $db->consultaPreparada(
                    "UPDATE usuarios SET login = ?, nombre = ?, apellido = ?, email = ?, perfil = ?, activo = ? WHERE id = ?",
                    [$login, $nombre, $apellido, $email, $perfil, $activo, $id]
                );
            }
            $msg = 'Usuario actualizado correctamente.';
        } else {
            $passMd5 = md5($password);
            $db->consultaPreparada(
                "INSERT INTO usuarios (login, pass, perfil, activo, nombre, apellido, email) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$login, $passMd5, $perfil, $activo, $nombre, $apellido, $email]
            );
            $msg = 'Usuario creado correctamente.';
        }

        $db->desconexion();

        $_SESSION['usuario_flash_success'] = $msg;  // will show on the list page
        header('Location: /admin/usuarios');
        exit;
    }
}
