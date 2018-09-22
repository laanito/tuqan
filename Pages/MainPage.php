<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 23/09/2018
 * Time: 1:05
 */

namespace Tuqan\Pages;


class MainPage
{

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
        return $template->render($this->Formulario());
    }
}
