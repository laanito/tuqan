<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class LegacyAction
{
    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        // Support both ?action=... and being called directly via catch-all route
        $action = $_GET['action'] ?? '';
        if (empty($action)) {
            // When hit via the catch-all route, the path itself is the legacy action
            $action = ltrim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        }

        // Properly compute the menu so navigation doesn't break
        $mainPage = new \Tuqan\Pages\MainPage();
        $submenu = $mainPage->crea_Menu_Superior();

        $variables = [
            'UserTitle' => 'Usuario',
            'UserName'  => $_SESSION['nombreUsuario'] ?? 'Guest',
            'submenu'   => $submenu,
            'LandingMessage' => '<div class="alert alert-info" style="max-width: 700px;">
                <h4>Acción legacy</h4>
                <p>Acción no modernizada todavía: <code>' . htmlspecialchars($action) . '</code></p>
                <p>Este módulo se implementará siguiendo la estructura del menú.</p>
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
