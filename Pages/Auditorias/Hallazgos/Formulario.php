<?php
namespace Tuqan\Pages\Auditorias\Hallazgos;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'hallazgos_auditoria';
    protected string $title        = 'Hallazgo de Auditoría';
    protected string $templateDir  = 'auditorias/hallazgos';
    protected string $flashPrefix  = 'hallazgo';
    protected string $listRoute    = '/admin/auditorias/hallazgos';

    protected function getSelectForForm(): string
    {
        return "SELECT id, auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo, observaciones FROM {$this->table} WHERE id = ?";
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
            'id'            => $row[0],
            'auditoria'     => $row[1] ?? null,
            'fecha'         => $row[2] ?? null,
            'descripcion'   => $row[3],
            'tipo'          => $row[4] ?? 'observacion',
            'gravedad'      => $row[5] ?? 'menor',
            'cerrado'       => $row[6],
            'accion_mejora' => $row[7] ?? null,
            'activo'        => $row[8],
            'observaciones' => $row[9] ?? null,
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        if ($item === null) {
            $prefill = (isset($_GET['auditoria']) && $_GET['auditoria'] !== '') ? (int)$_GET['auditoria'] : null;
            if ($prefill) {
                $item = [
                    'id' => null,
                    'auditoria' => $prefill,
                    'fecha' => date('Y-m-d'),
                    'tipo' => 'observacion',
                    'gravedad' => 'menor',
                    'cerrado' => 0,
                    'activo' => 1,
                ];
            }
        }

        $vars = parent::buildFormVariables($item);
        if ($item && empty($item['id'])) {
            $vars['isEdit'] = false;
            $vars['pageTitle'] = "Nuevo {$this->title}";
        }

        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');
        $vars['tipo_options'] = Listado::tipoOptions();
        $vars['gravedad_options'] = Listado::gravedadOptions();
        $vars['mejora_options'] = $this->getMejoraOptions();

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $h = $vars[$key];
            $h['auditoria_label'] = $this->getRelatedLabel('auditorias', $h['auditoria'] ?? null);
            $vars[$key] = $h;
        }
        return $vars;
    }

    protected function getMejoraOptions(): array
    {
        $db = $this->getDb();
        $db->consulta('SELECT id, descripcion FROM acciones_mejora ORDER BY id');
        $opts = [];
        while ($row = $db->coger_Fila()) {
            $desc = $row[1] ?? '';
            if (strlen($desc) > 60) {
                $desc = substr($desc, 0, 57) . '…';
            }
            $opts[] = ['id' => $row[0], 'nombre' => '#' . $row[0] . ' — ' . $desc];
        }
        $db->desconexion();
        return $opts;
    }

    protected function getPostData(): array
    {
        return [
            'auditoria'     => isset($_POST['auditoria']) && $_POST['auditoria'] !== '' ? (int)$_POST['auditoria'] : null,
            'fecha'         => trim($_POST['fecha'] ?? ''),
            'descripcion'   => trim($_POST['descripcion'] ?? ''),
            'tipo'          => trim($_POST['tipo'] ?? 'observacion'),
            'gravedad'      => trim($_POST['gravedad'] ?? 'menor'),
            'cerrado'       => !empty($_POST['cerrado']) ? 1 : 0,
            'accion_mejora' => isset($_POST['accion_mejora']) && $_POST['accion_mejora'] !== '' ? (int)$_POST['accion_mejora'] : null,
            'activo'        => !empty($_POST['activo']) ? 1 : 0,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['descripcion'] ?? '') === '') {
            $errors[] = 'La descripción del hallazgo es obligatoria.';
        }
        if (empty($data['auditoria'])) {
            $errors[] = 'La auditoría es obligatoria.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['auditoria'],
            $data['fecha'] ?: null,
            $data['descripcion'],
            $data['tipo'] ?: 'observacion',
            $data['gravedad'] ?: 'menor',
            $data['cerrado'],
            $data['accion_mejora'],
            $data['activo'],
            $data['observaciones'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET auditoria = ?, fecha = ?, descripcion = ?, tipo = ?, gravedad = ?, cerrado = ?, accion_mejora = ?, activo = ?, observaciones = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (auditoria, fecha, descripcion, tipo, gravedad, cerrado, accion_mejora, activo, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
