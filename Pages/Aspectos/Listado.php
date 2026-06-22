<?php
namespace Tuqan\Pages\Aspectos;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'aspectos';
    protected string $title       = 'Aspectos Ambientales';
    protected string $templateDir = 'aspectos';
    protected string $flashPrefix = 'aspectos';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, tipo_aspecto, magnitud, gravedad, frecuencia, activo, area FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'           => $row[0],
            'nombre'       => $row[1],
            'tipo_aspecto' => $row[2],
            'magnitud'     => $row[3],
            'gravedad'     => $row[4],
            'frecuencia'   => $row[5],
            'activo'       => $row[6],
            'area'         => $row[7] ?? null,
        ];
    }
}
