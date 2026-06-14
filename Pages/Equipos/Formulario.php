<?php

namespace Tuqan\Pages\Equipos;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'equipos';
    protected string $title        = 'Equipos';
    protected string $templateDir  = 'equipos';
    protected string $flashPrefix  = 'equipo';
    protected string $listRoute    = '/admin/equipos';

    /**
     * Override to fetch the richer set of columns for the edit form.
     */
    protected function getSelectSql(): string
    {
        return "SELECT id, numero, descripcion, numero_serie, modelo, fabricante, ubicacion, activo FROM {$this->table} ORDER BY id";
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
                "SELECT id, numero, descripcion, numero_serie, modelo, fabricante, ubicacion, activo FROM {$this->table} WHERE id = ?",
                [$id]
            );
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'            => $row[0],
                    'numero'        => $row[1],
                    'descripcion'   => $row[2],
                    'numero_serie'  => $row[3] ?? null,
                    'modelo'        => $row[4] ?? null,
                    'fabricante'    => $row[5] ?? null,
                    'ubicacion'     => $row[6] ?? null,
                    'activo'        => $row[7],
                ];
            }
            $db->desconexion();
        }

        $variables = [
            'sidebarMenu'   => $sidebarMenu,
            'equipo'        => $item,
            'isEdit'        => (bool)$item,
            'pageTitle'     => $item ? "Editar {$this->title}" : "Nuevo {$this->title}",
            'UserTitle'      => gettext('sUsuario'),
            'UserName'       => $_SESSION['nombreUsuario'] ?? 'Guest',
            'CompanyName'    => $_SESSION['empresa'] ?? null,
            'UserEmail'      => $_SESSION['usuario_email'] ?? null,
            'UserFullName'   => trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? '')) ?: ($_SESSION['nombreUsuario'] ?? 'Guest'),
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

        $numero        = trim($_POST['numero'] ?? '');
        $descripcion   = trim($_POST['descripcion'] ?? '');
        $numero_serie  = trim($_POST['numero_serie'] ?? '');
        $modelo        = trim($_POST['modelo'] ?? '');
        $fabricante    = trim($_POST['fabricante'] ?? '');
        $ubicacion     = trim($_POST['ubicacion'] ?? '');
        $activo        = !empty($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($numero === '') {
            $errors[] = 'El número de control es obligatorio.';
        }
        if ($descripcion === '') {
            $errors[] = 'La descripción es obligatoria.';
        }
        if ($numero_serie === '') {
            $errors[] = 'El número de serie es obligatorio.';
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
                "UPDATE {$this->table} SET numero = ?, descripcion = ?, numero_serie = ?, modelo = ?, fabricante = ?, ubicacion = ?, activo = ? WHERE id = ?",
                [$numero, $descripcion, $numero_serie, $modelo, $fabricante, $ubicacion, $activo, $id]
            );
            $msg = "{$this->title} actualizado correctamente.";
        } else {
            // Supply defaults for maintenance columns not exposed in this first-slice form
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (numero, descripcion, numero_serie, modelo, fabricante, ubicacion, ver_interna, mantenimiento_cada, dias, activo) VALUES (?, ?, ?, ?, ?, ?, false, 90, false, ?)",
                [$numero, $descripcion, $numero_serie, $modelo, $fabricante, $ubicacion, $activo]
            );
            $msg = "{$this->title} creado correctamente.";
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}