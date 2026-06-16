<?php
namespace Tuqan\Pages\Mejora;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'acciones_mejora';
    protected string $title       = 'Acciones de Mejora';
    protected string $templateDir = 'mejora';
    protected string $flashPrefix = 'mejora';

    protected function getSelectSql(): string
    {
        return "SELECT id, fecha, descripcion, area, cerrada, tipo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'          => $row[0],
            'fecha'       => $row[1],
            'descripcion' => $row[2],
            'area'        => $row[3] ?? null,
            'cerrada'     => $row[4],
            'tipo'        => $row[5] ?? null,
        ];
    }
}
