<?php
namespace Tuqan\Pages\Aspectos;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogListado;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Aspectos matrix view (Stage 9.14 first slice).
 * Grouped presentation of aspects with their evaluation scores (matrix-style table).
 * Reuses 9.8 + 9.13 helpers. Full editable matrix, revisiones and cuestionario flows deferred.
 */
class Matriz extends CatalogListado
{
    protected string $table       = 'aspectos';
    protected string $title       = 'Matriz de Aspectos';
    protected string $templateDir = 'aspectos';
    protected string $flashPrefix = 'aspectos';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, tipo_aspecto, magnitud, gravedad, frecuencia, impacto, probabilidad, severidad, area, activo FROM {$this->table} ORDER BY area, tipo_aspecto, id";
    }

    protected function mapRow($row): array
    {
        return [
            'id'            => $row[0],
            'nombre'        => $row[1],
            'tipo_aspecto'  => $row[2],
            'magnitud'      => $row[3],
            'gravedad'      => $row[4],
            'frecuencia'    => $row[5],
            'impacto'       => $row[6],
            'probabilidad'  => $row[7],
            'severidad'     => $row[8],
            'area'          => $row[9],
            'activo'        => $row[10],
        ];
    }

    protected function fetchMatrixItems(): array
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

    protected function buildMatrixVariables(array $items): array
    {
        $base = $this->buildCommonVariables(); // from 9.13 cross-cut

        // Group by area for matrix feel
        $grouped = $this->groupItems($items, 'area');

        return array_merge($base, [
            'aspectos' => $items,
            'grouped'  => $grouped,
        ]);
    }

    public function ShowPage()
    {
        $twig = $this->initTwig(); // from 9.13 cross-cut

        $items = $this->fetchMatrixItems();
        $variables = $this->buildMatrixVariables($items);

        try {
            $template = $twig->load($this->templateDir . '/matriz.twig');
            return $template->render($variables);
        } catch (\Exception $e) {
            return "Error al cargar {$this->title}: " . $e->getMessage();
        }
    }
}
