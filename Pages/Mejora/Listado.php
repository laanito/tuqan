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
        return "SELECT id, fecha, descripcion, area, cerrada, tipo, cliente FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'          => $row[0],
            'fecha'       => $row[1],
            'descripcion' => $row[2],
            'area'        => $row[3] ?? null,
            'cerrada'     => $row[4],
            'tipo'        => $row[5] ?? null,
            'cliente'     => $row[6] ?? null,
        ];
    }

    // 9.17: use relations polish to enrich with human labels (tipo from tipoaccionesmejora, cliente from clientes)
    protected function fetchItems(): array
    {
        $items = parent::fetchItems();
        foreach ($items as &$item) {
            $item['tipo_label'] = $this->getRelatedLabel('tipoaccionesmejora', $item['tipo'] ?? null);
            $item['cliente_label'] = $this->getRelatedLabel('clientes', $item['cliente'] ?? null);
        }
        unset($item);
        return $items;
    }
}
