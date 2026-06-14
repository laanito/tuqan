<?php

namespace Tuqan\Pages\Equipos;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'equipos';
    protected string $title       = 'Equipos';
    protected string $templateDir = 'equipos';
    protected string $flashPrefix = 'equipo';

    // Override because the entity uses 'numero' + 'descripcion' (no 'nombre') and several extra columns.
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
}