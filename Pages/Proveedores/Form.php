<?php

namespace Tuqan\Pages\Proveedores;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Form extends CatalogFormulario
{
    protected string $table          = 'proveedores';
    protected string $title          = 'Proveedores';
    protected string $templateDir  = 'proveedores';
    protected string $flashPrefix  = 'proveedor';
    protected string $listRoute    = '/admin/proveedores';

    /**
     * Override to include telefono field in the query.
     */
    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, telefono, activo FROM {$this->table} ORDER BY id";
    }

    public function ShowPage($id = null)
    {
        \Tuqan\Classes\Config::initialize();

        $loader = new \Twig\Loader\FilesystemLoader(\Tuqan\Classes\Config::$template_path);
        $twig   = new \Twig\Environment($loader, [
            'cache' => \Tuqan\Classes\Config::$cache_path,
        ]);

        $mainPage     = new \Tuqan\Pages\MainPage();
        $sidebarMenu  = $mainPage->buildSidebarMenuHtml();

        if ($id === null) {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        }
        $id = (int)$id;
        $item = null;

        if ($id > 0) {
            $host    = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
            $port    = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

            $db      = new \Tuqan\Classes\Manejador_Base_Datos(
                $_SESSION['login'] ?? '',
                $_SESSION['pass'] ?? '',
                $_SESSION['db'] ?? '',
                $host,
                $port
            );

            $db->consultaPreparada(
                "SELECT id, nombre, telefono FROM {$this->table} WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'        => $row[0],
                    'nombre'    => $row[1],
                    'telefono'  => $row[2] ?? null,
                ];
            }
            $db->desconexion();
        }

        $variables = [
            'sidebarMenu'   => $sidebarMenu,
            'proveedor'     => $item,
            'isEdit'        => (bool)$item,
            'pageTitle'     => $item ? "Editar {$this->title}" : "Nuevo {$this->title}",
            'UserTitle'      => gettext('sUsuario'),
            'UserName'       => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'    => $_SESSION['empresa'] ?? null,
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

        \Tuqan\Classes\Config::initialize();

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        }
        $id = (int)$id;

        $nombre   = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $activo    = !empty($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre === '') {
            $errors[] = 'El nombre del proveedor es obligatorio.';
        }

        if (!empty($errors)) {
            $_SESSION[$this->flashPrefix . '_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "{$this->listRoute}/editar/$id" : "{$this->listRoute}/nuevo";
            header("Location: $target");
            exit;
        }

        $host    = $_SESSION['db_host'] ?? (getenv('DB_HOST') ?: 'localhost');
        $port    = $_SESSION['db_port'] ?? (int)(getenv('DB_PORT') ?: 5432);

        $db      = new \Tuqan\Classes\Manejador_Base_Datos(
            $_SESSION['login'] ?? '',
            $_SESSION['pass'] ?? '',
            $_SESSION['db'] ?? '',
            $host,
            $port
        );

        if ($id > 0) {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, telefono = ? WHERE id = ?",
                [$nombre, $telefono, $id]
            );
            $msg = "{$this->title} actualizado correctamente.";
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, telefono) VALUES (?, ?)",
                [$nombre, $telefono]
            );
            $msg = "{$this->title} creado correctamente.";
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}
