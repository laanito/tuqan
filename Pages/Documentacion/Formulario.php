<?php
namespace Tuqan\Pages\Documentacion;
use Tuqan\Classes\Config;
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
        return "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas, revisado_por, aprobado_por, fecha_revision, fecha_aprobacion FROM {$this->table} ORDER BY id";
    }

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas, revisado_por, aprobado_por, fecha_revision, fecha_aprobacion FROM {$this->table} WHERE id = ?";
    }

    protected function loadItem($id): ?array
    {
        if ($id <= 0) return null;
        $db = $this->getDb();
        $db->consultaPreparada($this->getSelectForForm(), [$id]);
        $row = $db->coger_Fila();
        $contenido = '';
        $binario = null;
        if ($row) {
            $db->consultaPreparada("SELECT contenido FROM contenido_texto WHERE id = ?", [$id]);
            $crow = $db->coger_Fila();
            if ($crow) $contenido = $crow[0] ?? '';
            // 9.39 binary attachment metadata (no payload in form)
            try {
                $db->consultaPreparada(
                    "SELECT cb.size, cb.nombre_archivo, tf.nombre, tf.extension, tf.mime
                     FROM contenido_binario cb
                     LEFT JOIN tipos_fichero tf ON tf.id = cb.tipo_fichero
                     WHERE cb.id = ?",
                    [$id]
                );
                $brow = $db->coger_Fila();
                if ($brow) {
                    $binario = [
                        'size'           => $brow[0] !== null ? (int)$brow[0] : null,
                        'nombre_archivo' => $brow[1] ?? null,
                        'tipo_nombre'    => $brow[2] ?? null,
                        'extension'      => $brow[3] ? trim($brow[3]) : null,
                        'mime'           => $brow[4] ?? null,
                        'size_label'     => self::formatBytes($brow[0] !== null ? (int)$brow[0] : 0),
                    ];
                }
            } catch (\Throwable $e) {
                $binario = null;
            }
        }
        $db->desconexion();
        if (!$row) return null;
        return [
            'id'                => $row[0],
            'nombre'            => $row[1],
            'codigo'            => $row[2] ?? null,
            'estado'            => $row[3] ?? null,
            'revision'          => $row[4] ?? null,
            'activo'            => $row[5],
            'calidad'           => $row[6],
            'medioambiente'     => $row[7],
            'tipo_documento'    => $row[8] ?? null,
            'area'              => $row[9] ?? null,
            'perfil_ver'        => $row[10],
            'perfil_nueva'      => $row[11],
            'perfil_modificar'  => $row[12],
            'perfil_revisar'    => $row[13],
            'perfil_aprobar'    => $row[14],
            'perfil_historico'  => $row[15],
            'perfil_tareas'     => $row[16],
            'revisado_por'      => $row[17] ?? null,
            'aprobado_por'      => $row[18] ?? null,
            'fecha_revision'    => $row[19] ?? null,
            'fecha_aprobacion'  => $row[20] ?? null,
            'contenido'         => $contenido,
            'binario'           => $binario,
            'has_binario'       => $binario !== null,
        ];
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return round($bytes / 1048576, 2) . ' MB';
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        // 9.23 + 9.24: perfiles + workflows; 9.31 estado options/labels
        $vars['tipo_documento_options'] = $this->getRelatedOptions('tipodocumento', 'nombre');
        $vars['area_options'] = $this->getRelatedOptions('areas', 'nombre');
        $vars['usuario_options'] = $this->getRelatedOptions('usuarios', 'nombre');
        $vars['estado_options'] = EstadoHelper::options();
        $vars['max_upload_mb'] = (int)(Binario::MAX_BYTES / 1048576);

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $d = $vars[$key];
            $d['tipo_documento_label'] = $this->getRelatedLabel('tipodocumento', $d['tipo_documento'] ?? null);
            $d['area_label'] = $this->getRelatedLabel('areas', $d['area'] ?? null);
            $d['revisado_por_label'] = $this->getRelatedLabel('usuarios', $d['revisado_por'] ?? null);
            $d['aprobado_por_label'] = $this->getRelatedLabel('usuarios', $d['aprobado_por'] ?? null);
            $d['estado_label'] = EstadoHelper::label($d['estado'] ?? null);
            $d['estado_badge'] = EstadoHelper::badgeClass($d['estado'] ?? null);
            $d['contenido_len'] = strlen((string)($d['contenido'] ?? ''));
            $vars[$key] = $d;
        }
        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'nombre'           => trim($_POST['nombre'] ?? ''),
            'codigo'           => trim($_POST['codigo'] ?? ''),
            'estado'           => isset($_POST['estado']) && $_POST['estado'] !== '' ? (int)$_POST['estado'] : null,
            'revision'         => trim($_POST['revision'] ?? ''),
            'activo'           => !empty($_POST['activo']) ? 1 : 0,
            'calidad'          => !empty($_POST['calidad']) ? 1 : 0,
            'medioambiente'    => !empty($_POST['medioambiente']) ? 1 : 0,
            'tipo_documento'   => isset($_POST['tipo_documento']) && $_POST['tipo_documento'] !== '' ? (int)$_POST['tipo_documento'] : null,
            'area'             => isset($_POST['area']) && $_POST['area'] !== '' ? (int)$_POST['area'] : null,
            'perfil_ver'       => !empty($_POST['perfil_ver']) ? 1 : 0,
            'perfil_nueva'     => !empty($_POST['perfil_nueva']) ? 1 : 0,
            'perfil_modificar' => !empty($_POST['perfil_modificar']) ? 1 : 0,
            'perfil_revisar'   => !empty($_POST['perfil_revisar']) ? 1 : 0,
            'perfil_aprobar'   => !empty($_POST['perfil_aprobar']) ? 1 : 0,
            'perfil_historico' => !empty($_POST['perfil_historico']) ? 1 : 0,
            'perfil_tareas'    => !empty($_POST['perfil_tareas']) ? 1 : 0,
            'revisado_por'     => isset($_POST['revisado_por']) && $_POST['revisado_por'] !== '' ? (int)$_POST['revisado_por'] : null,
            'aprobado_por'     => isset($_POST['aprobado_por']) && $_POST['aprobado_por'] !== '' ? (int)$_POST['aprobado_por'] : null,
            'fecha_revision'   => trim($_POST['fecha_revision'] ?? ''),
            'fecha_aprobacion' => trim($_POST['fecha_aprobacion'] ?? ''),
            'contenido'        => trim($_POST['contenido'] ?? ''),
            // 9.34 workflow action checkboxes
            'accion_enviar_revision' => !empty($_POST['accion_enviar_revision']) ? 1 : 0,
            'accion_revisar'         => !empty($_POST['accion_revisar']) ? 1 : 0,
            'accion_aprobar'         => !empty($_POST['accion_aprobar']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del documento es obligatorio.';
        }
        // Cannot approve without prior review (form path)
        if (!empty($data['accion_aprobar']) && empty($data['revisado_por']) && empty($data['accion_revisar'])) {
            $errors[] = 'No se puede aprobar sin revisar primero.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $today = date('Y-m-d');
        $currentUser = $this->getCurrentUserId();

        // 9.34 form workflow auto-apply
        if (!empty($data['accion_enviar_revision'])) {
            $data['estado'] = 3; // Pend. revisión
        }
        if (!empty($data['accion_revisar'])) {
            if (empty($data['revisado_por'])) {
                $data['revisado_por'] = $currentUser;
            }
            if (($data['fecha_revision'] ?? '') === '') {
                $data['fecha_revision'] = $today;
            }
            $data['estado'] = 4; // Revisado
        }
        if (!empty($data['accion_aprobar'])) {
            if (empty($data['aprobado_por'])) {
                $data['aprobado_por'] = $currentUser;
            }
            if (($data['fecha_aprobacion'] ?? '') === '') {
                $data['fecha_aprobacion'] = $today;
            }
            if (empty($data['revisado_por'])) {
                $data['revisado_por'] = $currentUser;
                $data['fecha_revision'] = $data['fecha_revision'] ?: $today;
            }
            $data['estado'] = 1; // En vigor
        }

        $params = [
            $data['nombre'],
            $data['codigo'],
            $data['estado'],
            $data['revision'],
            $data['activo'],
            $data['calidad'],
            $data['medioambiente'],
            $data['tipo_documento'],
            $data['area'],
            $data['perfil_ver'],
            $data['perfil_nueva'],
            $data['perfil_modificar'],
            $data['perfil_revisar'],
            $data['perfil_aprobar'],
            $data['perfil_historico'],
            $data['perfil_tareas'],
            $data['revisado_por'],
            $data['aprobado_por'],
            $data['fecha_revision'] ?: null,
            $data['fecha_aprobacion'] ?: null,
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, codigo = ?, estado = ?, revision = ?, activo = ?, calidad = ?, medioambiente = ?, tipo_documento = ?, area = ?, perfil_ver = ?, perfil_nueva = ?, perfil_modificar = ?, perfil_revisar = ?, perfil_aprobar = ?, perfil_historico = ?, perfil_tareas = ?, revisado_por = ?, aprobado_por = ?, fecha_revision = ?, fecha_aprobacion = ? WHERE id = ?",
                $params
            );
            $doc_id = $id;
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas, revisado_por, aprobado_por, fecha_revision, fecha_aprobacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
            $db->consulta("SELECT currval('documentos_id_seq')");
            $row = $db->coger_Fila();
            $doc_id = $row[0];
        }
        // Handle linked content (content editor support)
        $db->consultaPreparada(
            "INSERT INTO contenido_texto (id, contenido) VALUES (?, ?) ON CONFLICT (id) DO UPDATE SET contenido = ?",
            [$doc_id, $data['contenido'], $data['contenido']]
        );

        // 9.39 binary attachment upload (optional)
        if (!empty($_FILES['archivo']) && is_array($_FILES['archivo'])) {
            $uploadErr = Binario::persistUpload($db, (int)$doc_id, $_FILES['archivo']);
            if ($uploadErr !== null) {
                $db->desconexion();
                // Surface via session; document meta already saved
                $_SESSION[$this->flashPrefix . '_form_error'] = $uploadErr;
                return;
            }
        }
        $db->desconexion();
    }

    /**
     * After save, stay on edit form when upload error; otherwise list.
     */
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

        // If persist flagged upload error, return to edit (need doc id)
        if (!empty($_SESSION[$this->flashPrefix . '_form_error'])) {
            // resolve id after insert
            if ($id <= 0) {
                // best effort: list
                header("Location: {$this->listRoute}");
                exit;
            }
            header("Location: {$this->listRoute}/editar/$id");
            exit;
        }

        $msg = $this->getSuccessMessage($id > 0);
        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        // After create with file, go to list; edits go to list too (consistent)
        header("Location: {$this->listRoute}");
        exit;
    }

    // --- Quick workflow actions (Stage 9.34) — one-click from list ---

    public function EnviarRevision($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }
        Config::initialize();
        $id = $id !== null ? (int)$id : (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: {$this->listRoute}");
            exit;
        }
        $db = $this->getDb();
        $db->consultaPreparada("SELECT id FROM {$this->table} WHERE id = ?", [$id]);
        $row = $db->coger_Fila();
        if (!$row) {
            $db->desconexion();
            $_SESSION[$this->flashPrefix . '_form_error'] = 'Documento no encontrado.';
            header("Location: {$this->listRoute}");
            exit;
        }
        $db->consultaPreparada("UPDATE {$this->table} SET estado = 3 WHERE id = ?", [$id]);
        $db->desconexion();
        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Documento enviado a revisión.';
        header("Location: {$this->listRoute}");
        exit;
    }

    public function Revisar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }
        Config::initialize();
        $id = $id !== null ? (int)$id : (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: {$this->listRoute}");
            exit;
        }
        $db = $this->getDb();
        $db->consultaPreparada(
            "SELECT revisado_por, fecha_revision FROM {$this->table} WHERE id = ?",
            [$id]
        );
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            $_SESSION[$this->flashPrefix . '_form_error'] = 'Documento no encontrado.';
            header("Location: {$this->listRoute}");
            exit;
        }
        $today = date('Y-m-d');
        $user = $this->getCurrentUserId();
        $revUser = $row[0] ?: $user;
        $revFecha = $row[1] ?: $today;
        $db = $this->getDb();
        $db->consultaPreparada(
            "UPDATE {$this->table} SET revisado_por = ?, fecha_revision = ?, estado = 4 WHERE id = ?",
            [$revUser, $revFecha, $id]
        );
        $db->desconexion();
        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Documento revisado.';
        header("Location: {$this->listRoute}");
        exit;
    }

    public function Aprobar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header("Location: {$this->listRoute}");
            exit;
        }
        Config::initialize();
        $id = $id !== null ? (int)$id : (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            header("Location: {$this->listRoute}");
            exit;
        }
        $db = $this->getDb();
        $db->consultaPreparada(
            "SELECT revisado_por, aprobado_por, fecha_aprobacion FROM {$this->table} WHERE id = ?",
            [$id]
        );
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            $_SESSION[$this->flashPrefix . '_form_error'] = 'Documento no encontrado.';
            header("Location: {$this->listRoute}");
            exit;
        }
        if (empty($row[0])) {
            $_SESSION[$this->flashPrefix . '_form_error'] = 'No se puede aprobar sin revisar primero. Use Revisar antes de Aprobar.';
            header("Location: {$this->listRoute}");
            exit;
        }
        $today = date('Y-m-d');
        $user = $this->getCurrentUserId();
        $aprUser = $row[1] ?: $user;
        $aprFecha = $row[2] ?: $today;
        $db = $this->getDb();
        $db->consultaPreparada(
            "UPDATE {$this->table} SET aprobado_por = ?, fecha_aprobacion = ?, estado = 1 WHERE id = ?",
            [$aprUser, $aprFecha, $id]
        );
        $db->desconexion();
        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Documento aprobado y puesto en vigor.';
        header("Location: {$this->listRoute}");
        exit;
    }
}
