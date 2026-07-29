<?php
namespace Tuqan\Pages\Equipos;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogListado;
use Tuqan\Pages\Equipos\Revisiones\Listado as RevisionesListado;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Plan de mantenimiento per equipo (Stage 9.38).
 * Shows interval, next preventivo due, history; can program next preventivo.
 */
class Plan extends CatalogListado
{
    protected string $table       = 'mantenimientos';
    protected string $title       = 'Plan de Mantenimiento';
    protected string $templateDir = 'equipos';
    protected string $flashPrefix = 'plan';

    protected function getSelectSql(): string
    {
        return "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'             => $row[0],
            'equipo'         => $row[1] ?? null,
            'tipo'           => $row[2] ?? null,
            'fecha_prevista' => $row[3] ?? null,
            'fecha_realiza'  => $row[4] ?? null,
            'comentarios'    => $row[5] ?? '',
            'motivos'        => $row[6] ?? '',
        ];
    }

    /**
     * Load equipo core + maintenance interval.
     */
    protected function loadEquipo(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada(
            'SELECT id, numero, descripcion, modelo, ubicacion, activo, mantenimiento_cada, dias, ver_interna
             FROM equipos WHERE id = ?',
            [$id]
        );
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        $dias = $row[7];
        $isDays = ($dias === true || $dias === 't' || $dias === '1' || $dias === 1);
        return [
            'id'                 => $row[0],
            'numero'             => $row[1],
            'descripcion'        => $row[2],
            'modelo'             => $row[3] ?? null,
            'ubicacion'          => $row[4] ?? null,
            'activo'             => $row[5],
            'mantenimiento_cada' => (int)$row[6],
            'dias'               => $isDays,
            'dias_label'         => $isDays ? 'días' : 'meses',
            'ver_interna'        => $row[8],
            'label'              => $row[1] . ' — ' . $row[2],
        ];
    }

    protected function fetchHistorial(int $equipoId): array
    {
        $db = $this->getDb();
        $db->consultaPreparada(
            "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos
             FROM mantenimientos WHERE equipo = ? ORDER BY COALESCE(fecha_realiza, fecha_prevista) DESC NULLS LAST, id DESC",
            [$equipoId]
        );
        $items = [];
        while ($row = $db->coger_Fila()) {
            $item = $this->mapRow($row);
            $item['tipo_label'] = RevisionesListado::tipoLabel($item['tipo'] ?? null);
            $items[] = $item;
        }
        $db->desconexion();
        return $items;
    }

    /**
     * Last completed/realized preventivo date (fecha_realiza).
     */
    protected function lastPreventivoRealiza(int $equipoId): ?string
    {
        $db = $this->getDb();
        $db->consultaPreparada(
            "SELECT max(fecha_realiza) FROM mantenimientos WHERE equipo = ? AND tipo = 'preventivo' AND fecha_realiza IS NOT NULL",
            [$equipoId]
        );
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row || empty($row[0])) {
            return null;
        }
        return substr((string)$row[0], 0, 10);
    }

    /**
     * Next due date using legacy rules: base = last preventivo realiza (or today),
     * add mantenimiento_cada days if dias=true else months.
     */
    public static function computeNextDue(string $baseYmd, int $cada, bool $isDays): string
    {
        $cada = max(1, $cada);
        try {
            $dt = new \DateTimeImmutable($baseYmd);
        } catch (\Exception $e) {
            $dt = new \DateTimeImmutable('today');
        }
        if ($isDays) {
            $next = $dt->modify('+' . $cada . ' days');
        } else {
            $next = $dt->modify('+' . $cada . ' months');
        }
        return $next->format('Y-m-d');
    }

    protected function resolveNextDue(array $equipo): array
    {
        $last = $this->lastPreventivoRealiza((int)$equipo['id']);
        $base = $last ?: date('Y-m-d');
        $from = $last ? 'último preventivo realizado' : 'hoy (sin preventivo previo)';
        $next = self::computeNextDue(
            $base,
            (int)$equipo['mantenimiento_cada'],
            (bool)$equipo['dias']
        );
        return [
            'last_preventivo' => $last,
            'base_date'       => $base,
            'base_label'      => $from,
            'next_due'        => $next,
        ];
    }

    public function ShowPage($id = null)
    {
        Config::initialize();

        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        // Also accept ?equipo= for consistency with other modules
        if (($id === null || (int)$id <= 0) && isset($_GET['equipo'])) {
            $id = (int)$_GET['equipo'];
        }
        $id = (int)$id;
        if ($id <= 0) {
            header('Location: /admin/equipos');
            exit;
        }

        $equipo = $this->loadEquipo($id);
        if (!$equipo) {
            $_SESSION[$this->flashPrefix . '_flash_error'] = 'Equipo no encontrado.';
            header('Location: /admin/equipos');
            exit;
        }

        $due = $this->resolveNextDue($equipo);
        $historial = $this->fetchHistorial($id);

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, ['cache' => Config::$cache_path]);

        $vars = array_merge($this->buildCommonVariables(), [
            'pageTitle'      => 'Plan: ' . $equipo['label'],
            'equipo'         => $equipo,
            'due'            => $due,
            'historial'      => $historial,
            'flash_success'  => $_SESSION[$this->flashPrefix . '_flash_success'] ?? null,
            'flash_error'    => $_SESSION[$this->flashPrefix . '_flash_error'] ?? null,
        ]);
        unset($_SESSION[$this->flashPrefix . '_flash_success'], $_SESSION[$this->flashPrefix . '_flash_error']);

        try {
            return $twig->load($this->templateDir . '/plan.twig')->render($vars);
        } catch (\Exception $e) {
            return 'Error al cargar el plan de mantenimiento: ' . $e->getMessage();
        }
    }

    /**
     * Create next preventivo mantenimiento row for the equipo.
     */
    public function ProgramarPreventivo($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: /admin/equipos');
            exit;
        }
        Config::initialize();

        if ($id === null) {
            $id = (int)($_POST['id'] ?? $_POST['equipo'] ?? 0);
        }
        $id = (int)$id;
        $equipo = $this->loadEquipo($id);
        if (!$equipo) {
            $_SESSION[$this->flashPrefix . '_flash_error'] = 'Equipo no encontrado.';
            header('Location: /admin/equipos');
            exit;
        }

        $due = $this->resolveNextDue($equipo);
        $fecha = $due['next_due'];
        $comentarios = 'Preventivo programado automáticamente (cada '
            . $equipo['mantenimiento_cada'] . ' ' . $equipo['dias_label'] . ')';
        $motivos = 'Plan de mantenimiento (Stage 9.38)';

        $db = $this->getDb();
        // fecha_realiza NOT NULL in legacy schema — set equal to prevista for scheduled rows
        $db->consultaPreparada(
            "INSERT INTO mantenimientos (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos)
             VALUES (?, 'preventivo', ?, ?, ?, ?)",
            [$id, $fecha, $fecha, $comentarios, $motivos]
        );
        $db->desconexion();

        $_SESSION[$this->flashPrefix . '_flash_success'] =
            'Preventivo programado para el ' . $fecha . '.';
        header('Location: /admin/equipos/plan/' . $id);
        exit;
    }
}
