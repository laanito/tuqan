<?php
namespace Tuqan\Pages\Auditorias\Ejecucion;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'auditorias';
    protected string $title       = 'Auditorías (Ejecución)';
    protected string $templateDir = 'auditorias/ejecucion';
    protected string $flashPrefix = 'auditoria';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, fecha, programa, estado, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'       => $row[0],
            'nombre'   => $row[1],
            'fecha'    => $row[2],
            'programa' => $row[3],
            'estado'   => $row[4] ?? 0,
            'activo'   => $row[5],
        ];
    }

    // 9.19 programa; 9.29 Mejora count; 9.32 hallazgos count
    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['programa_label'] = $this->getRelatedLabel('programa_auditoria', $item['programa'] ?? null);
            $item['mejora_count'] = $this->countMejoraForAuditoria((int)$item['id']);
            $item['hallazgo_count'] = $this->countHallazgosForAuditoria((int)$item['id']);
            $item['horario_count'] = $this->countHorarioForAuditoria((int)$item['id']);
        }
        unset($item);
        return $items;
    }

    protected function countMejoraForAuditoria(int $auditoriaId): int
    {
        if ($auditoriaId <= 0) {
            return 0;
        }
        $db = $this->getDb();
        $db->consultaPreparada(
            'SELECT COUNT(*) FROM acciones_mejora WHERE auditoria = ?',
            [$auditoriaId]
        );
        $row = $db->coger_Fila();
        $db->desconexion();
        return (int)($row[0] ?? 0);
    }

    protected function countHallazgosForAuditoria(int $auditoriaId): int
    {
        if ($auditoriaId <= 0) {
            return 0;
        }
        $db = $this->getDb();
        // Table may not exist until patch 0042; fail soft for older DBs mid-migration
        try {
            $db->consultaPreparada(
                'SELECT COUNT(*) FROM hallazgos_auditoria WHERE auditoria = ?',
                [$auditoriaId]
            );
            $row = $db->coger_Fila();
            $db->desconexion();
            return (int)($row[0] ?? 0);
        } catch (\Throwable $e) {
            $db->desconexion();
            return 0;
        }
    }

    protected function countHorarioForAuditoria(int $auditoriaId): int
    {
        if ($auditoriaId <= 0) {
            return 0;
        }
        try {
            $db = $this->getDb();
            $db->consultaPreparada(
                'SELECT COUNT(*) FROM horario_auditoria WHERE auditoria = ?',
                [$auditoriaId]
            );
            $row = $db->coger_Fila();
            $db->desconexion();
            return (int)($row[0] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    // Nested templateDir is "auditorias/ejecucion"; listado.twig expects "auditoria"
    // (same pattern as Hallazgos/Cursos/Productos nested listados).
    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['auditoria'] = $items;
        return $vars;
    }
}
