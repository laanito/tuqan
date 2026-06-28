<?php

namespace Tuqan\Pages\Catalog;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Shared base for simple catalog Listado pages (id + nombre + activo pattern).
 * Subclasses only need to declare the protected config properties.
 * This eliminates ~50-60 lines of boilerplate per module while preserving
 * exact original behavior, flash keys, template variables, and error handling.
 *
 * For richer modules (extra columns, flags, dates, etc. as seen in 9.x):
 *   - Override getSelectSql() + mapRow()
 *   - The base ShowPage still handles DB, sidebar, Twig, flashes, user context.
 * Future cross-cut candidates: list-with-filters, etc.
 */
abstract class CatalogListado
{
    protected string $table;
    protected string $title;
    protected string $templateDir;   // e.g. 'tiposmejora', 'clientes'
    protected string $flashPrefix;   // e.g. 'tipomejora', 'cliente' (for _flash_success / _form_error)

    /**
     * Override in subclass if the SELECT or mapping is more complex (e.g. Usuarios join).
     */
    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'     => $row[0],
            'nombre' => $row[1],
            'activo' => $row[2],
        ];
    }

    // --- Cross-cut helpers (Stage 9.8) to reduce duplication in rich modules ---
    protected function getDb()
    {
        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        return new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );
    }

    protected function getSidebarMenu()
    {
        $mainPage = new \Tuqan\Pages\MainPage();
        return $mainPage->buildSidebarMenuHtml();
    }

    protected function getUserContext(): array
    {
        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));
        return [
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];
    }

    protected function getFlashData(): array
    {
        $success = $_SESSION[$this->flashPrefix . '_flash_success'] ?? null;
        $error   = $_SESSION[$this->flashPrefix . '_form_error'] ?? null;
        unset($_SESSION[$this->flashPrefix . '_flash_success'], $_SESSION[$this->flashPrefix . '_form_error']);
        return ['flashSuccess' => $success, 'flashError' => $error];
    }

    protected function fetchItems(): array
    {
        $db = $this->getDb();
        $db->consulta($this->getSelectSql());

        $items = [];
        while ($row = $db->coger_Fila()) {
            $items[] = $this->mapRow($row);
        }
        $db->desconexion();
        return $items;
    }

    protected function buildListVariables(array $items): array
    {
        $flash = $this->getFlashData();
        $context = $this->getUserContext();

        $itemVar = $this->templateDir;

        return array_merge([
            'sidebarMenu'   => $this->getSidebarMenu(),
            $itemVar        => $items,
            'pageTitle'     => $this->title,
            'flashSuccess'  => $flash['flashSuccess'],
            'flashError'    => $flash['flashError'],
        ], $context);
    }

    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $items = $this->fetchItems();
        $variables = $this->buildListVariables($items);

        try {
            $template = $twig->load($this->templateDir . '/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }

    // --- Cross-cut tree helpers (Stage 9.13 first delivery) ---
    // Common patterns extracted from Procesos/Arbol (9.11) and Documentacion/Arbol (9.12)
    // to reduce duplication for future tree views.

    protected function initTwig(): Environment
    {
        Config::initialize();
        $loader = new FilesystemLoader(Config::$template_path);
        return new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);
    }

    protected function buildCommonVariables(): array
    {
        $flash = $this->getFlashData();
        $context = $this->getUserContext();

        return array_merge([
            'sidebarMenu'   => $this->getSidebarMenu(),
            'pageTitle'     => $this->title,
            'flashSuccess'  => $flash['flashSuccess'],
            'flashError'    => $flash['flashError'],
            'isTreeView'    => true,
        ], $context);
    }

    /**
     * Resolve parent names for hierarchy (used by padre-based trees like Procesos).
     */
    protected function resolveParentNames(array $items, string $parentField = 'padre', string $nameField = 'nombre'): array
    {
        $byId = [];
        foreach ($items as &$item) {
            $item['parent_nombre'] = '';
            $byId[$item['id']] = &$item;
        }
        foreach ($items as &$item) {
            $pid = $item[$parentField] ?? 0;
            if ($pid > 0 && isset($byId[$pid])) {
                $item['parent_nombre'] = $byId[$pid][$nameField] ?? '';
            }
        }
        unset($item);
        return $items;
    }

    /**
     * Group items by a key (used for tree-like grouping by tipo/area like Documentación).
     */
    protected function groupItems(array $items, string $groupKey): array
    {
        $grouped = [];
        foreach ($items as $item) {
            $key = $item[$groupKey] ?? 0;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $item;
        }
        return $grouped;
    }
}