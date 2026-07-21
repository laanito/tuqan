<?php
namespace Tuqan\Pages\Auditorias\Horario;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'horario_auditoria';
    protected string $title       = 'Horario de Auditoría';
    protected string $templateDir = 'auditorias/horario';
    protected string $flashPrefix = 'horario';

    protected function getSelectSql(): string
    {
        return "SELECT id, auditoria, horainicio, horafin, requisito, auditor, area FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'         => $row[0],
            'auditoria'  => $row[1] ?? null,
            'horainicio' => $row[2] ?? null,
            'horafin'    => $row[3] ?? null,
            'requisito'  => $row[4] ?? null,
            'auditor'    => $row[5] ?? null,
            'area'       => $row[6] ?? null,
        ];
    }

    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, auditoria, horainicio, horafin, requisito, auditor, area FROM {$this->table}";
        $params = [];
        if ($filters['auditoria'] !== null) {
            $sql .= ' WHERE auditoria = ?';
            $params[] = $filters['auditoria'];
        }
        $sql .= ' ORDER BY horainicio NULLS LAST, id';

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
            $item['auditoria_label'] = $this->getRelatedLabel('auditorias', $item['auditoria'] ?? null);
            $item['area_label'] = null;
            try {
                $item['area_label'] = $this->getRelatedLabel('areas', $item['area'] ?? null);
            } catch (\Throwable $e) {
                // areas may not exist
            }
            $item['horainicio_display'] = self::formatTs($item['horainicio'] ?? null);
            $item['horafin_display'] = self::formatTs($item['horafin'] ?? null);
        }
        unset($item);
        return $items;
    }

    public static function formatTs($ts): string
    {
        if ($ts === null || $ts === '') {
            return '—';
        }
        // Accept "Y-m-d H:i:s" or DateTime-like strings
        $t = strtotime((string)$ts);
        if ($t === false) {
            return (string)$ts;
        }
        return date('Y-m-d H:i', $t);
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['horario'] = $items;
        $filters = $this->getFilterParams();
        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');
        $vars['filter_auditoria'] = $filters['auditoria'];
        if ($filters['auditoria'] !== null) {
            $vars['filter_auditoria_label'] = $this->getRelatedLabel('auditorias', $filters['auditoria']);
        }
        return $vars;
    }
}
