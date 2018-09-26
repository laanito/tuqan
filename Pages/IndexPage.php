<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class IndexPage {

    public function MuestraPagina()
    {
        Config::initialize();
        $loader = new Twig_Loader_Filesystem(Config::$template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => Config::$cache_path,
        ));
        try {
            $template = $twig->load('index.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: ".$e->getMessage());
        }
        return $template->render(array());
    }
}
