<?php
namespace Tuqan\Pages\Auditorias\Informe;

use Tuqan\Pages\Catalog\CatalogFormulario;

/**
 * Auditoría informe editor (Stage 9.37).
 * Edits lugar/fecha/conclusiones/recomendaciones on existing ejecución row.
 * Create new auditoría is not in scope — use Ejecución form.
 */
class Formulario extends CatalogFormulario
{
    protected string $table        = 'auditorias';
    protected string $title        = 'Informe de Auditoría';
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

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);
        $vars['pageTitle'] = $item
            ? 'Informe: ' . ($item['nombre'] ?? ('Auditoría #' . $item['id']))
            : 'Informe de Auditoría';

        $key = strtolower($this->flashPrefix);
        // Map flash key "informe" to item; also expose as auditoria for templates
        if ($item) {
            $item['programa_label'] = $this->getRelatedLabel('programa_auditoria', $item['programa'] ?? null);
            $vars[$key] = $item;
            $vars['auditoria'] = $item;
            $vars['hallazgos_relacionados'] = $this->fetchHallazgos((int)$item['id']);
            $vars['mejora_relacionadas'] = $this->fetchMejoras((int)$item['id']);
        } else {
            $vars['auditoria'] = null;
            $vars['hallazgos_relacionados'] = [];
            $vars['mejora_relacionadas'] = [];
        }

        return $vars;
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

    protected function getPostData(): array
    {
        return [
            'lugar_informe'           => trim($_POST['lugar_informe'] ?? ''),
            'fecha_informe'           => trim($_POST['fecha_informe'] ?? ''),
            'fecha_realiza'           => trim($_POST['fecha_realiza'] ?? ''),
            'conclusiones_informe'    => trim($_POST['conclusiones_informe'] ?? ''),
            'recomendaciones_informe' => trim($_POST['recomendaciones_informe'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        // Informe is edit-only; no nombre required. Soft validation only.
        return [];
    }

    protected function persist(array $data, $id)
    {
        if ($id <= 0) {
            return;
        }
        $db = $this->getDb();
        $db->consultaPreparada(
            "UPDATE {$this->table}
             SET lugar_informe = ?, fecha_informe = ?, fecha_realiza = ?,
                 conclusiones_informe = ?, recomendaciones_informe = ?
             WHERE id = ?",
            [
                $data['lugar_informe'],
                $data['fecha_informe'] ?: null,
                $data['fecha_realiza'] ?: null,
                $data['conclusiones_informe'],
                $data['recomendaciones_informe'],
                $id,
            ]
        );
        $db->desconexion();
    }

    public function ShowPage($id = null)
    {
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
            $_SESSION[$this->flashPrefix . '_flash_error'] = 'Auditoría no encontrada.';
            header('Location: ' . $this->listRoute);
            exit;
        }
        return parent::ShowPage($id);
    }

    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . $this->listRoute);
            exit;
        }

        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        }
        $id = (int)$id;
        if ($id <= 0 || !$this->loadItem($id)) {
            $_SESSION[$this->flashPrefix . '_flash_error'] = 'Auditoría no encontrada.';
            header('Location: ' . $this->listRoute);
            exit;
        }

        $data = $this->getPostData();
        $this->persist($data, $id);

        $_SESSION[$this->flashPrefix . '_flash_success'] = 'Informe actualizado correctamente.';
        header('Location: /admin/auditorias/informes/ver/' . $id);
        exit;
    }
}
