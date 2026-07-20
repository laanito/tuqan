<?php

namespace Tuqan\Pages\Equipos;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'equipos';
    protected string $title       = 'Equipos';
    protected string $templateDir = 'equipos';
    protected string $flashPrefix = 'equipo';

    protected function getSelectSql(): string
    {
        return "SELECT id, numero, descripcion, modelo, ubicacion, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'          => $row[0],
            'numero'      => $row[1],
            'descripcion' => $row[2],
            'modelo'      => $row[3] ?? null,
            'ubicacion'   => $row[4] ?? null,
            'activo'      => $row[5],
        ];
    }

    // 9.33: reverse count of revisiones (mantenimientos)
    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['revision_count'] = $this->countRevisionesForEquipo((int)$item['id']);
        }
        unset($item);
        return $items;
    }

    protected function countRevisionesForEquipo(int $equipoId): int
    {
        if ($equipoId <= 0) {
            return 0;
        }
        try {
            $db = $this->getDb();
            $db->consultaPreparada('SELECT COUNT(*) FROM mantenimientos WHERE equipo = ?', [$equipoId]);
            $row = $db->coger_Fila();
            $db->desconexion();
            return (int)($row[0] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
