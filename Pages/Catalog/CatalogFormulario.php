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

    /**
     * Current logged-in user id for auto-assign in state transitions (Mejora etc.).
     * Falls back to 1 (demo seed user) if not present in session.
     */
    protected function getCurrentUserId(): int
    {
        return (int)($_SESSION['id_usuario'] ?? 1);
    }

    protected function getFlashPrefix(): string
    {
        return $this->flashPrefix;
    }

    /**
     * Override for rich forms (different columns).
     */
    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, activo FROM {$this->table} WHERE id = ?";
    }

    protected function loadItem($id): ?array
    {
        if ($id <= 0) return null;
        $db = $this->getDb();
        $db->consultaPreparada($this->getSelectForForm(), [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) return null;

        return [
            'id'     => $row[0],
            'nombre' => $row[1],
            'activo' => $row[2],
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $context = $this->getUserContext();
        return array_merge([
            'sidebarMenu' => $this->getSidebarMenu(),
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nuevo {$this->title}",
        ], $context);
    }

    protected function getPostData(): array
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'activo' => !empty($_POST['activo']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = $this->getNombreRequiredMessage();
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        if ($id > 0) {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, activo = ? WHERE id = ?",
                [$data['nombre'], $data['activo'], $id]
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, activo) VALUES (?, ?)",
                [$data['nombre'], $data['activo']]
            );
        }
        $db->desconexion();
    }

    public function ShowPage($id = null)
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;

        $item = $this->loadItem($id);
        $variables = $this->buildFormVariables($item);

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

        $data = $this->getPostData();

        $errors = $this->validate($data);
        if (!empty($errors)) {
            $_SESSION[$this->flashPrefix . '_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "{$this->listRoute}/editar/$id" : "{$this->listRoute}/nuevo";
            header("Location: $target");
            exit;
        }

        $this->persist($data, $id);

        $msg = $this->getSuccessMessage($id > 0);
        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }

    // --- Cross-cut relations helpers (Stage 9.15 + 9.17 polish) ---
    // form-with-relations support. Subclasses can call these for FK labels / related data.
    // loadRelated/getRelatedLabel also available in CatalogListado (promoted 9.17).
    // getRelatedOptions added for easy <select> population.

    protected function loadRelated(string $table, $id, array $columns = ['id', 'nombre']): ?array
    {
        if (!$id) return null;
        $cols = implode(', ', $columns);
        $db = $this->getDb();
        $db->consultaPreparada("SELECT {$cols} FROM {$table} WHERE id = ?", [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) return null;

        $result = [];
        foreach ($columns as $i => $col) {
            $result[$col] = $row[$i] ?? null;
        }
        return $result;
    }

    protected function getRelatedLabel(string $table, $id, string $labelCol = 'nombre'): ?string
    {
        $row = $this->loadRelated($table, $id, ['id', $labelCol]);
        return $row[$labelCol] ?? null;
    }

    /**
     * Fetch simple id + label pairs for populating <select> dropdowns for FKs.
     * Subclasses can pass the result as e.g. 'tipo_options' to templates.
     */
    protected function getRelatedOptions(string $table, string $labelCol = 'nombre', string $orderBy = 'nombre ASC'): array
    {
        $db = $this->getDb();
        $db->consulta("SELECT id, {$labelCol} FROM {$table} ORDER BY {$orderBy}");
        $opts = [];
        while ($row = $db->coger_Fila()) {
            $opts[] = ['id' => $row[0], $labelCol => $row[1]];
        }
        $db->desconexion();
        return $opts;
    }
}