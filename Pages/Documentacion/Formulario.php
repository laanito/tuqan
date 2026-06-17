<?php
namespace Tuqan\Pages\Documentacion;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'documentos';
    protected string $title        = 'Documentación';
    protected string $templateDir  = 'documentacion';
    protected string $flashPrefix  = 'documento';
    protected string $listRoute    = '/admin/documentacion';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente FROM {$this->table} ORDER BY id";
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

            $sql = "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente FROM {$this->table} WHERE id = ?";
            $db->consultaPreparada($sql, [$id]);
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'            => $row[0],
                    'nombre'        => $row[1],
                    'codigo'        => $row[2] ?? null,
                    'estado'        => $row[3] ?? null,
                    'revision'      => $row[4] ?? null,
                    'activo'        => $row[5],
                    'calidad'       => $row[6],
                    'medioambiente' => $row[7],
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nuevo Documento",
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
        $codigo = trim($_POST['codigo'] ?? '');
        $estado = isset($_POST['estado']) && $_POST['estado'] !== '' ? (int)$_POST['estado'] : null;
        $revision = trim($_POST['revision'] ?? '');
        $activo = !empty($_POST['activo']) ? 1 : 0;
        $calidad = !empty($_POST['calidad']) ? 1 : 0;
        $medioambiente = !empty($_POST['medioambiente']) ? 1 : 0;

        $errors = [];
        if ($nombre === '') {
            $errors[] = 'El nombre del documento es obligatorio.';
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
                "UPDATE {$this->table} SET nombre = ?, codigo = ?, estado = ?, revision = ?, activo = ?, calidad = ?, medioambiente = ? WHERE id = ?",
                [$nombre, $codigo, $estado, $revision, $activo, $calidad, $medioambiente, $id]
            );
            $msg = $this->getSuccessMessage(true);
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, codigo, estado, revision, activo, calidad, medioambiente) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$nombre, $codigo, $estado, $revision, $activo, $calidad, $medioambiente]
            );
            $msg = $this->getSuccessMessage(false);
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}
