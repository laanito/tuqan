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
    private $config;

    function __construct()
    {
        $this->config = new Config();
    }

    /**
     * @return string
     */
    public function ShowPage(){
        $loader = new Twig_Loader_Filesystem($this->config->template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => $this->config->cache_path,
        ));
        try {
            $template = $twig->load('notfound.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        return $template->render();
    }
}
