<?php
namespace Tuqan\Pages\Aspectos;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'aspectos';
    protected string $title        = 'Aspectos Ambientales';
    protected string $templateDir  = 'aspectos';
    protected string $flashPrefix  = 'aspectos';
    protected string $listRoute    = '/admin/aspectos';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones FROM {$this->table} ORDER BY id";
    }

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones FROM {$this->table} WHERE id = ?";
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
            'id'           => $row[0],
            'nombre'       => $row[1],
            'magnitud'     => $row[2],
            'gravedad'     => $row[3],
            'frecuencia'   => $row[4],
            'tipo_aspecto' => $row[5],
            'activo'       => $row[6],
            'impacto'      => $row[7],
            'probabilidad' => $row[8],
            'severidad'    => $row[9],
            'area'         => $row[10] ?? null,
            'observaciones'=> $row[11] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        // 9.18 relations polish: options for tipo_aspecto select + label
        $vars['tipo_aspecto_options'] = $this->getRelatedOptions('tipo_aspectos', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $a = $vars[$key];
            $a['tipo_aspecto_label'] = $this->getRelatedLabel('tipo_aspectos', $a['tipo_aspecto'] ?? null);
            $vars[$key] = $a;
        }

        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'nombre'       => trim($_POST['nombre'] ?? ''),
            'magnitud'     => isset($_POST['magnitud']) ? (int)$_POST['magnitud'] : 0,
            'gravedad'     => isset($_POST['gravedad']) ? (int)$_POST['gravedad'] : 0,
            'frecuencia'   => isset($_POST['frecuencia']) ? (int)$_POST['frecuencia'] : 0,
            'tipo_aspecto' => isset($_POST['tipo_aspecto']) ? (int)$_POST['tipo_aspecto'] : 0,
            'activo'       => !empty($_POST['activo']) ? 1 : 0,
            'impacto'      => isset($_POST['impacto']) ? (int)$_POST['impacto'] : 0,
            'probabilidad' => isset($_POST['probabilidad']) ? (int)$_POST['probabilidad'] : 0,
            'severidad'    => isset($_POST['severidad']) ? (int)$_POST['severidad'] : 0,
            'area'         => trim($_POST['area'] ?? ''),
            'observaciones'=> trim($_POST['observaciones'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del aspecto es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['nombre'],
            $data['magnitud'],
            $data['gravedad'],
            $data['frecuencia'],
            $data['tipo_aspecto'],
            $data['activo'],
            $data['impacto'],
            $data['probabilidad'],
            $data['severidad'],
            $data['area'],
            $data['observaciones'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, magnitud = ?, gravedad = ?, frecuencia = ?, tipo_aspecto = ?, activo = ?, impacto = ?, probabilidad = ?, severidad = ?, area = ?, observaciones = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, magnitud, gravedad, frecuencia, tipo_aspecto, activo, impacto, probabilidad, severidad, area, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
