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

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            'usuario'     => $usuario,
            'isEdit'      => (bool)$usuario,
            'pageTitle'   => $usuario ? 'Editar Usuario' : 'Nuevo Usuario',
        ];

        try {
            $template = $twig->load('usuarios/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar el formulario: " . $e->getMessage();
        }
    }
}
