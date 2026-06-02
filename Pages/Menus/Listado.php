<?php

namespace Tuqan\Pages\Menus;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Listado
{
    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        // Basic view of the menu structure with Spanish labels
        $db->consulta("
            SELECT m.id, m.padre, m.orden, m.accion, mi.valor as label_es
            FROM menu_nuevo m
            LEFT JOIN menu_idiomas_nuevo mi ON mi.menu = m.id AND mi.idioma_id = 1
            ORDER BY m.padre NULLS FIRST, m.orden, m.id
        ");

        $menus = [];
        while ($row = $db->coger_Fila()) {
            $menus[] = [
                'id'       => $row[0],
                'padre'    => $row[1],
                'orden'    => $row[2],
                'accion'   => $row[3],
                'label_es' => $row[4],
            ];
        }
        $db->desconexion();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $flashSuccess = $_SESSION['menu_flash_success'] ?? null;
        $flashError   = $_SESSION['menu_form_error'] ?? null;
        unset($_SESSION['menu_flash_success'], $_SESSION['menu_form_error']);

        $variables = [
            'sidebarMenu'  => $sidebarMenu,
            'menus'        => $menus,
            'pageTitle'    => 'Menús (edición básica)',
            'flashSuccess' => $flashSuccess,
            'flashError'   => $flashError,
            'UserTitle'    => gettext('sUsuario'),
            'UserName'     => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'  => $_SESSION['empresa'] ?? null,
            'UserEmail'    => $_SESSION['usuario_email'] ?? null,
            'UserFullName' => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load('menus/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar Menús: " . $e->getMessage();
        }
    }

    /**
     * Basic POST handler for editing menu orden + Spanish labels (menu_idiomas_nuevo.valor).
     * Expects POST data like:
     *   orden[123] = 10
     *   label_es[123] = "Foo"
     */
    public function Procesar()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: /admin/menus');
            exit;
        }

        Config::initialize();

        $ordens = isset($_POST['orden']) ? (array)$_POST['orden'] : [];
        $labels = isset($_POST['label_es']) ? (array)$_POST['label_es'] : [];

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        $updated = 0;
        foreach ($ordens as $mid => $newOrden) {
            $mid = (int)$mid;
            $newOrden = (int)$newOrden;
            $db->consultaPreparada("UPDATE menu_nuevo SET orden = ? WHERE id = ?", [$newOrden, $mid]);
            $updated++;
        }

        foreach ($labels as $mid => $newLabel) {
            $mid = (int)$mid;
            $newLabel = trim((string)$newLabel);
            // upsert the Spanish label
            $db->consultaPreparada("
                INSERT INTO menu_idiomas_nuevo (menu, idioma_id, valor)
                VALUES (?, 1, ?)
                ON CONFLICT (menu, idioma_id) DO UPDATE SET valor = EXCLUDED.valor
            ", [$mid, $newLabel]);
        }

        $db->desconexion();

        $_SESSION['menu_flash_success'] = "Menús actualizados ($updated cambios de orden procesados).";
        header('Location: /admin/menus');
        exit;
    }
}
