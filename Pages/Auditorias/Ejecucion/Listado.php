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

    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['programa_label'] = $this->getRelatedLabel('programa_auditoria', $item['programa'] ?? null);
        }
        unset($item);
        return $items;
    }
}
