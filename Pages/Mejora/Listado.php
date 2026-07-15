<?php
namespace Tuqan\Pages\Mejora;
use Tuqan\Pages\Catalog\CatalogListado;
class Listado extends CatalogListado
{
    protected string $table       = 'acciones_mejora';
    protected string $title       = 'Acciones de Mejora';
    protected string $templateDir = 'mejora';
    protected string $flashPrefix = 'mejora';

    protected function getSelectSql(): string
    {
        return "SELECT id, fecha, descripcion, area, cerrada, tipo, cliente, usuario_detectado, usuario_cerrado, auditoria, usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'                => $row[0],
            'fecha'             => $row[1],
            'descripcion'       => $row[2],
            'area'              => $row[3] ?? null,
            'cerrada'           => $row[4],
            'tipo'              => $row[5] ?? null,
            'cliente'           => $row[6] ?? null,
            'usuario_detectado' => $row[7] ?? null,
            'usuario_cerrado'   => $row[8] ?? null,
            'auditoria'         => $row[9] ?? null,
            'usuario_verifica'  => $row[10] ?? null,
            'fecha_verifica'    => $row[11] ?? null,
            'usuario_implantacion' => $row[12] ?? null,
            'fecha_cierre'      => $row[13] ?? null,
        ];
    }

    // 9.17 + 9.21 + 9.25 relations; 9.27 estado; 9.29 filter by auditoria
    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        $sql = "SELECT id, fecha, descripcion, area, cerrada, tipo, cliente, usuario_detectado, usuario_cerrado, auditoria, usuario_verifica, fecha_verifica, usuario_implantacion, fecha_cierre FROM {$this->table}";
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
            $item['tipo_label'] = $this->getRelatedLabel('tipoaccionesmejora', $item['tipo'] ?? null);
            $item['cliente_label'] = $this->getRelatedLabel('clientes', $item['cliente'] ?? null);
            $item['usuario_detectado_label'] = $this->getRelatedLabel('usuarios', $item['usuario_detectado'] ?? null);
            $item['usuario_cerrado_label'] = $this->getRelatedLabel('usuarios', $item['usuario_cerrado'] ?? null);
            $item['auditoria_label'] = $this->getRelatedLabel('auditorias', $item['auditoria'] ?? null);
            $item['usuario_verifica_label'] = $this->getRelatedLabel('usuarios', $item['usuario_verifica'] ?? null);
            $item['usuario_implantacion_label'] = $this->getRelatedLabel('usuarios', $item['usuario_implantacion'] ?? null);
            if ($item['cerrada']) {
                $item['estado'] = 'Cerrada';
            } elseif ($item['usuario_verifica']) {
                $item['estado'] = 'Verificada';
            } else {
                $item['estado'] = 'Pendiente';
            }
        }
        unset($item);
        return $items;
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $filters = $this->getFilterParams();
        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');
        $vars['filter_auditoria'] = $filters['auditoria'];
        if ($filters['auditoria'] !== null) {
            $vars['filter_auditoria_label'] = $this->getRelatedLabel('auditorias', $filters['auditoria']);
        }
        return $vars;
    }
}
