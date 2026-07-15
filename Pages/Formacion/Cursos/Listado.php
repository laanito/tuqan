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

    // 9.30: filter by plan + reverse inscription counts; fix list key for nested template
    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, nombre, plan, num_horas, fecha_prevista, activo, estado FROM {$this->table}";
        $params = [];
        if ($filters['plan'] !== null) {
            $sql .= ' WHERE plan = ?';
            $params[] = $filters['plan'];
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
            $item['plan_label'] = $this->getRelatedLabel('plan_formacion', $item['plan'] ?? null);
            $item['inscripcion_count'] = $this->countInscripcionesForCurso((int)$item['id']);
        }
        unset($item);
        return $items;
    }

    protected function countInscripcionesForCurso(int $cursoId): int
    {
        if ($cursoId <= 0) {
            return 0;
        }
        $db = $this->getDb();
        $db->consultaPreparada('SELECT COUNT(*) FROM alumnos WHERE curso = ?', [$cursoId]);
        $row = $db->coger_Fila();
        $db->desconexion();
        return (int)($row[0] ?? 0);
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        // Nested templateDir is "formacion/cursos"; templates expect "curso"
        $vars['curso'] = $items;
        $filters = $this->getFilterParams();
        $vars['plan_options'] = $this->getRelatedOptions('plan_formacion', 'nombre');
        $vars['filter_plan'] = $filters['plan'];
        if ($filters['plan'] !== null) {
            $vars['filter_plan_label'] = $this->getRelatedLabel('plan_formacion', $filters['plan']);
        }
        return $vars;
    }
}
