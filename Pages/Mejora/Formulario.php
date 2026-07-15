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
        return "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones, usuario_detectado, usuario_cerrado, auditoria, usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre FROM {$this->table} ORDER BY id";
    }

    protected function getSelectForForm(): string
    {
        return "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones, usuario_detectado, usuario_cerrado, auditoria, usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre FROM {$this->table} WHERE id = ?";
    }

    protected function loadItem($id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada($this->getSelectForForm(), [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        return [
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
            'usuario_detectado'   => $row[15] ?? null,
            'usuario_cerrado'     => $row[16] ?? null,
            'auditoria'           => $row[17] ?? null,
            'usuario_verifica'    => $row[18] ?? null,
            'fecha_verifica'      => $row[19] ?? null,
            'usuario_implantacion'=> $row[20] ?? null,
            'fecha_cierre'        => $row[21] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        // 9.17 + 9.21 + 9.25 relations polish: options + labels for tipo/cliente + full workflow users/auditoria
        $vars['tipo_options'] = $this->getRelatedOptions('tipoaccionesmejora', 'nombre');
        $vars['cliente_options'] = $this->getRelatedOptions('clientes', 'nombre');
        $vars['usuario_options'] = $this->getRelatedOptions('usuarios', 'nombre');
        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $m = $vars[$key];
            $m['tipo_label'] = $this->getRelatedLabel('tipoaccionesmejora', $m['tipo'] ?? null);
            $m['cliente_label'] = $this->getRelatedLabel('clientes', $m['cliente'] ?? null);
            $m['usuario_detectado_label'] = $this->getRelatedLabel('usuarios', $m['usuario_detectado'] ?? null);
            $m['usuario_cerrado_label'] = $this->getRelatedLabel('usuarios', $m['usuario_cerrado'] ?? null);
            $m['auditoria_label'] = $this->getRelatedLabel('auditorias', $m['auditoria'] ?? null);
            $m['usuario_verifica_label'] = $this->getRelatedLabel('usuarios', $m['usuario_verifica'] ?? null);
            $m['usuario_implantacion_label'] = $this->getRelatedLabel('usuarios', $m['usuario_implantacion'] ?? null);
            $vars[$key] = $m;
        }

        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'tipo'                => isset($_POST['tipo']) && $_POST['tipo'] !== '' ? (int)$_POST['tipo'] : null,
            'cliente'             => isset($_POST['cliente']) && $_POST['cliente'] !== '' ? (int)$_POST['cliente'] : null,
            'fecha'               => trim($_POST['fecha'] ?? ''),
            'descripcion'         => trim($_POST['descripcion'] ?? ''),
            'analisis'            => trim($_POST['analisis'] ?? ''),
            'requiere_tratamiento'=> !empty($_POST['requiere_tratamiento']) ? 1 : 0,
            'tratamiento'         => trim($_POST['tratamiento'] ?? ''),
            'accion_preventiva'   => trim($_POST['accion_preventiva'] ?? ''),
            'fecha_implantacion'  => trim($_POST['fecha_implantacion'] ?? ''),
            'plazo'               => trim($_POST['plazo'] ?? ''),
            'coste'               => isset($_POST['coste']) && $_POST['coste'] !== '' ? (float)$_POST['coste'] : null,
            'cerrada'             => !empty($_POST['cerrada']) ? 1 : 0,
            'area'                => trim($_POST['area'] ?? ''),
            'observaciones'       => trim($_POST['observaciones'] ?? ''),
            'usuario_detectado'   => isset($_POST['usuario_detectado']) && $_POST['usuario_detectado'] !== '' ? (int)$_POST['usuario_detectado'] : null,
            'usuario_cerrado'     => isset($_POST['usuario_cerrado']) && $_POST['usuario_cerrado'] !== '' ? (int)$_POST['usuario_cerrado'] : null,
            'auditoria'           => isset($_POST['auditoria']) && $_POST['auditoria'] !== '' ? (int)$_POST['auditoria'] : null,
            'usuario_verifica'    => isset($_POST['usuario_verifica']) && $_POST['usuario_verifica'] !== '' ? (int)$_POST['usuario_verifica'] : null,
            'usuario_implantacion'=> isset($_POST['usuario_implantacion']) && $_POST['usuario_implantacion'] !== '' ? (int)$_POST['usuario_implantacion'] : null,
            'fecha_verifica'      => trim($_POST['fecha_verifica'] ?? ''),
            'fecha_cierre'        => trim($_POST['fecha_cierre'] ?? ''),
            'accion_verificar'    => !empty($_POST['accion_verificar']) ? 1 : 0,
            'accion_cerrar'       => !empty($_POST['accion_cerrar']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['descripcion'] ?? '') === '') {
            $errors[] = 'La descripción de la acción es obligatoria.';
        }
        if (($data['fecha'] ?? '') === '') {
            $errors[] = 'La fecha es obligatoria.';
        }
        // Basic state machine validation (checkbox path)
        if ($data['accion_cerrar'] && !$data['usuario_verifica']) {
            $errors[] = 'No se puede cerrar sin verificar primero.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();

        // Normalize empty date strings to NULL for the DB
        $fecha_imp = ($data['fecha_implantacion'] ?? '') !== '' ? $data['fecha_implantacion'] : null;
        $plazo_val = ($data['plazo'] ?? '') !== '' ? $data['plazo'] : null;

        $today = date('Y-m-d');
        $currentUser = $this->getCurrentUserId();

        // Full state machine auto-apply on action checkboxes (form path)
        if ($data['accion_verificar']) {
            if (empty($data['usuario_verifica'])) {
                $data['usuario_verifica'] = $currentUser;
            }
            if (empty($data['fecha_verifica'])) {
                $data['fecha_verifica'] = $today;
            }
        }
        if ($data['accion_cerrar']) {
            $data['cerrada'] = 1;
            if (empty($data['usuario_cerrado'])) {
                $data['usuario_cerrado'] = $currentUser;
            }
            if (empty($data['fecha_cierre'])) {
                $data['fecha_cierre'] = $today;
            }
            // If closing and no verifica yet, auto-verify as well (generous but safe)
            if (empty($data['usuario_verifica'])) {
                $data['usuario_verifica'] = $currentUser;
                $data['fecha_verifica'] = $today;
            }
        }

        $params = [
            $data['tipo'],
            $data['cliente'],
            $data['fecha'],
            $data['descripcion'],
            $data['analisis'],
            $data['requiere_tratamiento'],
            $data['tratamiento'],
            $data['accion_preventiva'],
            $fecha_imp,
            $plazo_val,
            $data['coste'],
            $data['cerrada'],
            $data['area'],
            $data['observaciones'],
            $data['usuario_detectado'],
            $data['usuario_cerrado'],
            $data['auditoria'],
            $data['usuario_verifica'],
            $data['fecha_verifica'] ?: null,
            $data['usuario_implantacion'],
            $data['fecha_cierre'] ?: null,
        ];

        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET tipo = ?, cliente = ?, fecha = ?, descripcion = ?, analisis = ?, requiere_tratamiento = ?, tratamiento = ?, accion_preventiva = ?, fecha_implantacion = ?, plazo = ?, coste = ?, cerrada = ?, area = ?, observaciones = ?, usuario_detectado = ?, usuario_cerrado = ?, auditoria = ?, usuario_verifica = ?, fecha_verifica = ?, usuario_implantacion = ?, fecha_cierre = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones, usuario_detectado, usuario_cerrado, auditoria, usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }

    // --- Quick state machine actions (fuller transitions, 9.28) ---
    // Called directly via dedicated POST routes. Auto-assigns current user + today.
    // These bypass the full form and provide one-click verify/close from list.

    public function Verificar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }
        Config::initialize();

        $id = $id !== null ? (int)$id : (isset($_POST['id']) ? (int)$_POST['id'] : (int)($_GET['id'] ?? 0));
        if ($id <= 0) {
            header("Location: {$this->listRoute}");
            exit;
        }

        $db = $this->getDb();
        // Load minimal current state
        $db->consultaPreparada("SELECT usuario_verifica, fecha_verifica, cerrada FROM {$this->table} WHERE id = ?", [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();

        if (!$row) {
            $_SESSION[$this->flashPrefix . '_flash_success'] = 'Acción no encontrada.';
            header("Location: {$this->listRoute}");
            exit;
        }

        $today = date('Y-m-d');
        $currentUser = $this->getCurrentUserId();

        $usuarioVer = $row[0] ?: $currentUser;
        $fechaVer   = $row[1] ?: $today;

        $db = $this->getDb();
        $db->consultaPreparada(
            "UPDATE {$this->table} SET usuario_verifica = ?, fecha_verifica = ? WHERE id = ?",
            [$usuarioVer, $fechaVer, $id]
        );
        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Acción de mejora verificada.';
        header("Location: {$this->listRoute}");
        exit;
    }

    public function Cerrar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }
        Config::initialize();

        $id = $id !== null ? (int)$id : (isset($_POST['id']) ? (int)$_POST['id'] : (int)($_GET['id'] ?? 0));
        if ($id <= 0) {
            header("Location: {$this->listRoute}");
            exit;
        }

        $db = $this->getDb();
        $db->consultaPreparada("SELECT usuario_verifica, fecha_verifica, cerrada, usuario_cerrado, fecha_cierre FROM {$this->table} WHERE id = ?", [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();

        if (!$row) {
            $_SESSION[$this->flashPrefix . '_flash_success'] = 'Acción no encontrada.';
            header("Location: {$this->listRoute}");
            exit;
        }

        $alreadyVerified = !empty($row[0]);
        if (!$alreadyVerified) {
            // Enforce: cannot close without prior verification (match form validate)
            $_SESSION[$this->flashPrefix . '_form_error'] = 'No se puede cerrar sin verificar primero. Use Verificar antes de Cerrar.';
            header("Location: {$this->listRoute}");
            exit;
        }

        $today = date('Y-m-d');
        $currentUser = $this->getCurrentUserId();

        $usuarioCie = $row[3] ?: $currentUser;
        $fechaCie   = $row[4] ?: $today;

        $db = $this->getDb();
        $db->consultaPreparada(
            "UPDATE {$this->table} SET cerrada = true, usuario_cerrado = ?, fecha_cierre = ? WHERE id = ?",
            [$usuarioCie, $fechaCie, $id]
        );
        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Acción de mejora cerrada.';
        header("Location: {$this->listRoute}");
        exit;
    }
}
