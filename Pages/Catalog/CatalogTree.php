<?php

namespace Tuqan\Pages\Catalog;

/**
 * Shared base for tree / arbol views (Stage 9.16 full tree base cross-cut).
 * Builds on CatalogListado + prior cross-cuts (9.8 helpers, 9.13 tree helpers, 9.15 filters).
 * Subclasses implement fetchTreeItems() + buildTreeSpecificVariables().
 * Handles common tree ShowPage, Twig init, variable merging.
 *
 * Future trees can now be much smaller.
 */
abstract class CatalogTree extends CatalogListado
{
    /**
     * Subclasses must provide the tree-structured data.
     */
    abstract protected function fetchTreeItems(): array;

    /**
     * Subclasses provide module-specific variables (e.g. 'procesos' or 'grouped').
     * The base will merge with common tree variables (sidebar, pageTitle, isTreeView, flashes, etc.).
     */
    abstract protected function buildTreeSpecificVariables(array $items): array;

    public function ShowPage()
    {
        $twig = $this->initTwig();

        $items = $this->fetchTreeItems();
        $specific = $this->buildTreeSpecificVariables($items);
        $variables = $this->buildTreeVariables($items, $specific);

        try {
            $template = $twig->load($this->templateDir . '/arbol.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }

    protected function buildTreeVariables(array $items, array $specific): array
    {
        $base = $this->buildCommonVariables();  // from 9.13

        return array_merge($base, $specific);
    }
}
