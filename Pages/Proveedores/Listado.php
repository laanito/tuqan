<?php
namespace Tuqan\Pages\Proveedores;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'proveedores';
    protected string $title       = 'Proveedores';
    protected string $templateDir = 'proveedores';
    protected string $flashPrefix = 'proveedor';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, telefono, activo, fecha_homologacion, fecha_deshomologacion, ultima_revision FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'                     => $row[0],
            'nombre'                 => $row[1],
            'telefono'               => $row[2] ?? null,
            'activo'                 => $row[3],
            'fecha_homologacion'     => $row[4] ?? null,
            'fecha_deshomologacion'  => $row[5] ?? null,
            'ultima_revision'        => $row[6] ?? null,
        ];
    }

    protected function fetchItems(): array
    {
        try {
            $db = $this->getDb();
            $db->consulta($this->getSelectSql());
            $items = [];
            while ($row = $db->coger_Fila()) {
                $items[] = $this->mapRow($row);
            }
            $db->desconexion();
        } catch (\Throwable $e) {
            // Fallback if columns not yet applied
            $items = parent::fetchItems();
            foreach ($items as &$item) {
                $item['fecha_homologacion'] = null;
                $item['fecha_deshomologacion'] = null;
                $item['ultima_revision'] = null;
            }
            unset($item);
        }

        foreach ($items as &$item) {
            $item['homologado'] = HomologacionHelper::isHomologado(
                $item['fecha_homologacion'] ?? null,
                $item['fecha_deshomologacion'] ?? null
            );
            $item['homologacion_label'] = HomologacionHelper::label(
                $item['fecha_homologacion'] ?? null,
                $item['fecha_deshomologacion'] ?? null
            );
            $item['homologacion_badge'] = HomologacionHelper::badgeClass(
                $item['fecha_homologacion'] ?? null,
                $item['fecha_deshomologacion'] ?? null
            );
            $item['producto_count'] = $this->countProductos((int)$item['id']);
        }
        unset($item);
        return $items;
    }

    protected function countProductos(int $proveedorId): int
    {
        if ($proveedorId <= 0) {
            return 0;
        }
        try {
            $db = $this->getDb();
            $db->consultaPreparada('SELECT COUNT(*) FROM productos WHERE proveedor = ?', [$proveedorId]);
            $row = $db->coger_Fila();
            $db->desconexion();
            return (int)($row[0] ?? 0);
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
