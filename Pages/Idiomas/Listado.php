<?php

namespace Tuqan\Pages\Idiomas;

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

        $db->consulta("SELECT id, nombre FROM idiomas ORDER BY id");

        $idiomas = [];
        while ($row = $db->coger_Fila()) {
            $idiomas[] = [
                'id'     => $row[0],
                'nombre' => $row[1],
            ];
        }
        $db->desconexion();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu'  => $sidebarMenu,
            'idiomas'      => $idiomas,
            'pageTitle'    => 'Idiomas',
            'UserTitle'    => gettext('sUsuario'),
            'UserName'     => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'  => $_SESSION['empresa'] ?? null,
            'UserEmail'    => $_SESSION['usuario_email'] ?? null,
            'UserFullName' => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('idiomas/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar Idiomas: " . $e->getMessage();
        }
    }
}
