<?php
namespace Tuqan\Pages\Formacion\Cursos;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'cursos';
    protected string $title        = 'Curso de Formación';
    protected string $templateDir  = 'formacion/cursos';
    protected string $flashPrefix  = 'curso';
    protected string $listRoute    = '/admin/formacion/cursos';

    protected function getSelectForForm(): string
    {
        return "SELECT id, tipo, objetivos, contenido, num_horas, material_necesario, material_suministrado, activo, plan, fecha_prevista, lugar, fecha_realizacion, estado, nombre, responsable, observaciones, calidad, medioambiente FROM {$this->table} WHERE id = ?";
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
            'id'                  => $row[0],
            'tipo'                => $row[1],
            'objetivos'           => $row[2] ?? null,
            'contenido'           => $row[3] ?? null,
            'num_horas'           => $row[4],
            'material_necesario'  => $row[5] ?? null,
            'material_suministrado' => $row[6] ?? null,
            'activo'              => $row[7],
            'plan'                => $row[8],
            'fecha_prevista'      => $row[9],
            'lugar'               => $row[10] ?? null,
            'fecha_realizacion'   => $row[11] ?? null,
            'estado'              => $row[12] ?? 0,
            'nombre'              => $row[13],
            'responsable'         => $row[14],
            'observaciones'       => $row[15] ?? null,
            'calidad'             => $row[16],
            'medioambiente'       => $row[17],
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        $vars['plan_options'] = $this->getRelatedOptions('plan_formacion', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $c = $vars[$key];
            $c['plan_label'] = $this->getRelatedLabel('plan_formacion', $c['plan'] ?? null);
            $vars[$key] = $c;
        }

        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'tipo'                => isset($_POST['tipo']) && $_POST['tipo'] !== '' ? (int)$_POST['tipo'] : null,
            'objetivos'           => trim($_POST['objetivos'] ?? ''),
            'contenido'           => trim($_POST['contenido'] ?? ''),
            'num_horas'           => isset($_POST['num_horas']) && $_POST['num_horas'] !== '' ? (int)$_POST['num_horas'] : null,
            'material_necesario'  => trim($_POST['material_necesario'] ?? ''),
            'material_suministrado' => trim($_POST['material_suministrado'] ?? ''),
            'activo'              => !empty($_POST['activo']) ? 1 : 0,
            'plan'                => isset($_POST['plan']) && $_POST['plan'] !== '' ? (int)$_POST['plan'] : null,
            'fecha_prevista'      => trim($_POST['fecha_prevista'] ?? ''),
            'lugar'               => trim($_POST['lugar'] ?? ''),
            'fecha_realizacion'   => trim($_POST['fecha_realizacion'] ?? ''),
            'estado'              => isset($_POST['estado']) ? (int)$_POST['estado'] : 0,
            'nombre'              => trim($_POST['nombre'] ?? ''),
            'responsable'         => isset($_POST['responsable']) && $_POST['responsable'] !== '' ? (int)$_POST['responsable'] : null,
            'observaciones'       => trim($_POST['observaciones'] ?? ''),
            'calidad'             => !empty($_POST['calidad']) ? 1 : 0,
            'medioambiente'       => !empty($_POST['medioambiente']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del curso es obligatorio.';
        }
        if (($data['plan'] ?? 0) <= 0) {
            $errors[] = 'El plan de formación es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['tipo'],
            $data['objetivos'],
            $data['contenido'],
            $data['num_horas'],
            $data['material_necesario'],
            $data['material_suministrado'],
            $data['activo'],
            $data['plan'],
            $data['fecha_prevista'] ?: null,
            $data['lugar'],
            $data['fecha_realizacion'] ?: null,
            $data['estado'],
            $data['nombre'],
            $data['responsable'],
            $data['observaciones'],
            $data['calidad'],
            $data['medioambiente'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET tipo = ?, objetivos = ?, contenido = ?, num_horas = ?, material_necesario = ?, material_suministrado = ?, activo = ?, plan = ?, fecha_prevista = ?, lugar = ?, fecha_realizacion = ?, estado = ?, nombre = ?, responsable = ?, observaciones = ?, calidad = ?, medioambiente = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (tipo, objetivos, contenido, num_horas, material_necesario, material_suministrado, activo, plan, fecha_prevista, lugar, fecha_realizacion, estado, nombre, responsable, observaciones, calidad, medioambiente) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
