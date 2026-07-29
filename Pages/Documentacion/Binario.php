<?php
namespace Tuqan\Pages\Documentacion;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogFormulario;

/**
 * Download / remove document binary attachment (Stage 9.39).
 */
class Binario extends CatalogFormulario
{
    protected string $table        = 'contenido_binario';
    protected string $title        = 'Adjunto binario';
    protected string $templateDir  = 'documentacion';
    protected string $flashPrefix  = 'documento';
    protected string $listRoute    = '/admin/documentacion';

    public const MAX_BYTES = 5242880; // 5 MiB

    /**
     * Stream binary attachment for a document id.
     */
    public function Descargar($id = null)
    {
        Config::initialize();
        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;
        if ($id <= 0) {
            http_response_code(404);
            echo 'Adjunto no encontrado.';
            return '';
        }

        $db = $this->getDb();
        $db->consultaPreparada(
            "SELECT cb.contenido, cb.size, cb.nombre_archivo, tf.mime, tf.extension
             FROM contenido_binario cb
             LEFT JOIN tipos_fichero tf ON tf.id = cb.tipo_fichero
             WHERE cb.id = ?",
            [$id]
        );
        // Avoid stripslashes on binary payload
        $row = $db->coger_Fila(false);
        $db->desconexion();

        if (!$row || $row[0] === null || $row[0] === '') {
            http_response_code(404);
            echo 'Adjunto no encontrado.';
            return '';
        }

        $contenido = $row[0];
        if (is_resource($contenido)) {
            $contenido = stream_get_contents($contenido);
        }
        $size = $row[1] !== null ? (int)$row[1] : strlen($contenido);
        $nombre = $row[2] ?: ('documento-' . $id . ($row[4] ? ('.' . trim($row[4])) : ''));
        $mime = $row[3] ?: 'application/octet-stream';

        // Clear any accidental output buffers before binary headers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header('Content-Disposition: attachment; filename="' . str_replace(['"', "\r", "\n"], '', $nombre) . '"');
        header('X-Content-Type-Options: nosniff');
        echo $contenido;
        exit;
    }

    /**
     * POST: remove binary attachment for document id.
     */
    public function Eliminar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . $this->listRoute);
            exit;
        }
        Config::initialize();
        if ($id === null) {
            $id = (int)($_POST['id'] ?? 0);
        }
        $id = (int)$id;
        if ($id <= 0) {
            header('Location: ' . $this->listRoute);
            exit;
        }

        try {
            $db = $this->getDb();
            $db->consultaPreparada('DELETE FROM contenido_binario WHERE id = ?', [$id]);
            $db->desconexion();
            $_SESSION[$this->flashPrefix . '_flash_success'] = 'Adjunto eliminado.';
        } catch (\Throwable $e) {
            $_SESSION[$this->flashPrefix . '_form_error'] = 'No se pudo eliminar el adjunto.';
        }
        header('Location: /admin/documentacion/editar/' . $id);
        exit;
    }

    /**
     * Resolve tipos_fichero id from uploaded filename extension.
     */
    public static function resolveTipoFicheroId($db, string $filename): ?int
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext === '') {
            return null;
        }
        // jpg/jpeg alias
        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }
        $db->consultaPreparada(
            "SELECT id FROM tipos_fichero WHERE lower(trim(extension)) = ? LIMIT 1",
            [$ext]
        );
        $row = $db->coger_Fila();
        if ($row) {
            return (int)$row[0];
        }
        // Insert unknown extension as generic octet-stream type
        $db->consultaPreparada(
            "INSERT INTO tipos_fichero (nombre, extension, mime) VALUES (?, ?, 'application/octet-stream')",
            [strtoupper($ext), $ext]
        );
        $db->consultaPreparada(
            "SELECT id FROM tipos_fichero WHERE lower(trim(extension)) = ? ORDER BY id DESC LIMIT 1",
            [$ext]
        );
        $row = $db->coger_Fila();
        return $row ? (int)$row[0] : null;
    }

    /**
     * Persist uploaded file into contenido_binario for document $docId.
     * @return string|null error message or null on success / no file
     */
    public static function persistUpload($db, int $docId, array $file): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return 'Error al subir el archivo (código ' . (int)$file['error'] . ').';
        }
        $size = (int)($file['size'] ?? 0);
        if ($size <= 0) {
            return 'El archivo está vacío.';
        }
        if ($size > self::MAX_BYTES) {
            return 'El archivo supera el máximo de 5 MB.';
        }
        $tmp = $file['tmp_name'] ?? '';
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return 'Archivo temporal no válido.';
        }
        $bytes = file_get_contents($tmp);
        if ($bytes === false) {
            return 'No se pudo leer el archivo subido.';
        }
        $nombre = basename((string)($file['name'] ?? 'adjunto'));
        $nombre = preg_replace('/[^\w.\- ()áéíóúÁÉÍÓÚñÑ]+/u', '_', $nombre) ?: 'adjunto';
        $tipoId = self::resolveTipoFicheroId($db, $nombre);

        $db->consultaPreparada(
            "INSERT INTO contenido_binario (id, tipo_fichero, size, contenido, nombre_archivo)
             VALUES (?, ?, ?, ?, ?)
             ON CONFLICT (id) DO UPDATE SET
               tipo_fichero = EXCLUDED.tipo_fichero,
               size = EXCLUDED.size,
               contenido = EXCLUDED.contenido,
               nombre_archivo = EXCLUDED.nombre_archivo",
            [$docId, $tipoId, strlen($bytes), $bytes, $nombre]
        );
        return null;
    }
}
