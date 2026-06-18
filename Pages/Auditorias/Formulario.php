<?php
namespace Tuqan\Pages\Auditorias;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'programa_auditoria';
    protected string $title        = 'Programas de Auditoría';
    protected string $templateDir  = 'auditorias';
    protected string $flashPrefix  = 'auditorias';
    protected string $listRoute    = '/admin/auditorias';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, vigente, activo, revision FROM {$this->table} ORDER BY id";
    }

    public function ShowPage($id = null)
    {
        \Tuqan\Classes\Config::initialize();

        $loader = new \Twig\Loader\FilesystemLoader(\Tuqan\Classes\Config::$template_path);
        $twig = new \Twig\Environment($loader, [
            'cache' => \Tuqan\Classes\Config::$cache_path,
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

            $sql = "SELECT id, nombre, vigente, activo, revision FROM {$this->table} WHERE id = ?";
            $db->consultaPreparada($sql, [$id]);
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'       => $row[0],
                    'nombre'   => $row[1],
                    'vigente'  => $row[2],
                    'activo'   => $row[3],
                    'revision' => $row[4] ?? null,
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nuevo Programa de Auditoría",
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

        \Tuqan\Classes\Config::initialize();

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : (isset($_GET['id']) ? (int)$_GET['id'] : null);
        }
        $id = (int)$id;

        $nombre = trim($_POST['nombre'] ?? '');
        $revision = trim($_POST['revision'] ?? '');
        $vigente = !empty($_POST['vigente']) ? 1 : 0;
        $activo = !empty($_POST['activo']) ? 1 : 0;

        $errors = [];
        if ($nombre === '') {
            $errors[] = 'El nombre del programa es obligatorio.';
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
                "UPDATE {$this->table} SET nombre = ?, vigente = ?, activo = ?, revision = ? WHERE id = ?",
                [$nombre, $vigente, $activo, $revision, $id]
            );
            $msg = $this->getSuccessMessage(true);
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, vigente, activo, revision) VALUES (?, ?, ?, ?)",
                [$nombre, $vigente, $activo, $revision]
            );
            $msg = $this->getSuccessMessage(false);
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}
