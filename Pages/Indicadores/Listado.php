<?php
namespace Tuqan\Pages\Indicadores;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'indicadores';
    protected string $title       = 'Indicadores';
    protected string $templateDir = 'indicadores';
    protected string $flashPrefix = 'indicador';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, definicion, valor_objetivo, valor_tolerable, activo, genera_objetivo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'              => $row[0],
            'nombre'          => $row[1],
            'definicion'      => $row[2],
            'valor_objetivo'  => $row[3],
            'valor_tolerable' => $row[4],
            'activo'          => $row[5],
            'genera_objetivo' => $row[6],
        ];
    }
}
