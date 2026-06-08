<?php

namespace Tuqan\Pages\TipoCursos;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tipocursos';
    protected string $title       = 'Tipo Cursos';
    protected string $templateDir = 'tipocursos';
    protected string $flashPrefix = 'tipocurso';
    protected string $listRoute   = '/admin/tipo-cursos';
}