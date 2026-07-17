<?php
namespace Tuqan\Pages\Auditorias\Hallazgos;
use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'hallazgos_auditoria';
    protected string $title       = 'Hallazgos de Auditoría';
    protected string $templateDir = 'auditorias/hallazgos';
    protected string $flashPrefix = 'hallazgo';

    protected function getSelectSql(): string
    {
        return "SELECT id, auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'            => $row[0],
            'auditoria'     => $row[1] ?? null,
            'fecha'         => $row[2] ?? null,
            'descripcion'   => $row[3],
            'tipo'          => $row[4] ?? null,
            'gravedad'      => $row[5] ?? null,
            'cerrado'       => $row[6],
            'accion_mejora' => $row[7] ?? null,
            'activo'        => $row[8],
        ];
    }

    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo FROM {$this->table}";
        $params = [];
        if ($filters['auditoria'] !== null) {
            $sql .= ' WHERE auditoria = ?';
            $params[] = $filters['auditoria'];
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
            $item['auditoria_label'] = $this->getRelatedLabel('auditorias', $item['auditoria'] ?? null);
            $item['mejora_label'] = $this->getMejoraLabel($item['accion_mejora'] ?? null);
            $item['tipo_label'] = self::tipoLabel($item['tipo'] ?? null);
            $item['gravedad_label'] = self::gravedadLabel($item['gravedad'] ?? null);
        }
        unset($item);
        return $items;
    }

    protected function getMejoraLabel($id): ?string
    {
        if (!$id) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada('SELECT id, descripcion FROM acciones_mejora WHERE id = ?', [(int)$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        $desc = $row[1] ?? '';
        if (strlen($desc) > 48) {
            $desc = substr($desc, 0, 45) . '…';
        }
        return '#' . $row[0] . ' ' . $desc;
    }

    public static function tipoLabel($tipo): string
    {
        $map = [
            'no_conformidad' => 'No conformidad',
            'observacion'    => 'Observación',
            'oportunidad'    => 'Oportunidad',
        ];
        return $map[$tipo ?? ''] ?? ($tipo ?: '—');
    }

    public static function gravedadLabel($g): string
    {
        $map = [
            'mayor'  => 'Mayor',
            'menor'  => 'Menor',
            'leve'   => 'Leve',
        ];
        return $map[$g ?? ''] ?? ($g ?: '—');
    }

    public static function tipoOptions(): array
    {
        return [
            ['id' => 'no_conformidad', 'nombre' => 'No conformidad'],
            ['id' => 'observacion', 'nombre' => 'Observación'],
            ['id' => 'oportunidad', 'nombre' => 'Oportunidad'],
        ];
    }

    public static function gravedadOptions(): array
    {
        return [
            ['id' => 'mayor', 'nombre' => 'Mayor'],
            ['id' => 'menor', 'nombre' => 'Menor'],
            ['id' => 'leve', 'nombre' => 'Leve'],
        ];
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['hallazgo'] = $items;
        $filters = $this->getFilterParams();
        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');
        $vars['filter_auditoria'] = $filters['auditoria'];
        if ($filters['auditoria'] !== null) {
            $vars['filter_auditoria_label'] = $this->getRelatedLabel('auditorias', $filters['auditoria']);
        }
        return $vars;
    }
}
