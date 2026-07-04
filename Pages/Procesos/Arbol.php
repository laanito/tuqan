<?php
namespace Tuqan\Pages\Procesos;

use Tuqan\Pages\Catalog\CatalogTree;

/**
 * Árbol / tree view for Procesos (Stage 9.11 deeper slice).
 * Shows hierarchy via padre + basic linked contenido_procesos details.
 * Now uses full tree base (Stage 9.16).
 * Full editing, drag-drop, flujogramas and array management deferred.
 */
class Arbol extends CatalogTree
{
    protected string $table       = 'procesos';
    protected string $title       = 'Árbol de Procesos';
    protected string $templateDir = 'procesos';
    protected string $flashPrefix = 'proceso';

    protected function getSelectSql(): string
    {
        // Include padre for hierarchy + enough for display
        return "SELECT id, nombre, codigo, revision, padre, activo FROM {$this->table} ORDER BY id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'       => $row[0],
            'nombre'   => $row[1],
            'codigo'   => $row[2],
            'revision' => $row[3],
            'padre'    => $row[4] ?? 0,
            'activo'   => $row[5],
        ];
    }

    /**
     * Implement for CatalogTree base (Stage 9.16).
     */
    protected function fetchTreeItems(): array
    {
        $db = $this->getDb();

        // Procesos
        $db->consulta($this->getSelectSql());
        $procesos = [];
        while ($row = $db->coger_Fila()) {
            $procesos[] = $this->mapRow($row);
        }

        // Use cross-cut helper (Stage 9.13)
        $procesos = $this->resolveParentNames($procesos);

        // Attach basic contenido_procesos summary for each proceso (0 or 1 for demo)
        $db->consulta("SELECT id, proceso, entradas, salidas, proveedor, cliente, doc_asociada FROM contenido_procesos ORDER BY id");
        $contenidoByProceso = [];
        while ($row = $db->coger_Fila()) {
            $pid = $row[1];
            if (!isset($contenidoByProceso[$pid])) {
                $contenidoByProceso[$pid] = [];
            }
            $contenidoByProceso[$pid][] = [
                'id'           => $row[0],
                'entradas'     => $row[2],
                'salidas'      => $row[3],
                'proveedor'    => $row[4],
                'cliente'      => $row[5],
                'doc_asociada' => $row[6],
            ];
        }

        foreach ($procesos as &$p) {
            $p['contenido'] = $contenidoByProceso[$p['id']] ?? [];
        }
        unset($p);

        $db->desconexion();
        return $procesos;
    }

    /**
     * Implement for CatalogTree base (Stage 9.16).
     */
    protected function buildTreeSpecificVariables(array $items): array
    {
        return [
            'procesos' => $items,  // re-used name for template simplicity
        ];
    }
}
