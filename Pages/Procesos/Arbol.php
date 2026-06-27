<?php
namespace Tuqan\Pages\Procesos;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogListado;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Árbol / tree view for Procesos (Stage 9.11 deeper slice).
 * Shows hierarchy via padre + basic linked contenido_procesos details.
 * Reuses 9.8 helpers (getDb, sidebar, user context, flashes).
 * The legacy "arbol" entry point now lands here (modern shell).
 * Full editing, drag-drop, flujogramas and array management deferred.
 */
class Arbol extends CatalogListado
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
     * Build a simple flat list with parent name + attached contenido summary.
     * For a richer nested tree we could recurse; start flat + indent in template.
     */
    protected function fetchArbolItems(): array
    {
        $db = $this->getDb();

        // Procesos
        $db->consulta($this->getSelectSql());
        $procesos = [];
        $byId = [];
        while ($row = $db->coger_Fila()) {
            $p = $this->mapRow($row);
            $p['parent_nombre'] = '';
            $procesos[] = $p;
            $byId[$p['id']] = &$procesos[count($procesos)-1];
        }

        // Resolve parent names (simple loop)
        foreach ($procesos as &$p) {
            if (!empty($p['padre']) && isset($byId[$p['padre']])) {
                $p['parent_nombre'] = $byId[$p['padre']]['nombre'];
            }
        }
        unset($p);

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

    protected function buildArbolVariables(array $items): array
    {
        $flash   = $this->getFlashData();
        $context = $this->getUserContext();

        return array_merge([
            'sidebarMenu'  => $this->getSidebarMenu(),
            'procesos'     => $items,           // re-used name for template simplicity
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

        $items = $this->fetchArbolItems();
        $variables = $this->buildArbolVariables($items);

        try {
            $template = $twig->load($this->templateDir . '/arbol.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }
}
