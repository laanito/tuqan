<?php
namespace Tuqan\Pages\Auditorias\Ejecucion;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'auditorias';
    protected string $title        = 'Auditoría';
    protected string $templateDir  = 'auditorias/ejecucion';
    protected string $flashPrefix  = 'auditoria';
    protected string $listRoute    = '/admin/auditorias/ejecucion';

    protected function getSelectForForm(): string
    {
        return "SELECT id, programa, nombre, fecha, estado, descripcion, observaciones, activo, requisitos, alcance, interna, fecha_realiza, lugar_informe, fecha_informe FROM {$this->table} WHERE id = ?";
    }

    protected function loadItem($id): ?array
    {
        if ($id <= 0) return null;
        $db = $this->getDb();
        $db->consultaPreparada($this->getSelectForForm(), [$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) return null;

        return [
            'id'                => $row[0],
            'programa'          => $row[1],
            'nombre'            => $row[2],
            'fecha'             => $row[3],
            'estado'            => $row[4] ?? 0,
            'descripcion'       => $row[5] ?? null,
            'observaciones'     => $row[6] ?? null,
            'activo'            => $row[7],
            'requisitos'        => $row[8] ?? null,
            'alcance'           => $row[9] ?? null,
            'interna'           => $row[10],
            'fecha_realiza'     => $row[11] ?? null,
            'lugar_informe'     => $row[12] ?? null,
            'fecha_informe'     => $row[13] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        $vars['programa_options'] = $this->getRelatedOptions('programa_auditoria', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $a = $vars[$key];
            $a['programa_label'] = $this->getRelatedLabel('programa_auditoria', $a['programa'] ?? null);
            $vars[$key] = $a;
        }

        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'programa'      => isset($_POST['programa']) && $_POST['programa'] !== '' ? (int)$_POST['programa'] : null,
            'nombre'        => trim($_POST['nombre'] ?? ''),
            'fecha'         => trim($_POST['fecha'] ?? ''),
            'estado'        => isset($_POST['estado']) ? (int)$_POST['estado'] : 0,
            'descripcion'   => trim($_POST['descripcion'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'activo'        => !empty($_POST['activo']) ? 1 : 0,
            'requisitos'    => trim($_POST['requisitos'] ?? ''),
            'alcance'       => trim($_POST['alcance'] ?? ''),
            'interna'       => !empty($_POST['interna']) ? 1 : 0,
            'fecha_realiza' => trim($_POST['fecha_realiza'] ?? ''),
            'lugar_informe' => trim($_POST['lugar_informe'] ?? ''),
            'fecha_informe' => trim($_POST['fecha_informe'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre de la auditoría es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['programa'],
            $data['nombre'],
            $data['fecha'] ?: null,
            $data['estado'],
            $data['descripcion'],
            $data['observaciones'],
            $data['activo'],
            $data['requisitos'],
            $data['alcance'],
            $data['interna'],
            $data['fecha_realiza'] ?: null,
            $data['lugar_informe'],
            $data['fecha_informe'] ?: null,
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET programa = ?, nombre = ?, fecha = ?, estado = ?, descripcion = ?, observaciones = ?, activo = ?, requisitos = ?, alcance = ?, interna = ?, fecha_realiza = ?, lugar_informe = ?, fecha_informe = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (programa, nombre, fecha, estado, descripcion, observaciones, activo, requisitos, alcance, interna, fecha_realiza, lugar_informe, fecha_informe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
