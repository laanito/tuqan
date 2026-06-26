<?php
namespace Tuqan\Pages\Procesos;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'procesos';
    protected string $title       = 'Procesos';
    protected string $templateDir = 'procesos';
    protected string $flashPrefix = 'proceso';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, codigo, revision, padre, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'       => $row[0],
            'nombre'   => $row[1],
            'codigo'   => $row[2],
            'revision' => $row[3],
            'padre'    => $row[4],
            'activo'   => $row[5],
        ];
    }
}
