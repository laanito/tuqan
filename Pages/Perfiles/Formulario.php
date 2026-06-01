<?php

namespace Tuqan\Pages\Perfiles;

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
        $perfil = null;

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
                "SELECT id, nombre, activo FROM perfiles WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $perfil = [
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
            'perfil'      => $perfil,
            'isEdit'      => (bool)$perfil,
            'pageTitle'   => $perfil ? 'Editar Perfil' : 'Nuevo Perfil',
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('perfiles/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar el formulario de perfiles: " . $e->getMessage();
        }
    }
}
