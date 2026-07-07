<?php
namespace Tuqan\Pages\Formacion\Cursos;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'cursos';
    protected string $title       = 'Cursos de Formación';
    protected string $templateDir = 'formacion/cursos';
    protected string $flashPrefix = 'curso';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, plan, num_horas, fecha_prevista, activo, estado FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'            => $row[0],
            'nombre'        => $row[1],
            'plan'          => $row[2],
            'num_horas'     => $row[3],
            'fecha_prevista'=> $row[4],
            'activo'        => $row[5],
            'estado'        => $row[6] ?? 0,
        ];
    }

    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['plan_label'] = $this->getRelatedLabel('plan_formacion', $item['plan'] ?? null);
        }
        unset($item);
        return $items;
    }
}
