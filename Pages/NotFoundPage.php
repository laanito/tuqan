<?php

/**
 * Main 404 template
 */

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

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
        $loader = new Twig_Loader_Filesystem(Config::$template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => Config::$cache_path,
        ));
        try {
            $template = $twig->load('notfound.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        return $template->render();
    }
}
