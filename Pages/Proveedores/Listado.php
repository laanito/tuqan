<?php

namespace Tuqan\Pages\Proveedores;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table         = 'proveedores';
    protected string $title         = 'Proveedores';
    protected string $templateDir = 'proveedores';
    protected string $flashPrefix = 'proveedor';

    // Override for the extra 'telefono' column (base assumes only id/nombre/activo)
    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, telefono, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'       => $row[0],
            'nombre'   => $row[1],
            'telefono' => $row[2] ?? null,
            'activo'   => $row[3],
        ];
    }
}
