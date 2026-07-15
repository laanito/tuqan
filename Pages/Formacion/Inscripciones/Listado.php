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

    // 9.30: filter by curso; fix list key for nested template
    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, usuario, curso, inscrito, verificado FROM {$this->table}";
        $params = [];
        if ($filters['curso'] !== null) {
            $sql .= ' WHERE curso = ?';
            $params[] = $filters['curso'];
        }
        $sql .= ' ORDER BY id';

        $db = $this->getDb();
        if ($params) {
            $db->consultaPreparada($sql, $params);
        } else {
            $db->consulta($sql);
        }
        $items = [];
        while ($row = $db->coger_Fila()) {
            $items[] = $this->mapRow($row);
        }
        $db->desconexion();

        foreach ($items as &$item) {
            $item['usuario_label'] = $this->getRelatedLabel('usuarios', $item['usuario'] ?? null);
            $item['curso_label'] = $this->getRelatedLabel('cursos', $item['curso'] ?? null);
        }
        unset($item);
        return $items;
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['inscripcion'] = $items;
        $filters = $this->getFilterParams();
        $vars['curso_options'] = $this->getRelatedOptions('cursos', 'nombre');
        $vars['filter_curso'] = $filters['curso'];
        if ($filters['curso'] !== null) {
            $vars['filter_curso_label'] = $this->getRelatedLabel('cursos', $filters['curso']);
        }
        return $vars;
    }
}
