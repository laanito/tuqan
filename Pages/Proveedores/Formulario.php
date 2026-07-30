<?php
namespace Tuqan\Pages\Proveedores;

use Tuqan\Classes\Config;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'proveedores';
    protected string $title        = 'Proveedores';
    protected string $templateDir  = 'proveedores';
    protected string $flashPrefix  = 'proveedor';
    protected string $listRoute    = '/admin/proveedores';

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, telefono, activo, direccion, web, cif,
                       fecha_homologacion, ultima_revision, fecha_deshomologacion
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
            'id'                    => $row[0],
            'nombre'                => $row[1],
            'telefono'              => $row[2] ?? null,
            'activo'                => $row[3],
            'direccion'             => $row[4] ?? null,
            'web'                   => $row[5] ?? null,
            'cif'                   => $row[6] ?? null,
            'fecha_homologacion'    => $row[7] ?? null,
            'ultima_revision'       => $row[8] ?? null,
            'fecha_deshomologacion' => $row[9] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);
        $key = strtolower($this->flashPrefix);
        $vars['productos_relacionados'] = [];
        $vars['flash_success'] = $_SESSION[$this->flashPrefix . '_flash_success'] ?? null;
        $vars['flash_error'] = $_SESSION[$this->flashPrefix . '_form_error'] ?? null;
        unset($_SESSION[$this->flashPrefix . '_flash_success'], $_SESSION[$this->flashPrefix . '_form_error']);

        if (!empty($vars[$key])) {
            $p = $vars[$key];
            $p['homologado'] = HomologacionHelper::isHomologado(
                $p['fecha_homologacion'] ?? null,
                $p['fecha_deshomologacion'] ?? null
            );
            $p['homologacion_label'] = HomologacionHelper::label(
                $p['fecha_homologacion'] ?? null,
                $p['fecha_deshomologacion'] ?? null
            );
            $p['homologacion_badge'] = HomologacionHelper::badgeClass(
                $p['fecha_homologacion'] ?? null,
                $p['fecha_deshomologacion'] ?? null
            );
            $vars[$key] = $p;
            if (!empty($p['id'])) {
                $vars['productos_relacionados'] = $this->fetchProductos((int)$p['id']);
            }
        }
        return $vars;
    }

    protected function fetchProductos(int $proveedorId): array
    {
        try {
            $db = $this->getDb();
            $db->consultaPreparada(
                'SELECT id, nombre, valor, homologado, activo FROM productos WHERE proveedor = ? ORDER BY id',
                [$proveedorId]
            );
            $items = [];
            while ($row = $db->coger_Fila()) {
                $items[] = [
                    'id'         => $row[0],
                    'nombre'     => $row[1],
                    'valor'      => $row[2],
                    'homologado' => $row[3],
                    'activo'     => $row[4],
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
            'nombre'                => trim($_POST['nombre'] ?? ''),
            'telefono'              => trim($_POST['telefono'] ?? ''),
            'activo'                => !empty($_POST['activo']) ? 1 : 0,
            'direccion'             => trim($_POST['direccion'] ?? ''),
            'web'                   => trim($_POST['web'] ?? ''),
            'cif'                   => trim($_POST['cif'] ?? ''),
            'fecha_homologacion'    => trim($_POST['fecha_homologacion'] ?? ''),
            'ultima_revision'       => trim($_POST['ultima_revision'] ?? ''),
            'fecha_deshomologacion' => trim($_POST['fecha_deshomologacion'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del proveedor es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['nombre'],
            $data['telefono'],
            $data['activo'],
            $data['direccion'],
            $data['web'],
            $data['cif'],
            $data['fecha_homologacion'] ?: null,
            $data['ultima_revision'] ?: null,
            $data['fecha_deshomologacion'] ?: null,
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, telefono = ?, activo = ?, direccion = ?, web = ?, cif = ?,
                 fecha_homologacion = ?, ultima_revision = ?, fecha_deshomologacion = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, telefono, activo, direccion, web, cif, fecha_homologacion, ultima_revision, fecha_deshomologacion)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }

    public function Homologar($id = null)
    {
        return $this->setHomologacionState($id, true);
    }

    public function Deshomologar($id = null)
    {
        return $this->setHomologacionState($id, false);
    }

    protected function setHomologacionState($id, bool $homologar)
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Location: ' . $this->listRoute);
            exit;
        }
        Config::initialize();
        if ($id === null) {
            $id = (int)($_POST['id'] ?? 0);
        }
        $id = (int)$id;
        if ($id <= 0) {
            header('Location: ' . $this->listRoute);
            exit;
        }
        $today = date('Y-m-d');
        $db = $this->getDb();
        if ($homologar) {
            $db->consultaPreparada(
                "UPDATE {$this->table}
                 SET fecha_homologacion = COALESCE(fecha_homologacion, ?),
                     ultima_revision = ?,
                     fecha_deshomologacion = NULL
                 WHERE id = ?",
                [$today, $today, $id]
            );
            $msg = 'Proveedor homologado.';
        } else {
            $db->consultaPreparada(
                "UPDATE {$this->table} SET fecha_deshomologacion = ?, ultima_revision = ? WHERE id = ?",
                [$today, $today, $id]
            );
            $msg = 'Proveedor deshomologado.';
        }
        $db->desconexion();
        $_SESSION[$this->flashPrefix . '_flash_success'] = $msg;
        header('Location: ' . $this->listRoute);
        exit;
    }
}
