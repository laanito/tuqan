<?php
namespace Tuqan\Pages\Mejora;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'acciones_mejora';
    protected string $title        = 'Acciones de Mejora';
    protected string $templateDir  = 'mejora';
    protected string $flashPrefix  = 'mejora';
    protected string $listRoute    = '/admin/mejora';

    protected function getSelectSql(): string
    {
        return "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones FROM {$this->table} ORDER BY id";
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

            $sql = "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones FROM {$this->table} WHERE id = ?";
            $db->consultaPreparada($sql, [$id]);
            $row = $db->coger_Fila();
            if ($row) {
                $item = [
                    'id'                  => $row[0],
                    'tipo'                => $row[1] ?? null,
                    'cliente'             => $row[2] ?? null,
                    'fecha'               => $row[3],
                    'descripcion'         => $row[4],
                    'analisis'            => $row[5] ?? null,
                    'requiere_tratamiento'=> $row[6],
                    'tratamiento'         => $row[7] ?? null,
                    'accion_preventiva'   => $row[8] ?? null,
                    'fecha_implantacion'  => $row[9] ?? null,
                    'plazo'               => $row[10] ?? null,
                    'coste'               => $row[11] ?? null,
                    'cerrada'             => $row[12],
                    'area'                => $row[13] ?? null,
                    'observaciones'       => $row[14] ?? null,
                ];
            }
            $db->desconexion();
        }

        $fullName = trim(($_SESSION['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario_apellido'] ?? ''));

        $variables = [
            'sidebarMenu' => $sidebarMenu,
            // singular key for the form template (e.g. 'mejora')
            strtolower($this->flashPrefix) => $item,
            'isEdit'      => (bool)$item,
            'pageTitle'   => $item ? "Editar {$this->title}" : "Nueva Acción de Mejora",
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

        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $tipo = isset($_POST['tipo']) && $_POST['tipo'] !== '' ? (int)$_POST['tipo'] : null;
        $cliente = isset($_POST['cliente']) && $_POST['cliente'] !== '' ? (int)$_POST['cliente'] : null;
        $analisis = trim($_POST['analisis'] ?? '');
        $requiere_tratamiento = !empty($_POST['requiere_tratamiento']) ? 1 : 0;
        $tratamiento = trim($_POST['tratamiento'] ?? '');
        $accion_preventiva = trim($_POST['accion_preventiva'] ?? '');
        $fecha_implantacion = trim($_POST['fecha_implantacion'] ?? '');
        $plazo = trim($_POST['plazo'] ?? '');
        $coste = isset($_POST['coste']) && $_POST['coste'] !== '' ? (float)$_POST['coste'] : null;
        $cerrada = !empty($_POST['cerrada']) ? 1 : 0;
        $area = trim($_POST['area'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        $errors = [];
        if ($descripcion === '') {
            $errors[] = 'La descripción de la acción es obligatoria.';
        }
        if ($fecha === '') {
            $errors[] = 'La fecha es obligatoria.';
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

        // Normalize empty date strings to NULL for the DB
        $fecha_imp = $fecha_implantacion !== '' ? $fecha_implantacion : null;
        $plazo_val = $plazo !== '' ? $plazo : null;

        if ($id > 0) {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET tipo = ?, cliente = ?, fecha = ?, descripcion = ?, analisis = ?, requiere_tratamiento = ?, tratamiento = ?, accion_preventiva = ?, fecha_implantacion = ?, plazo = ?, coste = ?, cerrada = ?, area = ?, observaciones = ? WHERE id = ?",
                [$tipo, $cliente, $fecha, $descripcion, $analisis, $requiere_tratamiento, $tratamiento, $accion_preventiva, $fecha_imp, $plazo_val, $coste, $cerrada, $area, $observaciones, $id]
            );
            $msg = $this->getSuccessMessage(true);
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [$tipo, $cliente, $fecha, $descripcion, $analisis, $requiere_tratamiento, $tratamiento, $accion_preventiva, $fecha_imp, $plazo_val, $coste, $cerrada, $area, $observaciones]
            );
            $msg = $this->getSuccessMessage(false);
        }

        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header("Location: {$this->listRoute}");
        exit;
    }
}
