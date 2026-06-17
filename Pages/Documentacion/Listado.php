<?php
namespace Tuqan\Pages\Documentacion;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'documentos';
    protected string $title       = 'Documentación';
    protected string $templateDir = 'documentacion';
    protected string $flashPrefix = 'documento';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, codigo, estado, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'     => $row[0],
            'nombre' => $row[1],
            'codigo' => $row[2] ?? null,
            'estado' => $row[3] ?? null,
            'activo' => $row[4],
        ];
    }
}
