<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Placeholder
{
    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        // Properly compute the menu so navigation doesn't break on placeholder pages
        $mainPage = new \Tuqan\Pages\MainPage();
        $submenu = $mainPage->crea_Menu_Superior();

        $variables = [
            'UserTitle' => 'Usuario',
            'UserName'  => $_SESSION['nombreUsuario'] ?? 'Guest',
            'submenu'   => $submenu,
            'LandingMessage' => '<div class="alert alert-warning" style="max-width: 700px;">
                <h4>Módulo en desarrollo</h4>
                <p>Este módulo está siendo modernizado. Pronto tendrá contenido real siguiendo la estructura del menú.</p>
            </div>',
        ];

        try {
            $template = $twig->load('main.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
