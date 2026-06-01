<?php

namespace Tuqan\Pages\Permisos;

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

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            'pageTitle'   => 'Permisos de Perfil',
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
            'message'     => 'La edición de la matriz de permisos (qué menús ve cada perfil) se completará cuando activemos el soporte de formularios POST.',
        ];

        try {
            $template = $twig->load('permisos/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
