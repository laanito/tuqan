<?php
namespace Tuqan\Pages\Equipos\Revisiones;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'mantenimientos';
    protected string $title        = 'Revisión de Equipo';
    protected string $templateDir  = 'equipos/revisiones';
    protected string $flashPrefix  = 'revision';
    protected string $listRoute    = '/admin/equipos/revisiones';

    protected function getSelectForForm(): string
    {
        return "SELECT id, equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos FROM {$this->table} WHERE id = ?";
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
            'equipo'         => $row[1] ?? null,
            'tipo'           => $row[2] ?? 'revision',
            'fecha_prevista' => $row[3] ?? null,
            'fecha_realiza'  => $row[4] ?? null,
            'comentarios'    => $row[5] ?? '',
            'motivos'        => $row[6] ?? '',
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        if ($item === null) {
            $prefill = (isset($_GET['equipo']) && $_GET['equipo'] !== '') ? (int)$_GET['equipo'] : null;
            if ($prefill) {
                $item = [
                    'id' => null,
                    'equipo' => $prefill,
                    'tipo' => 'revision',
                    'fecha_realiza' => date('Y-m-d'),
                ];
            }
        }

        $vars = parent::buildFormVariables($item);
        if ($item && empty($item['id'])) {
            $vars['isEdit'] = false;
            $vars['pageTitle'] = "Nueva {$this->title}";
        }

        $vars['equipo_options'] = $this->getEquipoOptions();
        $vars['tipo_options'] = Listado::tipoOptions();

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $r = $vars[$key];
            $r['equipo_label'] = $this->getEquipoLabel($r['equipo'] ?? null);
            $vars[$key] = $r;
        }
        return $vars;
    }

    protected function getEquipoOptions(): array
    {
        $db = $this->getDb();
        $db->consulta('SELECT id, numero, descripcion FROM equipos ORDER BY numero');
        $opts = [];
        while ($row = $db->coger_Fila()) {
            $opts[] = ['id' => $row[0], 'nombre' => $row[1] . ' — ' . $row[2]];
        }
        $db->desconexion();
        return $opts;
    }

    protected function getEquipoLabel($id): ?string
    {
        if (!$id) {
            return null;
        }
        $db = $this->getDb();
        $db->consultaPreparada('SELECT numero, descripcion FROM equipos WHERE id = ?', [(int)$id]);
        $row = $db->coger_Fila();
        $db->desconexion();
        if (!$row) {
            return null;
        }
        return $row[0] . ' — ' . $row[1];
    }

    protected function getPostData(): array
    {
        return [
            'equipo'         => isset($_POST['equipo']) && $_POST['equipo'] !== '' ? (int)$_POST['equipo'] : null,
            'tipo'           => trim($_POST['tipo'] ?? 'revision'),
            'fecha_prevista' => trim($_POST['fecha_prevista'] ?? ''),
            'fecha_realiza'  => trim($_POST['fecha_realiza'] ?? ''),
            'comentarios'    => trim($_POST['comentarios'] ?? ''),
            'motivos'        => trim($_POST['motivos'] ?? ''),
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (empty($data['equipo'])) {
            $errors[] = 'El equipo es obligatorio.';
        }
        if (($data['fecha_realiza'] ?? '') === '') {
            $errors[] = 'La fecha de realización es obligatoria.';
        }
        if (($data['comentarios'] ?? '') === '') {
            $errors[] = 'Los comentarios son obligatorios.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['equipo'],
            $data['tipo'] ?: 'revision',
            $data['fecha_prevista'] ?: null,
            $data['fecha_realiza'] ?: date('Y-m-d'),
            $data['comentarios'] !== '' ? $data['comentarios'] : '-',
            $data['motivos'] !== '' ? $data['motivos'] : '-',
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET equipo = ?, tipo = ?, fecha_prevista = ?, fecha_realiza = ?, comentarios = ?, motivos = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (equipo, tipo, fecha_prevista, fecha_realiza, comentarios, motivos) VALUES (?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
