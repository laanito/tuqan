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

    // 9.19 programa relation; 9.29 reverse count of linked Mejora actions
    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['programa_label'] = $this->getRelatedLabel('programa_auditoria', $item['programa'] ?? null);
            $item['mejora_count'] = $this->countMejoraForAuditoria((int)$item['id']);
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
}
