<?php
/**
 * Main Page of the app
 *
 */

namespace Tuqan\Pages;

use Tuqan\Classes\arbol_listas;
use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

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
        // Defensive guard for bare-minimum home page (no active login/session yet).
        // With the minimal seed we only have the company/user rows; full menu requires
        // a logged-in session with 'idioma', 'perfil', etc. Returning early produces
        // a clean render instead of undefined $_SESSION warnings and DB handler noise.
        if (!isset($_SESSION) || !isset($_SESSION['idioma']) || empty($_SESSION['idioma'])) {
            return '<!-- menu requires login session -->';
        }

        // Defensive fallback for the bare-minimum / minimal-seed phase.
        // The legacy arbol_listas + menu_nuevo query will fail (or produce noise)
        // until the full menu tables and data are present in the DB.
        // We catch any failure here so /main/ can still render the useful landing
        // page instead of exploding into the NotFound cloud animation.
        // Once a real DB with menu data is used, the normal path will work unchanged.
        try {
            $aDatos['pkey'] = 'menu_nuevo.id';
            $aDatos['padre'] = 'menu_nuevo.padre';
            $aDatos['etiqueta'] = 'menu_idiomas_nuevo.valor';
            $aDatos['accion'] = 'menu_nuevo.accion';
            $aDatos['tablas'] = array('menu_nuevo', 'menu_idiomas_nuevo', 'idiomas');
            $aDatos['order'] = 'orden ASC';
            $sCondicion = "menu_nuevo.id=menu_idiomas_nuevo.menu and menu_idiomas_nuevo.idioma_id=idiomas.id " .
                "and idiomas.id='" . $_SESSION['idioma'] . "'";
            if (!($_SESSION['admin'] == true || $_SESSION['perfil'] == '0')) {
                $sCondicion .= " and menu_nuevo.permisos[" . $_SESSION['perfil'] . "]=true";
            }
            $aDatos['condicion'] = $sCondicion;
            $oArbol = new arbol_listas($aDatos, 0);
            $oArbol->genera_arbol_menu();
            $sHtml = $oArbol->to_Html();

            // If the legacy builder produced nothing usable (common with minimal seed),
            // treat it as "DB not ready" and fall back.
            if (empty(trim(strip_tags($sHtml ?? '')))) {
                throw new \Exception('Legacy menu builder returned empty result on minimal DB');
            }

            return $sHtml;

        } catch (\Throwable $e) {
            // Log at debug level so developers see why the menu is minimal,
            // but never let it break the landing page or generate Xdebug noise.
            if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                \Tuqan\Classes\TuqanLogger::debug('MainPage menu fallback triggered', [
                    'reason' => $e->getMessage(),
                    'session_keys' => array_keys($_SESSION ?? [])
                ]);
            }
            return '<ul class="nav navbar-nav"><li><a href="#" title="Menú completo disponible cuando la base de datos esté poblada">(Menú)</a></li></ul>';
        }
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
            $template = $twig->load('main.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        $variables = array(
            'UserTitle' => gettext('sUsuario'),
            'UserName' =>  $_SESSION['nombreUsuario'] ?? 'Guest',
            'submenu' => $this->crea_Menu_Superior(),
            // Placeholder content for the minimal landing page
            'LandingMessage' => 'Bienvenido a Tuqan (versión mínima).<br>Has iniciado sesión correctamente. Usa el menú superior o el enlace de cerrar sesión.',
        );
        return $template->render($variables);
    }
}
