<?php
namespace Tuqan\Pages\Documentacion;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogListado;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Modern tree/arbol view shell for Documentación (Stage 9.12).
 * Grouped view (by tipo_documento/area) as first tree-like experience.
 * Reuses 9.8 helpers. Defers full arbol_documentos logic, editor, perfiles, workflows.
 */
class Arbol extends CatalogListado
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

    protected function buildTreeVariables(array $items): array
    {
        $flash = $this->getFlashData();
        $context = $this->getUserContext();

        // Simple grouping for tree feel (by tipo_documento as top level)
        $grouped = [];
        foreach ($items as $item) {
            $key = $item['tipo_documento'] ?? 0;
            if (!isset($grouped[$key])) $grouped[$key] = [];
            $grouped[$key][] = $item;
        }

        return array_merge([
            'sidebarMenu'  => $this->getSidebarMenu(),
            'documentos'   => $items,
            'grouped'      => $grouped,
            'pageTitle'    => $this->title,
            'flashSuccess' => $flash['flashSuccess'],
            'flashError'   => $flash['flashError'],
            'isTreeView'   => true,
        ], $context);
    }

    public function ShowPage()
    {
        Config::initialize();

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, [
            'cache' => Config::$cache_path,
        ]);

        $items = $this->fetchTreeItems();
        $variables = $this->buildTreeVariables($items);

        try {
            $template = $twig->load($this->templateDir . '/arbol.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }
}
