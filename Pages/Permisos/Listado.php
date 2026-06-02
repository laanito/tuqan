<?php

namespace Tuqan\Pages\Permisos;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Listado
{
    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        // List profiles
        $db->consulta("SELECT id, nombre, activo FROM perfiles ORDER BY id");

        $perfiles = [];
        while ($row = $db->coger_Fila()) {
            $perfiles[] = [
                'id'     => $row[0],
                'nombre' => $row[1],
                'activo' => $row[2],
            ];
        }

        // For a simple overview, count how many menus each profile can see (top level approximation)
        $db->consulta("
            SELECT m.permisos 
            FROM menu_nuevo m 
            WHERE m.activo = true AND m.padre IS NULL OR m.padre = 0
        ");

        $menuCount = 0;
        while ($db->coger_Fila()) { $menuCount++; }

        $db->desconexion();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $flashSuccess = $_SESSION['permiso_flash_success'] ?? null;
        unset($_SESSION['permiso_flash_success']);

        $variables = [
            'sidebarMenu'  => $sidebarMenu,
            'perfiles'     => $perfiles,
            'menuCount'    => $menuCount,
            'pageTitle'    => 'Permisos (Asignación de Menús)',
            'flashSuccess' => $flashSuccess,
            'UserTitle'    => gettext('sUsuario'),
            'UserName'     => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'  => $_SESSION['empresa'] ?? null,
            'UserEmail'    => $_SESSION['usuario_email'] ?? null,
            'UserFullName' => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('permisos/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar Permisos: " . $e->getMessage();
        }
    }
}
