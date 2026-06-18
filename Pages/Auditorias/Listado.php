<?php
namespace Tuqan\Pages\Auditorias;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'programa_auditoria';
    protected string $title       = 'Programas de Auditoría';
    protected string $templateDir = 'auditorias';
    protected string $flashPrefix = 'auditorias';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, vigente, activo, revision FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'       => $row[0],
            'nombre'   => $row[1],
            'vigente'  => $row[2],
            'activo'   => $row[3],
            'revision' => $row[4] ?? null,
        ];
    }
}
