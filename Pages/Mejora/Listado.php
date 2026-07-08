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
        return "SELECT id, fecha, descripcion, area, cerrada, tipo, cliente, usuario_detectado, usuario_cerrado, auditoria FROM {$this->table} ORDER BY id";
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
        ];
    }

    // 9.17 + 9.21: relations polish (tipo, cliente, users for workflow, auditoria)
    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['tipo_label'] = $this->getRelatedLabel('tipoaccionesmejora', $item['tipo'] ?? null);
            $item['cliente_label'] = $this->getRelatedLabel('clientes', $item['cliente'] ?? null);
            $item['usuario_detectado_label'] = $this->getRelatedLabel('usuarios', $item['usuario_detectado'] ?? null);
            $item['usuario_cerrado_label'] = $this->getRelatedLabel('usuarios', $item['usuario_cerrado'] ?? null);
            $item['auditoria_label'] = $this->getRelatedLabel('auditorias', $item['auditoria'] ?? null);
        }
        unset($item);
        return $items;
    }
}
