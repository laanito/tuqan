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
    public function crea_Menu_Superior()
    {
        // Early exit if we don't have a logged-in session yet (prevents noise).
        if (!isset($_SESSION) || !isset($_SESSION['idioma']) || empty($_SESSION['idioma'])) {
            return '<!-- menu requires login session -->';
        }

        // For the modern landing page (Stage 8.3+), we prefer the reliable simple menu builder
        // that we fully control and that correctly uses the company DB host/port from session.
        // The legacy arbol_listas generator has internal DB connection assumptions that
        // break in the Docker environment even with real menu data loaded.
        if (!empty($_SESSION['db_host']) || !empty($_SESSION['loginempresa'])) {
            return $this->buildSimpleMenuHtml();
        }

        // Fallback to legacy generator only for non-company or very old flows.
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

            require_once __DIR__ . "/../HTML/TreeMenu.php";

            $oArbol = new arbol_listas($aDatos, 0);
            $oArbol->genera_arbol_menu();
            $sHtml = $oArbol->to_Html();

            if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                \Tuqan\Classes\TuqanLogger::debug('MainPage legacy submenu output', [
                    'length' => strlen($sHtml ?? ''),
                    'starts_with' => substr($sHtml ?? '', 0, 150)
                ]);
            }

            if (empty(trim(strip_tags($sHtml ?? ''))) || strlen($sHtml) < 20) {
                return $this->buildSimpleMenuHtml();
            }

            return $sHtml;

        } catch (\Throwable $e) {
            error_log("TUQAN_DIAG: MainPage crea_Menu_Superior EXCEPTION - " . $e->getMessage());
            error_log("TUQAN_DIAG: Session at menu error: loginempresa=" . ($_SESSION['loginempresa'] ?? 'n/a') .
                      " db=" . ($_SESSION['db'] ?? 'n/a') .
                      " idioma=" . ($_SESSION['idioma'] ?? 'n/a'));

            if (class_exists('\Tuqan\Classes\TuqanLogger')) {
                \Tuqan\Classes\TuqanLogger::debug('MainPage menu generator threw', [
                    'reason' => $e->getMessage(),
                    'session_keys' => array_keys($_SESSION ?? [])
                ]);
            }
            return '<ul class="nav navbar-nav"><li><a href="#" title="Menú no disponible">(Menú)</a></li></ul>';
        }
    }

    /**
     * Simple server-rendered menu as a bridge until the legacy tree menu
     * is fully integrated with the modern Bootstrap landing.
     * Uses the real data we loaded via incremental patches.
     */
    private function buildSimpleMenuHtml(): string
    {
        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        $db->consulta(
            "SELECT m.id, m.padre, mi.valor, m.accion 
             FROM menu_nuevo m
             JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = " . intval($_SESSION['idioma'] ?? 1) . "
             WHERE m.activo = true
             ORDER BY m.orden"
        );

        $items = [];
        while ($row = $db->coger_Fila()) {
            $accion = $row[3] ?? '';
            $items[] = [
                'id'    => $row[0],
                'padre' => $row[1],
                'label' => $row[2],
                'url'   => $this->resolveLegacyAction($accion)
            ];
        }
        $db->desconexion();

        if (empty($items)) {
            return '<ul class="nav navbar-nav"><li><a href="#">(Sin menú)</a></li></ul>';
        }

        // Group by parent for easy tree building
        $byParent = [];
        foreach ($items as $it) {
            $byParent[$it['padre']][] = $it;
        }

        // Build a collapsible menu using Bootstrap collapse (already loaded)
        // This gives immediate expand/collapse without reviving the heavy old JS tree
        $build = function($parentId, $level = 0) use (&$build, $byParent) {
            if (!isset($byParent[$parentId])) return '';

            $isRoot = ($level === 0);
            $html = $isRoot ? '<ul class="nav navbar-nav">' : '<ul class="nav">';

            foreach ($byParent[$parentId] as $it) {
                $hasChildren = isset($byParent[$it['id']]);
                $id = 'menu-' . $it['id'];

                $html .= '<li>';

                if ($hasChildren) {
                    $html .= '<a href="#' . $id . '" data-toggle="collapse" aria-expanded="false" class="collapsed">';
                    $html .= htmlspecialchars($it['label']);
                    $html .= ' <span class="caret"></span></a>';
                    $html .= '<div id="' . $id . '" class="collapse">';
                    $html .= $build($it['id'], $level + 1);
                    $html .= '</div>';
                } else {
                    $html .= '<a href="' . htmlspecialchars($it['url']) . '">';
                    $html .= htmlspecialchars($it['label']);
                    $html .= '</a>';
                }

                $html .= '</li>';
            }

            $html .= '</ul>';
            return $html;
        };

        return $build(0);
    }

    /**
     * Builds a vertical, collapsible sidebar menu suitable for the full legacy menu.
     * Designed for a left sidebar layout.
     */
    public function buildSidebarMenuHtml(): string
    {
        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        $db->consulta(
            "SELECT m.id, m.padre, mi.valor, m.accion 
             FROM menu_nuevo m
             JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = " . intval($_SESSION['idioma'] ?? 1) . "
             WHERE m.activo = true
             ORDER BY m.orden"
        );

        $items = [];
        while ($row = $db->coger_Fila()) {
            $accion = $row[3] ?? '';
            $items[] = [
                'id'    => $row[0],
                'padre' => $row[1],
                'label' => $row[2],
                'url'   => $this->resolveLegacyAction($accion)
            ];
        }
        $db->desconexion();

        if (empty($items)) {
            return '<div class="sidebar-empty">No hay menú disponible</div>';
        }

        // Group by parent
        $byParent = [];
        foreach ($items as $it) {
            $byParent[$it['padre']][] = $it;
        }

        $build = function($parentId, $level = 0) use (&$build, $byParent) {
            if (!isset($byParent[$parentId])) return '';

            $html = '<ul class="sidebar-menu' . ($level > 0 ? ' sidebar-submenu' : '') . '">';

            foreach ($byParent[$parentId] as $it) {
                $hasChildren = isset($byParent[$it['id']]);
                $itemId = 'sidebar-item-' . $it['id'];

                $html .= '<li class="sidebar-item' . ($hasChildren ? ' has-children' : '') . '">';

                if ($hasChildren) {
                    // Always render collapsed by default.
                    // Actual open state is restored client-side from localStorage.
                    $html .= '<a href="#' . $itemId . '" class="sidebar-link" data-toggle="collapse" aria-expanded="false">';
                    $html .= '<span class="sidebar-label">' . htmlspecialchars($it['label']) . '</span>';
                    $html .= '<span class="sidebar-caret"></span>';
                    $html .= '</a>';
                    $html .= '<div id="' . $itemId . '" class="collapse">';
                    $html .= $build($it['id'], $level + 1);
                    $html .= '</div>';
                } else {
                    $html .= '<a href="' . htmlspecialchars($it['url']) . '" class="sidebar-link">';
                    $html .= '<span class="sidebar-label">' . htmlspecialchars($it['label']) . '</span>';
                    $html .= '</a>';
                }

                $html .= '</li>';
            }

            $html .= '</ul>';
            return $html;
        };

        return $build(0);
    }

    /**
     * Translates legacy menu "accion" values (e.g. "administracion:usuarios:listado")
     * into real Phroute URLs by replacing colons with slashes.
     * This is the bridge while we modernize modules.
     */
    private function resolveLegacyAction(string $accion): string
    {
        if (empty($accion) || $accion === '#') {
            return '#';
        }

        // Direct paths (already start with /) are used as-is
        if (str_starts_with($accion, '/')) {
            return $accion;
        }

        // The vast majority of legacy actions are colon-separated.
        // We can directly turn them into clean paths.
        $path = '/' . str_replace(':', '/', $accion);

        return $path;
    }

    /**
     * @return string
     */
    public function ShowPage(){
        error_log("TUQAN_DIAG: MainPage ShowPage reached - loginempresa=" . ($_SESSION['loginempresa'] ?? 'NOT SET') . 
                  ", usuarioconectado=" . ($_SESSION['usuarioconectado'] ?? 'NOT SET') .
                  ", nombreUsuario=" . ($_SESSION['nombreUsuario'] ?? 'NOT SET'));

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);
        try {
            $template = $twig->load('main.twig');
        } catch (\Exception $e) {
            return ("Error al cargar plantilla: " . $e->getMessage());
        }
        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));
        $variables = array(
            'UserTitle' => gettext('sUsuario'),
            'UserName' =>  $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName' => $_SESSION['empresa'] ?? null,
            'UserEmail' => $_SESSION['usuario_email'] ?? null,
            'UserFullName' => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
            'sidebarMenu' => $this->buildSidebarMenuHtml(),
            // Placeholder content for the minimal landing page
            'LandingMessage' => 'Bienvenido a Tuqan (versión mínima).<br>Has iniciado sesión correctamente. Usa el menú superior o el enlace de cerrar sesión.',
        );
        return $template->render($variables);
    }
}
