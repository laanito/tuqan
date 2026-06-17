<?php
namespace Tuqan\Pages\Formacion;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'plan_formacion';
    protected string $title       = 'Planes de Formación';
    protected string $templateDir = 'formacion';
    protected string $flashPrefix = 'formacion';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, vigente, descripcion, activo, calidad, medioambiente FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'             => $row[0],
            'nombre'         => $row[1],
            'vigente'        => $row[2],
            'descripcion'    => $row[3] ?? null,
            'activo'         => $row[4],
            'calidad'        => $row[5],
            'medioambiente'  => $row[6],
        ];
    }
}
