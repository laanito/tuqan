<?php
namespace Tuqan\Pages\Mejora;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'acciones_mejora';
    protected string $title        = 'Acciones de Mejora';
    protected string $templateDir  = 'mejora';
    protected string $flashPrefix  = 'mejora';
    protected string $listRoute    = '/admin/mejora';

    protected function getSelectSql(): string
    {
        return "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones FROM {$this->table} ORDER BY id";
    }

    protected function getSelectForForm(): string
    {
        return "SELECT id, tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones FROM {$this->table} WHERE id = ?";
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
            'id'                  => $row[0],
            'tipo'                => $row[1] ?? null,
            'cliente'             => $row[2] ?? null,
            'fecha'               => $row[3],
            'descripcion'         => $row[4],
            'analisis'            => $row[5] ?? null,
            'requiere_tratamiento'=> $row[6],
            'tratamiento'         => $row[7] ?? null,
            'accion_preventiva'   => $row[8] ?? null,
            'fecha_implantacion'  => $row[9] ?? null,
            'plazo'               => $row[10] ?? null,
            'coste'               => $row[11] ?? null,
            'cerrada'             => $row[12],
            'area'                => $row[13] ?? null,
            'observaciones'       => $row[14] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        // 9.17 relations polish: provide options for selects + attach labels to current item
        $vars['tipo_options'] = $this->getRelatedOptions('tipoaccionesmejora', 'nombre');
        $vars['cliente_options'] = $this->getRelatedOptions('clientes', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $m = $vars[$key];
            $m['tipo_label'] = $this->getRelatedLabel('tipoaccionesmejora', $m['tipo'] ?? null);
            $m['cliente_label'] = $this->getRelatedLabel('clientes', $m['cliente'] ?? null);
            $vars[$key] = $m;
        }

        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'tipo'                => isset($_POST['tipo']) && $_POST['tipo'] !== '' ? (int)$_POST['tipo'] : null,
            'cliente'             => isset($_POST['cliente']) && $_POST['cliente'] !== '' ? (int)$_POST['cliente'] : null,
            'fecha'               => trim($_POST['fecha'] ?? ''),
            'descripcion'         => trim($_POST['descripcion'] ?? ''),
            'analisis'            => trim($_POST['analisis'] ?? ''),
            'requiere_tratamiento'=> !empty($_POST['requiere_tratamiento']) ? 1 : 0,
            'tratamiento'         => trim($_POST['tratamiento'] ?? ''),
            'accion_preventiva'   => trim($_POST['accion_preventiva'] ?? ''),
            'fecha_implantacion'  => trim($_POST['fecha_implantacion'] ?? ''),
            'plazo'               => trim($_POST['plazo'] ?? ''),
            'coste'               => isset($_POST['coste']) && $_POST['coste'] !== '' ? (float)$_POST['coste'] : null,
            'cerrada'             => !empty($_POST['cerrada']) ? 1 : 0,
            'area'                => trim($_POST['area'] ?? ''),
            'observaciones'       => trim($_POST['observaciones'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['descripcion'] ?? '') === '') {
            $errors[] = 'La descripción de la acción es obligatoria.';
        }
        if (($data['fecha'] ?? '') === '') {
            $errors[] = 'La fecha es obligatoria.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();

        // Normalize empty date strings to NULL for the DB
        $fecha_imp = ($data['fecha_implantacion'] ?? '') !== '' ? $data['fecha_implantacion'] : null;
        $plazo_val = ($data['plazo'] ?? '') !== '' ? $data['plazo'] : null;

        $params = [
            $data['tipo'],
            $data['cliente'],
            $data['fecha'],
            $data['descripcion'],
            $data['analisis'],
            $data['requiere_tratamiento'],
            $data['tratamiento'],
            $data['accion_preventiva'],
            $fecha_imp,
            $plazo_val,
            $data['coste'],
            $data['cerrada'],
            $data['area'],
            $data['observaciones'],
        ];

        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET tipo = ?, cliente = ?, fecha = ?, descripcion = ?, analisis = ?, requiere_tratamiento = ?, tratamiento = ?, accion_preventiva = ?, fecha_implantacion = ?, plazo = ?, coste = ?, cerrada = ?, area = ?, observaciones = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (tipo, cliente, fecha, descripcion, analisis, requiere_tratamiento, tratamiento, accion_preventiva, fecha_implantacion, plazo, coste, cerrada, area, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
