<?php
namespace Tuqan\Pages\Auditorias\Informe;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogFormulario;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Printable informe ficha (Stage 9.37).
 * HTML + print CSS; GenPDF deferred.
 */
class Ficha extends CatalogFormulario
{
    protected string $table        = 'auditorias';
    protected string $title        = 'Ficha de Informe';
    protected string $templateDir  = 'auditorias/informes';
    protected string $flashPrefix  = 'informe';
    protected string $listRoute    = '/admin/auditorias/ejecucion';

    protected function getSelectForForm(): string
    {
        return "SELECT id, programa, nombre, fecha, estado, descripcion, observaciones, activo,
                       requisitos, alcance, interna, fecha_realiza, lugar_informe, fecha_informe,
                       recomendaciones_informe, conclusiones_informe
                FROM {$this->table} WHERE id = ?";
    }

    protected function loadItem($id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada($this->getSelectForForm(), [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }

        return [
            'id'                      => $row[0],
            'programa'                => $row[1],
            'nombre'                  => $row[2],
            'fecha'                   => $row[3],
            'estado'                  => $row[4] ?? 0,
            'descripcion'             => $row[5] ?? null,
            'observaciones'           => $row[6] ?? null,
            'activo'                  => $row[7],
            'requisitos'              => $row[8] ?? null,
            'alcance'                 => $row[9] ?? null,
            'interna'                 => $row[10],
            'fecha_realiza'           => $row[11] ?? null,
            'lugar_informe'           => $row[12] ?? null,
            'fecha_informe'           => $row[13] ?? null,
            'recomendaciones_informe' => $row[14] ?? null,
            'conclusiones_informe'    => $row[15] ?? null,
        ];
    }

    public function ShowPage($id = null)
    {
        Config::initialize();

        if ($id === null && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
        }
        $id = (int)$id;
        if ($id <= 0) {
            header('Location: ' . $this->listRoute);
            exit;
        }

        $item = $this->loadItem($id);
        if (!$item) {
            header('Location: ' . $this->listRoute);
            exit;
        }

        $item['programa_label'] = $this->getRelatedLabel('programa_auditoria', $item['programa'] ?? null);
        $item['interna_label'] = $item['interna'] ? 'Interna' : 'Externa';

        $loader = new FilesystemLoader(Config::$template_path);
        $twig = new Environment($loader, ['cache' => Config::$cache_path]);

        $vars = array_merge($this->getUserContext(), [
            'sidebarMenu' => $this->getSidebarMenu(),
            'pageTitle'   => 'Informe — ' . ($item['nombre'] ?? ('Auditoría #' . $id)),
            'auditoria'   => $item,
            'hallazgos'   => $this->fetchHallazgos($id),
            'mejoras'     => $this->fetchMejoras($id),
            'flash_success' => $_SESSION[$this->flashPrefix . '_flash_success'] ?? null,
        ]);
        unset($_SESSION[$this->flashPrefix . '_flash_success']);

        try {
            return $twig->load($this->templateDir . '/ficha.twig')->render($vars);
        } catch (\Exception $e) {
            return 'Error al cargar la ficha de informe: ' . $e->getMessage();
        }
    }

    protected function fetchHallazgos(int $auditoriaId): array
    {
        try {
            $db = $this->getDb();
            $db->consultaPreparada(
                'SELECT id, fecha, descripcion, tipo, gravedad, cerrado FROM hallazgos_auditoria WHERE auditoria = ? ORDER BY id',
                [$auditoriaId]
            );
            $items = [];
            while ($row = $db->coger_Fila()) {
                $items[] = [
                    'id'          => $row[0],
                    'fecha'       => $row[1],
                    'descripcion' => $row[2],
                    'tipo'        => $row[3],
                    'gravedad'    => $row[4],
                    'cerrado'     => $row[5],
                ];
            }
            $db->desconexion();
            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function fetchMejoras(int $auditoriaId): array
    {
        try {
            $db = $this->getDb();
            $db->consultaPreparada(
                'SELECT id, fecha, descripcion, cerrada, usuario_verifica FROM acciones_mejora WHERE auditoria = ? ORDER BY id',
                [$auditoriaId]
            );
            $items = [];
            while ($row = $db->coger_Fila()) {
                $cerrada = $row[3];
                $verifica = $row[4] ?? null;
                if ($cerrada) {
                    $estado = 'Cerrada';
                } elseif ($verifica) {
                    $estado = 'Verificada';
                } else {
                    $estado = 'Pendiente';
                }
                $items[] = [
                    'id'          => $row[0],
                    'fecha'       => $row[1],
                    'descripcion' => $row[2],
                    'estado'      => $estado,
                ];
            }
            $db->desconexion();
            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }
}
