<?php
namespace Tuqan\Pages\Equipos;

use Tuqan\Pages\Catalog\CatalogListado;
use Tuqan\Pages\Equipos\Revisiones\Listado as RevisionesListado;

/**
 * Equipos annual calendar (Stage 9.36 first slice).
 * Marks days with mantenimientos (fecha_prevista / fecha_realiza).
 * Full preventivo auto-schedule and ICS deferred.
 */
class Calendario extends CatalogListado
{
    protected string $table       = 'mantenimientos';
    protected string $title       = 'Calendario de Equipos';
    protected string $templateDir = 'equipos';
    protected string $flashPrefix = 'calendario';

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

    protected function resolveYear(): int
    {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
        if ($year < 2000 || $year > 2100) {
            $year = (int)date('Y');
        }
        return $year;
    }

    /**
     * Events that fall in $year by prevista and/or realiza.
     *
     * @return array{events: array, by_date: array}
     */
    protected function fetchYearEvents(int $year, ?int $equipoId): array
    {
        $start = sprintf('%04d-01-01', $year);
        $end   = sprintf('%04d-12-31', $year);

        $sql = "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos
                FROM {$this->table}
                WHERE (
                    (fecha_prevista IS NOT NULL AND fecha_prevista >= ? AND fecha_prevista <= ?)
                    OR (fecha_realiza IS NOT NULL AND fecha_realiza >= ? AND fecha_realiza <= ?)
                )";
        $params = [$start, $end, $start, $end];
        if ($equipoId !== null && $equipoId > 0) {
            $sql .= ' AND equipo = ?';
            $params[] = $equipoId;
        }
        $sql .= ' ORDER BY COALESCE(fecha_prevista, fecha_realiza), id';

        $db = $this->getDb();
        $db->consultaPreparada($sql, $params);
        $events = [];
        while ($row = $db->coger_Fila()) {
            $events[] = $this->mapRow($row);
        }
        $db->desconexion();

        foreach ($events as &$ev) {
            $ev['equipo_label'] = $this->getEquipoLabel($ev['equipo'] ?? null);
            $ev['tipo_label']   = RevisionesListado::tipoLabel($ev['tipo'] ?? null);
            $ev['tipo_class']   = $this->tipoCssClass($ev['tipo'] ?? null);
        }
        unset($ev);

        $byDate = [];
        foreach ($events as $ev) {
            foreach (['fecha_prevista', 'fecha_realiza'] as $field) {
                $d = $ev[$field] ?? null;
                if (!$d) {
                    continue;
                }
                // Normalize to Y-m-d (DB may return timestamp-ish strings)
                $ymd = substr((string)$d, 0, 10);
                if (strlen($ymd) < 10 || (int)substr($ymd, 0, 4) !== $year) {
                    continue;
                }
                if (!isset($byDate[$ymd])) {
                    $byDate[$ymd] = [];
                }
                // One marker per mantenimiento per day (even if prevista == realiza)
                $already = false;
                foreach ($byDate[$ymd] as $existing) {
                    if ((int)$existing['id'] === (int)$ev['id']) {
                        $already = true;
                        break;
                    }
                }
                if ($already) {
                    continue;
                }
                $copy = $ev;
                $copy['_field'] = $field;
                $copy['date_kind'] = $field === 'fecha_prevista' ? 'prevista' : 'realizada';
                $byDate[$ymd][] = $copy;
            }
        }

        return ['events' => $events, 'by_date' => $byDate];
    }

    protected function tipoCssClass(?string $tipo): string
    {
        $map = [
            'revision'   => 'cal-tipo-revision',
            'preventivo' => 'cal-tipo-preventivo',
            'correctivo' => 'cal-tipo-correctivo',
        ];
        return $map[$tipo ?? ''] ?? 'cal-tipo-otro';
    }

    protected function getEquipoLabel($id): ?string
    {
        if (!$id) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada('SELECT id, numero, descripcion FROM equipos WHERE id = ?', [(int)$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        return $row[1] . ' — ' . $row[2];
    }

    protected function getEquipoOptions(): array
    {
        $db = $this->getDb();
        $db->consulta('SELECT id, numero, descripcion FROM equipos ORDER BY numero');
        $opts = [];
        while ($row = $db->coger_Fila()) {
            $opts[] = ['id' => $row[0], 'nombre' => $row[1] . ' — ' . $row[2]];
        }
        $db->desconexion();
        return $opts;
    }

    /**
     * Build 12 months of week rows (Mon-first). Each day: day, ymd, is_today, events[].
     */
    protected function buildMonths(int $year, array $byDate): array
    {
        $today = date('Y-m-d');
        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $daysInMonth = (int)date('t', mktime(0, 0, 0, $m, 1, $year));
            // ISO weekday: Mon=1 … Sun=7
            $startDow = (int)date('N', mktime(0, 0, 0, $m, 1, $year));
            $weeks = [];
            $week = array_fill(0, 7, null);
            for ($pad = 1; $pad < $startDow; $pad++) {
                $week[$pad - 1] = null;
            }
            $col = $startDow - 1;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $ymd = sprintf('%04d-%02d-%02d', $year, $m, $d);
                $week[$col] = [
                    'day'      => $d,
                    'ymd'      => $ymd,
                    'is_today' => ($ymd === $today),
                    'events'   => $byDate[$ymd] ?? [],
                    'has'      => !empty($byDate[$ymd]),
                ];
                $col++;
                if ($col === 7) {
                    $weeks[] = $week;
                    $week = array_fill(0, 7, null);
                    $col = 0;
                }
            }
            if ($col > 0) {
                $weeks[] = $week;
            }
            $months[] = [
                'num'   => $m,
                'name'  => $monthNames[$m],
                'weeks' => $weeks,
            ];
        }
        return $months;
    }

    public function ShowPage()
    {
        $twig = $this->initTwig();
        $year = $this->resolveYear();
        $filters = $this->getFilterParams();
        $equipoId = $filters['equipo'] ?? null;

        $fetched = $this->fetchYearEvents($year, $equipoId);
        $months = $this->buildMonths($year, $fetched['by_date']);

        $vars = array_merge($this->buildCommonVariables(), [
            'pageTitle'        => $this->title,
            'year'             => $year,
            'prev_year'        => $year - 1,
            'next_year'        => $year + 1,
            'months'           => $months,
            'events'           => $fetched['events'],
            'event_count'      => count($fetched['events']),
            'equipo_options'   => $this->getEquipoOptions(),
            'filter_equipo'    => $equipoId,
            'filter_equipo_label' => $equipoId ? $this->getEquipoLabel($equipoId) : null,
            'today'            => date('Y-m-d'),
        ]);

        try {
            $template = $twig->load($this->templateDir . '/calendario.twig');
            return $template->render($vars);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }
}
