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

        $db->consulta($this->getSelectSql());

        $items = [];
        while ($row = $db->coger_Fila()) {
            $items[] = $this->mapRow($row);
        }
        $db->desconexion();

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $flashSuccess = $_SESSION[$this->flashPrefix . '_flash_success'] ?? null;
        $flashError   = $_SESSION[$this->flashPrefix . '_form_error'] ?? null;
        unset($_SESSION[$this->flashPrefix . '_flash_success'], $_SESSION[$this->flashPrefix . '_form_error']);

        // The template variable name is usually the same as templateDir (e.g. 'tiposmejora', 'clientes')
        $itemVar = $this->templateDir;

        $variables = [
            'sidebarMenu'   => $sidebarMenu,
            $itemVar        => $items,
            'pageTitle'     => $this->title,
            'flashSuccess'  => $flashSuccess,
            'flashError'    => $flashError,
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load($this->templateDir . '/listado.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }
}