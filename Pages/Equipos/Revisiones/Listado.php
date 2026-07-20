<?php
namespace Tuqan\Pages\Equipos\Revisiones;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'mantenimientos';
    protected string $title       = 'Revisiones de Equipos';
    protected string $templateDir = 'equipos/revisiones';
    protected string $flashPrefix = 'revision';

    protected function getSelectSql(): string
    {
        return "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'             => $row[0],
            'equipo'         => $row[1] ?? null,
            'tipo'           => $row[2] ?? null,
            'fecha_prevista' => $row[3] ?? null,
            'fecha_realiza'  => $row[4] ?? null,
            'comentarios'    => $row[5] ?? '',
            'motivos'        => $row[6] ?? '',
        ];
    }

    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos FROM {$this->table}";
        $params = [];
        if ($filters['equipo'] !== null) {
            $sql .= ' WHERE equipo = ?';
            $params[] = $filters['equipo'];
        }
        $sql .= ' ORDER BY id DESC';

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
            $item['equipo_label'] = $this->getEquipoLabel($item['equipo'] ?? null);
            $item['tipo_label'] = self::tipoLabel($item['tipo'] ?? null);
        }
        unset($item);
        return $items;
    }

    protected function getEquipoLabel($id): ?string
    {
        if (!$id) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada('SELECT id, numero, descripcion FROM equipos WHERE id = ?', [(int)$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        return $row[1] . ' — ' . $row[2];
    }

    public static function tipoLabel($tipo): string
    {
        $map = [
            'revision'   => 'Revisión',
            'preventivo' => 'Preventivo',
            'correctivo' => 'Correctivo',
        ];
        return $map[$tipo ?? ''] ?? ($tipo ?: '—');
    }

    public static function tipoOptions(): array
    {
        return [
            ['id' => 'revision', 'nombre' => 'Revisión'],
            ['id' => 'preventivo', 'nombre' => 'Preventivo'],
            ['id' => 'correctivo', 'nombre' => 'Correctivo'],
        ];
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['revision'] = $items;
        $filters = $this->getFilterParams();
        $vars['equipo_options'] = $this->getEquipoOptions();
        $vars['filter_equipo'] = $filters['equipo'];
        if ($filters['equipo'] !== null) {
            $vars['filter_equipo_label'] = $this->getEquipoLabel($filters['equipo']);
        }
        return $vars;
    }

    protected function getEquipoOptions(): array
    {
        $db = $this->getDb();
        $db->consulta('SELECT id, numero, descripcion FROM equipos ORDER BY numero');
        $opts = [];
        while ($row = $db->coger_Fila()) {
            $opts[] = ['id' => $row[0], 'nombre' => $row[1] . ' — ' . $row[2]];
        }
        $db->desconexion();
        return $opts;
    }
}
