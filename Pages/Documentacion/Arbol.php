<?php
namespace Tuqan\Pages\Documentacion;

use Tuqan\Pages\Catalog\CatalogTree;

/**
 * Modern tree/arbol view shell for Documentación (Stage 9.12).
 * Grouped view (by tipo_documento/area) as first tree-like experience.
 * Now uses full tree base (Stage 9.16).
 * Defers full arbol_documentos logic, editor, perfiles, workflows.
 */
class Arbol extends CatalogTree
{
    protected string $table       = 'documentos';
    protected string $title       = 'Árbol de Documentos';
    protected string $templateDir = 'documentacion';
    protected string $flashPrefix = 'documento';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, codigo, estado, revision, activo, tipo_documento, area FROM {$this->table} ORDER BY tipo_documento, area, id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'            => $row[0],
            'nombre'        => $row[1],
            'codigo'        => $row[2],
            'estado'        => $row[3],
            'revision'      => $row[4],
            'activo'        => $row[5],
            'tipo_documento'=> $row[6],
            'area'          => $row[7],
        ];
    }

    /**
     * Implement for CatalogTree base (Stage 9.16).
     */
    protected function fetchTreeItems(): array
    {
        $db = $this->getDb();
        $db->consulta($this->getSelectSql());

        $items = [];
        while ($row = $db->coger_Fila()) {
            $items[] = $this->mapRow($row);
        }
        $db->desconexion();
        return $items;
    }

    /**
     * Implement for CatalogTree base (Stage 9.16).
     */
    protected function buildTreeSpecificVariables(array $items): array
    {
        // Simple grouping for tree feel (by tipo_documento as top level)
        $grouped = $this->groupItems($items, 'tipo_documento');  // cross-cut helper (Stage 9.13)

        return [
            'documentos' => $items,
            'grouped'    => $grouped,
        ];
    }
}
