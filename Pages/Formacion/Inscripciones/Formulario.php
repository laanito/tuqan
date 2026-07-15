<?php
namespace Tuqan\Pages\Formacion\Inscripciones;
use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table        = 'alumnos';
    protected string $title        = 'Inscripción a Curso';
    protected string $templateDir  = 'formacion/inscripciones';
    protected string $flashPrefix  = 'inscripcion';
    protected string $listRoute    = '/admin/formacion/inscripciones';

    protected function getSelectForForm(): string
    {
        return "SELECT id, usuario, curso, inscrito, verificado FROM {$this->table} WHERE id = ?";
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
            'id'        => $row[0],
            'usuario'   => $row[1],
            'curso'     => $row[2],
            'inscrito'  => $row[3],
            'verificado'=> $row[4],
        ];
    }

    protected function buildFormVariables(?array $item): array
    {
        // 9.30: prefill curso from ?curso= when creating
        if ($item === null) {
            $prefillCurso = (isset($_GET['curso']) && $_GET['curso'] !== '') ? (int)$_GET['curso'] : null;
            if ($prefillCurso) {
                $item = [
                    'id' => null,
                    'curso' => $prefillCurso,
                    'inscrito' => 1,
                    'verificado' => 0,
                ];
            }
        }

        $vars = parent::buildFormVariables($item);
        if ($item && empty($item['id'])) {
            $vars['isEdit'] = false;
            $vars['pageTitle'] = "Nuevo {$this->title}";
        }

        $vars['usuario_options'] = $this->getRelatedOptions('usuarios', 'nombre');
        $vars['curso_options'] = $this->getRelatedOptions('cursos', 'nombre');

        $key = strtolower($this->flashPrefix);
        if (!empty($vars[$key])) {
            $i = $vars[$key];
            $i['usuario_label'] = $this->getRelatedLabel('usuarios', $i['usuario'] ?? null);
            $i['curso_label'] = $this->getRelatedLabel('cursos', $i['curso'] ?? null);
            $vars[$key] = $i;
        }
        return $vars;
    }

    protected function getPostData(): array
    {
        return [
            'usuario'   => isset($_POST['usuario']) && $_POST['usuario'] !== '' ? (int)$_POST['usuario'] : null,
            'curso'     => isset($_POST['curso']) && $_POST['curso'] !== '' ? (int)$_POST['curso'] : null,
            'inscrito'  => !empty($_POST['inscrito']) ? 1 : 0,
            'verificado'=> !empty($_POST['verificado']) ? 1 : 0,
        ];
    }

    protected function validate(array $data): array
    {
        $errors = [];
        if (($data['curso'] ?? 0) <= 0) {
            $errors[] = 'El curso es obligatorio.';
        }
        return $errors;
    }

    protected function persist(array $data, $id)
    {
        $db = $this->getDb();
        $params = [
            $data['usuario'],
            $data['curso'],
            $data['inscrito'],
            $data['verificado'],
        ];
        if ($id > 0) {
            $params[] = $id;
            $db->consultaPreparada(
                "UPDATE {$this->table} SET usuario = ?, curso = ?, inscrito = ?, verificado = ? WHERE id = ?",
                $params
            );
        } else {
            $db->consultaPreparada(
                "INSERT INTO {$this->table} (usuario, curso, inscrito, verificado) VALUES (?, ?, ?, ?)",
                $params
            );
        }
        $db->desconexion();
    }
}
