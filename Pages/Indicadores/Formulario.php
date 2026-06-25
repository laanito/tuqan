<?php
namespace Tuqan\Pages\Indicadores;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'indicadores';
    protected string $title        = 'Indicadores';
    protected string $templateDir  = 'indicadores';
    protected string $flashPrefix  = 'indicador';
    protected string $listRoute    = '/admin/indicadores';

    protected function getSelectForForm(): string
    {
        return "SELECT id, definicion, valor_inicial, tecnica, variables_control, activo, frecuencia_seg, frecuencia_ana, genera_objetivo, nombre, responsable_analisis, responsable_seguimiento, valor_tolerable, valor_tolerable2, valor_objetivo FROM {$this->table} WHERE id = ?";
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
            'definicion'              => $row[1],
            'valor_inicial'           => $row[2],
            'tecnica'                 => $row[3],
            'variables_control'       => $row[4],
            'activo'                  => $row[5],
            'frecuencia_seg'          => $row[6],
            'frecuencia_ana'          => $row[7],
            'genera_objetivo'         => $row[8],
            'nombre'                  => $row[9],
            'responsable_analisis'    => $row[10],
            'responsable_seguimiento' => $row[11],
            'valor_tolerable'         => $row[12],
            'valor_tolerable2'        => $row[13],
            'valor_objetivo'          => $row[14],
        ];
    }

    protected function getPostData(): array
    {
        return [
            'definicion'              => trim($_POST['definicion'] ?? ''),
            'valor_inicial'           => isset($_POST['valor_inicial']) && $_POST['valor_inicial'] !== '' ? (int)$_POST['valor_inicial'] : null,
            'tecnica'                 => trim($_POST['tecnica'] ?? ''),
            'variables_control'       => trim($_POST['variables_control'] ?? ''),
            'activo'                  => !empty($_POST['activo']) ? 1 : 0,
            'frecuencia_seg'          => isset($_POST['frecuencia_seg']) && $_POST['frecuencia_seg'] !== '' ? (int)$_POST['frecuencia_seg'] : null,
            'frecuencia_ana'          => isset($_POST['frecuencia_ana']) && $_POST['frecuencia_ana'] !== '' ? (int)$_POST['frecuencia_ana'] : null,
            'genera_objetivo'         => !empty($_POST['genera_objetivo']) ? 1 : 0,
            'nombre'                  => trim($_POST['nombre'] ?? ''),
            'responsable_analisis'    => trim($_POST['responsable_analisis'] ?? ''),
            'responsable_seguimiento' => trim($_POST['responsable_seguimiento'] ?? ''),
            'valor_tolerable'         => isset($_POST['valor_tolerable']) && $_POST['valor_tolerable'] !== '' ? (float)$_POST['valor_tolerable'] : null,
            'valor_tolerable2'        => isset($_POST['valor_tolerable2']) && $_POST['valor_tolerable2'] !== '' ? (int)$_POST['valor_tolerable2'] : null,
            'valor_objetivo'          => isset($_POST['valor_objetivo']) && $_POST['valor_objetivo'] !== '' ? (int)$_POST['valor_objetivo'] : null,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del indicador es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['definicion'],
            $data['valor_inicial'],
            $data['tecnica'],
            $data['variables_control'],
            $data['activo'],
            $data['frecuencia_seg'],
            $data['frecuencia_ana'],
            $data['genera_objetivo'],
            $data['nombre'],
            $data['responsable_analisis'],
            $data['responsable_seguimiento'],
            $data['valor_tolerable'],
            $data['valor_tolerable2'],
            $data['valor_objetivo'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET definicion = ?, valor_inicial = ?, tecnica = ?, variables_control = ?, activo = ?, frecuencia_seg = ?, frecuencia_ana = ?, genera_objetivo = ?, nombre = ?, responsable_analisis = ?, responsable_seguimiento = ?, valor_tolerable = ?, valor_tolerable2 = ?, valor_objetivo = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (definicion, valor_inicial, tecnica, variables_control, activo, frecuencia_seg, frecuencia_ana, genera_objetivo, nombre, responsable_analisis, responsable_seguimiento, valor_tolerable, valor_tolerable2, valor_objetivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
