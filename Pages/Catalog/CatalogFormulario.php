<?php

namespace Tuqan\Pages\Catalog;

use Tuqan\Classes\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Shared base for simple catalog Formulario pages (create/edit nombre + activo).
 * Subclasses declare the protected config + implement any custom validation if needed.
 * Preserves exact original flash keys, redirects, error messages, and template variables.
 */
abstract class CatalogFormulario
{
    protected string $table;
    protected string $title;           // used for page titles and error messages
    protected string $templateDir;     // e.g. 'tiposmejora', 'clientes'
    protected string $flashPrefix;     // e.g. 'tipomejora', 'cliente'
    protected string $listRoute;       // e.g. '/admin/tipos-mejora', '/admin/clientes'

    /**
     * Override for modules that need custom error messages (e.g. "El nombre del cliente es obligatorio").
     */
    protected function getNombreRequiredMessage(): string
    {
        return 'El nombre es obligatorio.';
    }

    protected function getSuccessMessage(bool $isEdit): string
    {
        return $isEdit
            ? "{$this->title} actualizado correctamente."
            : "{$this->title} creado correctamente.";
    }

    public function ShowPage($id = null)
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $mainPage = new \Tuqan\Pages\MainPage();
        $sidebarMenu = $mainPage->buildSidebarMenuHtml();

        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;
        $item = null;

        if ($id > 0) {
            $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
            $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

            $db = new \Tuqan\Classes\Manejador_Base_Datos(
                $_SESSION['login'] ?? '',
                $_SESSION['pass'] ?? '',
                $_SESSION['db'] ?? '',
                $host,
                $port
            );

            $db->consultaPreparada(
                "SELECT id, nombre, activo FROM {$this->table} WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'     => $row[0],
                    'nombre' => $row[1],
                    'activo' => $row[2],
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            // singular key for the form template (e.g. 'tipomejora', 'cliente')
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nuevo {$this->title}",
            'UserTitle'     => gettext('sUsuario'),
            'UserName'      => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'   => $_SESSION['empresa'] ?? null,
            'UserEmail'     => $_SESSION['usuario_email'] ?? null,
            'UserFullName'  => $fullName ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
        ];

        try {
            $template = $twig->load($this->templateDir . '/formulario.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar el formulario de {$this->title}: " . $e->getMessage();
        }
    }

    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }

        Config::initialize();

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        }
        $id = (int)$id;

        $nombre = trim($_POST['nombre'] ?? '');
        $activo = !empty($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre === '') {
            $errors[] = $this->getNombreRequiredMessage();
        }

        if (!empty($errors)) {
            $_SESSION[$this->flashPrefix . '_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "{$this->listRoute}/editar/$id" : "{$this->listRoute}/nuevo";
            header("Location: $target");
            exit;
        }

        $host = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        if ($id > 0) {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, activo = ? WHERE id = ?",
                [$nombre, $activo, $id]
            );
            $msg = $this->getSuccessMessage(true);
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, activo) VALUES (?, ?)",
                [$nombre, $activo]
            );
            $msg = $this->getSuccessMessage(false);
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}