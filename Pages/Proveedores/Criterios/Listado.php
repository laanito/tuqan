<?php
namespace Tuqan\Pages\Proveedores\Criterios;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'criterios_homologacion';
    protected string $title       = 'Criterios de Homologación';
    protected string $templateDir = 'proveedores/criterios';
    protected string $flashPrefix = 'criterio';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, valor, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'     => $row[0],
            'nombre' => $row[1],
            'valor'  => $row[2] ?? 0,
            'activo' => $row[3],
        ];
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['criterio'] = $items;
        return $vars;
    }
}
