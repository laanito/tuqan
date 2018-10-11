<?php
/**
 * Main Page of the app
 *
 */

namespace Tuqan\Pages;

use Tuqan\Classes\arbol_listas;
use Tuqan\Classes\Config;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class MainPage
{

    /**
     * LoginUsuario constructor.
     */
    function __construct()
    {
        Config::initialize();
    }

    /**
     * Esta funcion devuelve el menu superior de calidad o medioambiente
     * @return String
     */
    private function crea_Menu_Superior()
    {
        $aDatos['pkey'] = 'menu_nuevo.id';
        $aDatos['padre'] = 'menu_nuevo.padre';
        $aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
        $aDatos['accion'] = 'menu_nuevo.accion';
        $aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo', 'idiomas');
        $aDatos['order'] = 'orden ASC';
        $sCondicion = "menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=idiomas.id " . "
    and idiomas.id='" . $_SESSION['idioma'] . "'";

        if ($_SESSION['admin'] == true || $_SESSION['perfil'] == '0') {

        } else {
            $sCondicion .= " and menu_nuevo.permisos[" . $_SESSION['perfil'] . "]=true";
        }

        $aDatos['condicion'] = $sCondicion;
        $oArbol = new arbol_listas($aDatos, 0);
        $oArbol->genera_arbol_menu();
        $sHtml = "submenu|";
        $sHtml .= $oArbol->to_Html();
        return $sHtml;
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
            $template = $twig->load('main.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        $variables = array(
            'UserTitle' => gettext('sUsuario'),
            'UserName' =>  $_SESSION['nombreUsuario'],
            'submenu' => $this->crea_Menu_Superior(),
        );
        return $template->render($variables);
    }
}
