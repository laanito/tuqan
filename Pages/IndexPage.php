<?php

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class IndexPage {

    public function MuestraPagina()
    {
        $config= new Config();
        $loader = new Twig_Loader_Filesystem($config->template_path);
        $twig = new Twig_Environment($loader, array(
            'cache' => $config->cache_path,
        ));
        try {
            $template = $twig->load('index.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: ".$e->getMessage());
        }
        return $template->render(array());
    }
}