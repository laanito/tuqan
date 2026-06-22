<?php
namespace Tuqan\Pages\Aspectos;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'aspectos';
    protected string $title        = 'Aspectos Ambientales';
    protected string $templateDir  = 'aspectos';
    protected string $flashPrefix  = 'aspectos';
    protected string $listRoute    = '/admin/aspectos';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones FROM {$this->table} ORDER BY id";
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

            $sql = "SELECT id, nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones FROM {$this->table} WHERE id = ?";
            $db->consultaPreparada($sql, [$id]);
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'           => $row[0],
                    'nombre'       => $row[1],
                    'magnitud'     => $row[2],
                    'gravedad'     => $row[3],
                    'frecuencia'   => $row[4],
                    'tipo_aspecto' => $row[5],
                    'activo'       => $row[6],
                    'impacto'      => $row[7],
                    'probabilidad' => $row[8],
                    'severidad'    => $row[9],
                    'area'         => $row[10] ?? null,
                    'observaciones'=> $row[11] ?? null,
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nuevo Aspecto Ambiental",
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

        $nombre        = trim($_POST['nombre'] ?? '');
        $magnitud      = isset($_POST['magnitud']) ? (int)$_POST['magnitud'] : 0;
        $gravedad      = isset($_POST['gravedad']) ? (int)$_POST['gravedad'] : 0;
        $frecuencia    = isset($_POST['frecuencia']) ? (int)$_POST['frecuencia'] : 0;
        $tipo_aspecto  = isset($_POST['tipo_aspecto']) ? (int)$_POST['tipo_aspecto'] : 0;
        $activo        = !empty($_POST['activo']) ? 1 : 0;
        $impacto       = isset($_POST['impacto']) ? (int)$_POST['impacto'] : 0;
        $probabilidad  = isset($_POST['probabilidad']) ? (int)$_POST['probabilidad'] : 0;
        $severidad     = isset($_POST['severidad']) ? (int)$_POST['severidad'] : 0;
        $area          = trim($_POST['area'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        $errors = [];
        if ($nombre === '') {
            $errors[] = 'El nombre del aspecto es obligatorio.';
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
                "UPDATE {$this->table} SET nombre = ?, magnitud = ?, gravedad = ?, frecuencia = ?, tipo_aspecto = ?, activo = ?, impacto = ?, probabilidad = ?, severidad = ?, area = ?, observaciones = ? WHERE id = ?",
                [$nombre, $magnitud, $gravedad, $frecuencia, $tipo_aspecto, $activo, $impacto, $probabilidad, $severidad, $area, $observaciones, $id]
            );
            $msg = $this->getSuccessMessage(true);
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$nombre, $magnitud, $gravedad, $frecuencia, $tipo_aspecto, $activo, $impacto, $probabilidad, $severidad, $area, $observaciones]
            );
            $msg = $this->getSuccessMessage(false);
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}
