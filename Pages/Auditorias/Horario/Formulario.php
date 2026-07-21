<?php
namespace Tuqan\Pages\Auditorias\Horario;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'horario_auditoria';
    protected string $title        = 'Franja de Horario';
    protected string $templateDir  = 'auditorias/horario';
    protected string $flashPrefix  = 'horario';
    protected string $listRoute    = '/admin/auditorias/horario';

    protected function getSelectForForm(): string
    {
        return "SELECT id, auditoria, horainicio, horafin, requisito, auditor, area FROM {$this->table} WHERE id = ?";
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
            'id'         => $row[0],
            'auditoria'  => $row[1] ?? null,
            'horainicio' => self::toDatetimeLocal($row[2] ?? null),
            'horafin'    => self::toDatetimeLocal($row[3] ?? null),
            'requisito'  => $row[4] ?? null,
            'auditor'    => $row[5] ?? null,
            'area'       => $row[6] ?? null,
        ];
    }

    /** HTML datetime-local value */
    public static function toDatetimeLocal($ts): string
    {
        if ($ts === null || $ts === '') {
            return '';
        }
        $t = strtotime((string)$ts);
        if ($t === false) {
            return '';
        }
        return date('Y-m-d\TH:i', $t);
    }

    public static function fromDatetimeLocal(string $val): ?string
    {
        $val = trim($val);
        if ($val === '') {
            return null;
        }
        // datetime-local: 2025-01-15T09:00
        $val = str_replace('T', ' ', $val);
        if (strlen($val) === 16) {
            $val .= ':00';
        }
        return $val;
    }

    protected function buildFormVariables(?array $item): array
    {
        if ($item === null) {
            $prefill = (isset($_GET['auditoria']) && $_GET['auditoria'] !== '') ? (int)$_GET['auditoria'] : null;
            if ($prefill) {
                $item = [
                    'id' => null,
                    'auditoria' => $prefill,
                    'horainicio' => date('Y-m-d') . 'T09:00',
                    'horafin' => date('Y-m-d') . 'T11:00',
                ];
            }
        }

        $vars = parent::buildFormVariables($item);
        if ($item && empty($item['id'])) {
            $vars['isEdit'] = false;
            $vars['pageTitle'] = "Nueva {$this->title}";
        }

        $vars['auditoria_options'] = $this->getRelatedOptions('auditorias', 'nombre');
        // areas table may be absent on minimal DB; optional free FK id only
        $vars['area_options'] = [];
        try {
            $vars['area_options'] = $this->getRelatedOptions('areas', 'nombre');
        } catch (\Throwable $e) {
            // ignore
        }

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $h = $vars[$key];
            $h['auditoria_label'] = $this->getRelatedLabel('auditorias', $h['auditoria'] ?? null);
            $vars[$key] = $h;
        }
        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'auditoria'  => isset($_POST['auditoria']) && $_POST['auditoria'] !== '' ? (int)$_POST['auditoria'] : null,
            'horainicio' => self::fromDatetimeLocal($_POST['horainicio'] ?? ''),
            'horafin'    => self::fromDatetimeLocal($_POST['horafin'] ?? ''),
            'requisito'  => trim($_POST['requisito'] ?? ''),
            'auditor'    => trim($_POST['auditor'] ?? ''),
            'area'       => isset($_POST['area']) && $_POST['area'] !== '' ? (int)$_POST['area'] : null,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (empty($data['auditoria'])) {
            $errors[] = 'La auditoría es obligatoria.';
        }
        if (empty($data['horainicio'])) {
            $errors[] = 'La hora de inicio es obligatoria.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['auditoria'],
            $data['horainicio'],
            $data['horafin'],
            $data['requisito'] !== '' ? $data['requisito'] : null,
            $data['auditor'] !== '' ? $data['auditor'] : null,
            $data['area'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET auditoria = ?, horainicio = ?, horafin = ?, requisito = ?, auditor = ?, area = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (auditoria, horainicio, horafin, requisito, auditor, area) VALUES (?, ?, ?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
