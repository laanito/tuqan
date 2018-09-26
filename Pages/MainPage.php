<?php
/**
 * Main Page of the app
 *
 */

namespace Tuqan\Pages;

use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class MainPage
{
    private $config;

    /**
     * LoginUsuario constructor.
     */
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
            $template = $twig->load('main.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        $variables = array(
            'UserTitle' => gettext('sUsuario'),
            'UserName' =>  $_SESSION['nombreUsuario'],
        );
        return $template->render($variables);
    }
}
