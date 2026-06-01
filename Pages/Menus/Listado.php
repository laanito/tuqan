<?php

namespace Tuqan\Pages\Menus;

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

        // Basic view of the menu structure with Spanish labels
        $db->consulta("
            SELECT m.id, m.padre, m.orden, m.accion, mi.valor as label_es
            FROM menu_nuevo m
            LEFT JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = 1
            ORDER BY m.padre NULLS FIRST, m.orden, m.id
        ");

        $menus = [];
        while ($row = $db->coger_Fila()) {
            $menus[] = [
                'id'       => $row[0],
                'padre'    => $row[1],
                'orden'    => $row[2],
                'accion'   => $row[3],
                'label_es' => $row[4],
            ];
        }
        $db->desconexion();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu'  => $sidebarMenu,
            'menus'        => $menus,
            'pageTitle'    => 'Menús',
            'UserTitle'    => gettext('sUsuario'),
            'UserName'     => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'  => $_SESSION['empresa'] ?? null,
            'UserEmail'    => $_SESSION['usuario_email'] ?? null,
            'UserFullName' => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('menus/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar Menús: " . $e->getMessage();
        }
    }
}
