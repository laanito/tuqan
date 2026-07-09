<?php
namespace Tuqan\Pages\Documentacion;
use Tuqan\Pages\Catalog\CatalogFormulario;
class Formulario extends CatalogFormulario
{
    protected string $table        = 'documentos';
    protected string $title        = 'Documentación';
    protected string $templateDir  = 'documentacion';
    protected string $flashPrefix  = 'documento';
    protected string $listRoute    = '/admin/documentacion';

    protected function getSelectSql(): string
    {
        return "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas FROM {$this->table} ORDER BY id";
    }

    protected function getSelectForForm(): string
    {
        return "SELECT id, nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas FROM {$this->table} WHERE id = ?";
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
            'nombre'            => $row[1],
            'codigo'            => $row[2] ?? null,
            'estado'            => $row[3] ?? null,
            'revision'          => $row[4] ?? null,
            'activo'            => $row[5],
            'calidad'           => $row[6],
            'medioambiente'     => $row[7],
            'tipo_documento'    => $row[8] ?? null,
            'area'              => $row[9] ?? null,
            'perfil_ver'        => $row[10],
            'perfil_nueva'      => $row[11],
            'perfil_modificar'  => $row[12],
            'perfil_revisar'    => $row[13],
            'perfil_aprobar'    => $row[14],
            'perfil_historico'  => $row[15],
            'perfil_tareas'     => $row[16],
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        $vars = parent::buildFormVariables($item);

        // 9.23: perfiles for editor/approval
        // tipo and area relations if tables exist
        $vars['tipo_documento_options'] = $this->getRelatedOptions('tipodocumento', 'nombre');
        $vars['area_options'] = $this->getRelatedOptions('areas', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $d = $vars[$key];
            $d['tipo_documento_label'] = $this->getRelatedLabel('tipodocumento', $d['tipo_documento'] ?? null);
            $d['area_label'] = $this->getRelatedLabel('areas', $d['area'] ?? null);
            $vars[$key] = $d;
        }
        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'nombre'           => trim($_POST['nombre'] ?? ''),
            'codigo'           => trim($_POST['codigo'] ?? ''),
            'estado'           => isset($_POST['estado']) && $_POST['estado'] !== '' ? (int)$_POST['estado'] : null,
            'revision'         => trim($_POST['revision'] ?? ''),
            'activo'           => !empty($_POST['activo']) ? 1 : 0,
            'calidad'          => !empty($_POST['calidad']) ? 1 : 0,
            'medioambiente'    => !empty($_POST['medioambiente']) ? 1 : 0,
            'tipo_documento'   => isset($_POST['tipo_documento']) && $_POST['tipo_documento'] !== '' ? (int)$_POST['tipo_documento'] : null,
            'area'             => isset($_POST['area']) && $_POST['area'] !== '' ? (int)$_POST['area'] : null,
            'perfil_ver'       => !empty($_POST['perfil_ver']) ? 1 : 0,
            'perfil_nueva'     => !empty($_POST['perfil_nueva']) ? 1 : 0,
            'perfil_modificar' => !empty($_POST['perfil_modificar']) ? 1 : 0,
            'perfil_revisar'   => !empty($_POST['perfil_revisar']) ? 1 : 0,
            'perfil_aprobar'   => !empty($_POST['perfil_aprobar']) ? 1 : 0,
            'perfil_historico' => !empty($_POST['perfil_historico']) ? 1 : 0,
            'perfil_tareas'    => !empty($_POST['perfil_tareas']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['nombre'] ?? '') === '') {
            $errors[] = 'El nombre del documento es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['nombre'],
            $data['codigo'],
            $data['estado'],
            $data['revision'],
            $data['activo'],
            $data['calidad'],
            $data['medioambiente'],
            $data['tipo_documento'],
            $data['area'],
            $data['perfil_ver'],
            $data['perfil_nueva'],
            $data['perfil_modificar'],
            $data['perfil_revisar'],
            $data['perfil_aprobar'],
            $data['perfil_historico'],
            $data['perfil_tareas'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET nombre = ?, codigo = ?, estado = ?, revision = ?, activo = ?, calidad = ?, medioambiente = ?, tipo_documento = ?, area = ?, perfil_ver = ?, perfil_nueva = ?, perfil_modificar = ?, perfil_revisar = ?, perfil_aprobar = ?, perfil_historico = ?, perfil_tareas = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (nombre, codigo, estado, revision, activo, calidad, medioambiente, tipo_documento, area, perfil_ver, perfil_nueva, perfil_modificar, perfil_revisar, perfil_aprobar, perfil_historico, perfil_tareas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
