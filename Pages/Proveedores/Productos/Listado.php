<?php
namespace Tuqan\Pages\Proveedores\Productos;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'productos';
    protected string $title       = 'Productos de Proveedores';
    protected string $templateDir = 'proveedores/productos';
    protected string $flashPrefix = 'producto';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, proveedor, valor, homologado, activo, fecha_revision FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'             => $row[0],
            'nombre'         => $row[1],
            'proveedor'      => $row[2] ?? null,
            'valor'          => $row[3] ?? 0,
            'homologado'     => $row[4],
            'activo'         => $row[5],
            'fecha_revision' => $row[6] ?? null,
        ];
    }

    protected function fetchItems(): array
    {
        $filters = $this->getFilterParams();
        // reuse equipo slot? No - add proveedor via GET directly (getFilterParams has no proveedor)
        $proveedor = (isset($_GET['proveedor']) && $_GET['proveedor'] !== '') ? (int)$_GET['proveedor'] : null;

        $sql = "SELECT id, nombre, proveedor, valor, homologado, activo, fecha_revision FROM {$this->table}";
        $params = [];
        if ($proveedor !== null && $proveedor > 0) {
            $sql .= ' WHERE proveedor = ?';
            $params[] = $proveedor;
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
            $item['proveedor_label'] = $this->getRelatedLabel('proveedores', $item['proveedor'] ?? null);
        }
        unset($item);
        return $items;
    }

    protected function buildListVariables(array $items): array
    {
        $vars = parent::buildListVariables($items);
        $vars['producto'] = $items;
        $proveedor = (isset($_GET['proveedor']) && $_GET['proveedor'] !== '') ? (int)$_GET['proveedor'] : null;
        $vars['filter_proveedor'] = $proveedor;
        $vars['proveedor_options'] = $this->getRelatedOptions('proveedores', 'nombre');
        if ($proveedor) {
            $vars['filter_proveedor_label'] = $this->getRelatedLabel('proveedores', $proveedor);
        }
        return $vars;
    }
}
