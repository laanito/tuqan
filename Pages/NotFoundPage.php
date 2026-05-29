<?php

/**
 * Main 404 template
 */

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class NotFoundPage
{
    function __construct()
    {
        Config::initialize();
    }

    /**
     * @return string
     */
    public function ShowPage(){
        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);
        try {
            $template = $twig->load('notfound.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        return $template->render();
    }
}
