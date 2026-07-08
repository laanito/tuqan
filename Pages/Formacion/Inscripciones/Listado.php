<?php
namespace Tuqan\Pages\Formacion\Inscripciones;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'alumnos';
    protected string $title       = 'Inscripciones a Cursos';
    protected string $templateDir = 'formacion/inscripciones';
    protected string $flashPrefix = 'inscripcion';

    protected function getSelectSql(): string
    {
        return "SELECT id, usuario, curso, inscrito, verificado FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'        => $row[0],
            'usuario'   => $row[1],
            'curso'     => $row[2],
            'inscrito'  => $row[3],
            'verificado'=> $row[4],
        ];
    }

    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['usuario_label'] = $this->getRelatedLabel('usuarios', $item['usuario'] ?? null);
            $item['curso_label'] = $this->getRelatedLabel('cursos', $item['curso'] ?? null);
        }
        unset($item);
        return $items;
    }
}
