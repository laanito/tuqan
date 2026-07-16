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
        return "SELECT id, nombre, codigo, estado, activo, revisado_por, aprobado_por FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'           => $row[0],
            'nombre'       => $row[1],
            'codigo'       => $row[2] ?? null,
            'estado'       => $row[3] ?? null,
            'activo'       => $row[4],
            'revisado_por' => $row[5] ?? null,
            'aprobado_por' => $row[6] ?? null,
        ];
    }

    // 9.24 labels; 9.31 estado filter + labels
    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, nombre, codigo, estado, activo, revisado_por, aprobado_por FROM {$this->table}";
        $params = [];
        $where = [];

        if ($filters['estado'] !== null) {
            $where[] = 'estado = ?';
            $params[] = $filters['estado'];
        }
        if (isset($filters['activo']) && $filters['activo'] !== null) {
            $where[] = 'activo = ?';
            $params[] = $filters['activo'] ? true : false;
        }
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
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
            $item['revisado_por_label'] = $this->getRelatedLabel('usuarios', $item['revisado_por'] ?? null);
            $item['aprobado_por_label'] = $this->getRelatedLabel('usuarios', $item['aprobado_por'] ?? null);
            $item['estado_label'] = EstadoHelper::label($item['estado'] ?? null);
            $item['estado_badge'] = EstadoHelper::badgeClass($item['estado'] ?? null);
        }
        unset($item);
        return $items;
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        // templateDir is documentacion; keep that key (parent uses templateDir)
        $filters = $this->getFilterParams();
        $vars['estado_options'] = EstadoHelper::options();
        $vars['filter_estado'] = $filters['estado'];
        $vars['filter_activo'] = $filters['activo'];
        if ($filters['estado'] !== null) {
            $vars['filter_estado_label'] = EstadoHelper::label($filters['estado']);
        }
        return $vars;
    }
}
