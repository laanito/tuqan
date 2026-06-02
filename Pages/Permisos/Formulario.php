<?php

namespace Tuqan\Pages\Permisos;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Formulario
{
    public function ShowPage($id = null)
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $perfilId = (int)($id ?? 0);
        $perfilNombre = 'Perfil ' . $perfilId;
        $menus = [];

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        // Load the profile name
        $db->consultaPreparada("SELECT nombre FROM perfiles WHERE id = ?", [$perfilId]);
        if ($r = $db->coger_Fila()) {
            $perfilNombre = $r[0];
        }

        // Load relevant menus (top level + under Aplicacion for a useful matrix; limit to keep UI small)
        $db->consulta("
            SELECT m.id, 
                   COALESCE((SELECT valor FROM menu_idiomas_nuevo mi WHERE mi.menu = m.id AND mi.idioma_id = 1), m.accion) as label,
                   m.permisos,
                   m.accion
            FROM menu_nuevo m
            WHERE m.activo = true 
              AND (m.padre IS NULL OR m.padre = 0 OR m.padre = 82)
            ORDER BY m.orden, m.id
            LIMIT 25
        ");

        while ($row = $db->coger_Fila()) {
            $permStr = trim($row[2] ?? '', '{}');
            $parts = $permStr ? explode(',', $permStr) : [];
            $can = false;
            if (isset($parts[$perfilId])) {
                $can = strtolower(trim($parts[$perfilId], " '")) === 't';
            }
            $menus[] = [
                'id'    => $row[0],
                'label' => $row[1],
                'can'   => $can,
                'accion'=> $row[3],
            ];
        }

        $db->desconexion();

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            'pageTitle'   => 'Permisos de Perfil: ' . $perfilNombre,
            'perfilId'    => $perfilId,
            'perfilNombre'=> $perfilNombre,
            'menus'       => $menus,
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('permisos/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Basic matrix POST: receives perfil + array of menu ids that should be allowed.
     * Rebuilds the permisos text array for each affected menu (supports small number of profiles 0/1).
     */
    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: /admin/permisos');
            exit;
        }

        $perfilId = (int)($id ?? $_POST['perfil_id'] ?? 0);
        $allowedMenus = isset($_POST['allowed']) ? array_map('intval', (array)$_POST['allowed']) : [];

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        // For each menu that has a permisos column, update the bit for this perfilId
        // We fetch current, flip the bit at $perfilId position, write back.
        $db->consulta("SELECT id, permisos FROM menu_nuevo WHERE activo = true LIMIT 100");
        while ($row = $db->coger_Fila()) {
            $mid = (int)$row[0];
            $permStr = trim($row[1] ?? '', '{}');
            $parts = $permStr ? explode(',', $permStr) : [];
            // ensure enough slots (for 2 profiles)
            while (count($parts) <= $perfilId) $parts[] = 'f';

            $parts[$perfilId] = in_array($mid, $allowedMenus, true) ? 't' : 'f';

            $newPerm = '{' . implode(',', $parts) . '}';
            $db2 = new \Tuqan\Classes\Manejador_Base_Datos( // small reuse, one conn per is fine for this
                $_SESSION['login'] ?? '',
                $_SESSION['pass'] ?? '',
                $_SESSION['db'] ?? '',
                $host,
                $port
            );
            $db2->consultaPreparada("UPDATE menu_nuevo SET permisos = ? WHERE id = ?", [$newPerm, $mid]);
            $db2->desconexion();
        }

        $db->desconexion();

        $_SESSION['permiso_flash_success'] = 'Permisos actualizados para el perfil.';
        header('Location: /admin/permisos/editar/' . $perfilId);
        exit;
    }
}
