<?php
namespace Tuqan\Pages\Proveedores\Productos;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'productos';
    protected string $title        = 'Producto';
    protected string $templateDir  = 'proveedores/productos';
    protected string $flashPrefix  = 'producto';
    protected string $listRoute    = '/admin/proveedores/productos';

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, proveedor, valor, homologado, activo, fecha_revision FROM {$this->table} WHERE id = ?";
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
            'id'             => $row[0],
            'nombre'         => $row[1],
            'proveedor'      => $row[2] ?? null,
            'valor'          => $row[3] ?? 0,
            'homologado'     => $row[4],
            'activo'         => $row[5],
            'fecha_revision' => $row[6] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        if ($item === null) {
            $proveedor = (isset($_GET['proveedor']) && $_GET['proveedor'] !== '') ? (int)$_GET['proveedor'] : null;
            $item = [
                'id'             => null,
                'nombre'         => '',
                'proveedor'      => $proveedor,
                'valor'          => 50,
                'homologado'     => false,
                'activo'         => true,
                'fecha_revision' => null,
            ];
            // Keep isEdit false: pass null to parent then override key
            $vars = parent::buildFormVariables(null);
            $vars[strtolower($this->flashPrefix)] = $item;
            $vars['isEdit'] = false;
        } else {
            $vars = parent::buildFormVariables($item);
        }
        $vars['proveedor_options'] = $this->getRelatedOptions('proveedores', 'nombre');
        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $p = $vars[$key];
            $p['proveedor_label'] = $this->getRelatedLabel('proveedores', $p['proveedor'] ?? null);
            $vars[$key] = $p;
            if (!empty($p['proveedor'])) {
                $vars['list_return'] = $this->listRoute . '?proveedor=' . (int)$p['proveedor'];
            } else {
                $vars['list_return'] = $this->listRoute;
            }
        } else {
            $vars['list_return'] = $this->listRoute;
        }
        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'nombre'         => trim($_POST['nombre'] ?? ''),
            'proveedor'      => isset($_POST['proveedor']) && $_POST['proveedor'] !== '' ? (int)$_POST['proveedor'] : null,
            'valor'          => isset($_POST['valor']) ? (int)$_POST['valor'] : 0,
            'homologado'     => !empty($_POST['homologado']) ? 1 : 0,
            'activo'         => !empty($_POST['activo']) ? 1 : 0,
            'fecha_revision' => trim($_POST['fecha_revision'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del producto es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['nombre'],
            $data['proveedor'],
            $data['valor'],
            $data['homologado'],
            $data['activo'],
            $data['fecha_revision'] ?: null,
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, proveedor = ?, valor = ?, homologado = ?, activo = ?, fecha_revision = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, proveedor, valor, homologado, activo, fecha_revision) VALUES (?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }

    public function Procesar($id = null)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . $this->listRoute);
            exit;
        }
        \Tuqan\Classes\Config::initialize();
        if ($id === null) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        }
        $id = (int)$id;
        $data = $this->getPostData();
        $errors = $this->validate($data);
        if (!empty($errors)) {
            $_SESSION[$this->flashPrefix . '_form_error'] = implode(' ', $errors);
            $target = $id > 0 ? "{$this->listRoute}/editar/$id" : "{$this->listRoute}/nuevo";
            if (!empty($data['proveedor'])) {
                $target .= (strpos($target, '?') === false ? '?' : '&') . 'proveedor=' . (int)$data['proveedor'];
            }
            header("Location: $target");
            exit;
        }
        $this->persist($data, $id);
        $_SESSION[$this->flashPrefix . '_flash_success'] = $this->getSuccessMessage($id > 0);
        $loc = $this->listRoute;
        if (!empty($data['proveedor'])) {
            $loc .= '?proveedor=' . (int)$data['proveedor'];
        }
        header("Location: $loc");
        exit;
    }
}
