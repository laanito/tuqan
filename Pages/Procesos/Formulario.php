<?php
namespace Tuqan\Pages\Procesos;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table       = 'procesos';
    protected string $title       = 'Procesos';
    protected string $templateDir = 'procesos';
    protected string $flashPrefix = 'proceso';
    protected string $listRoute   = '/admin/procesos';

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, codigo, revision, padre, activo FROM {$this->table} WHERE id = ?";
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
            'id'       => $row[0],
            'nombre'   => $row[1],
            'codigo'   => $row[2],
            'revision' => $row[3],
            'padre'    => $row[4],
            'activo'   => $row[5],
        ];
    }

    protected function getPostData(): array
    {
        return [
            'nombre'   => trim($_POST['nombre'] ?? ''),
            'codigo'   => trim($_POST['codigo'] ?? ''),
            'revision' => trim($_POST['revision'] ?? ''),
            'padre'    => isset($_POST['padre']) && $_POST['padre'] !== '' ? (int)$_POST['padre'] : 0,
            'activo'   => !empty($_POST['activo']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del proceso es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['nombre'],
            $data['codigo'],
            $data['revision'],
            $data['padre'],
            $data['activo'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, codigo = ?, revision = ?, padre = ?, activo = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, codigo, revision, padre, activo) VALUES (?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
