<?php

namespace Tuqan\Pages\Usuarios;

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

        // Build the sidebar menu so navigation works on this modern page
        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        // Fetch users with their profile name
        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        $db->consulta(
            "SELECT u.id, u.login, u.nombre, u.apellido, u.email, u.perfil, u.activo,
                    p.nombre as perfil_nombre
             FROM usuarios u
             LEFT JOIN perfiles p ON p.id = u.perfil
             ORDER BY u.id"
        );

        $usuarios = [];
        while ($row = $db->coger_Fila()) {
            $usuarios[] = [
                'id'            => $row[0],
                'login'         => $row[1],
                'nombre'        => $row[2],
                'apellido'      => $row[3],
                'email'         => $row[4],
                'perfil'        => $row[5],
                'activo'        => $row[6],
                'perfil_nombre' => $row[7],
            ];
        }
        $db->desconexion();

        $variables = [
            'sidebarMenu'   => $sidebarMenu,
            'usuarios'      => $usuarios,
            'pageTitle'     => 'Usuarios',
        ];

        try {
            $template = $twig->load('usuarios/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar la plantilla de usuarios: " . $e->getMessage();
        }
    }
}
