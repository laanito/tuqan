<?php

namespace Tuqan\Pages\TiposImp;

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

        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;
        $tiposimp = null;

        if ($id > 0) {
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
                "SELECT id, nombre, activo FROM tiposimp WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $tiposimp = [
                    'id'     => $row[0],
                    'nombre' => $row[1],
                    'activo' => $row[2],
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            'tiposimp'    => $tiposimp,
            'isEdit'      => (bool)$tiposimp,
            'pageTitle'   => $tiposimp ? 'Editar Tipo Imp. Amb.' : 'Nuevo Tipo Imp. Amb.',
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('tiposimp/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar el formulario de Tipos Imp. Amb.: " . $e->getMessage();
        }
    }

    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: /admin/tiposimp');
            exit;
        }

        Config::initialize();

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        }
        $id = (int)$id;

        $nombre = trim($_POST['nombre'] ?? '');
        $activo = !empty($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre === '') {
            $errors[] = 'El nombre es obligatorio.';
        }

        if (!empty($errors)) {
            $_SESSION['tiposimp_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "/admin/tiposimp/editar/$id" : '/admin/tiposimp/nuevo';
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
            $db->consultaPreparada(
                "UPDATE tiposimp SET nombre = ?, activo = ? WHERE id = ?",
                [$nombre, $activo, $id]
            );
            $msg = 'Tipo Imp. Amb. actualizado correctamente.';
        } else {
            $db->consultaPreparada(
                "INSERT INTO tiposimp (nombre, activo) VALUES (?, ?)",
                [$nombre, $activo]
            );
            $msg = 'Tipo Imp. Amb. creado correctamente.';
        }

        $db->desconexion();

        $_SESSION['tiposimp_flash_success'] = $msg;
        header('Location: /admin/tiposimp');
        exit;
    }
}
