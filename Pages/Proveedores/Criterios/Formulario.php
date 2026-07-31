<?php
namespace Tuqan\Pages\Proveedores\Criterios;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'criterios_homologacion';
    protected string $title        = 'Criterio de Homologación';
    protected string $templateDir  = 'proveedores/criterios';
    protected string $flashPrefix  = 'criterio';
    protected string $listRoute    = '/admin/proveedores/criterios';

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, valor, activo FROM {$this->table} WHERE id = ?";
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
            'id'     => $row[0],
            'nombre' => $row[1],
            'valor'  => $row[2] ?? 0,
            'activo' => $row[3],
        ];
    }

    protected function getPostData(): array
    {
        return [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'valor'  => isset($_POST['valor']) ? (int)$_POST['valor'] : 0,
            'activo' => !empty($_POST['activo']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del criterio es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        if ($id > 0) {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, valor = ?, activo = ? WHERE id = ?",
                [$data['nombre'], $data['valor'], $data['activo'], $id]
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, valor, activo) VALUES (?, ?, ?)",
                [$data['nombre'], $data['valor'], $data['activo']]
            );
        }
        $db->desconexion();
    }
}
