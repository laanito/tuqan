<?php

namespace Tuqan\Pages\TipoCursos;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tipocursos';
    protected $title       = 'Tipo Cursos';
    protected $templateDir = 'tipocursos';
    protected $flashPrefix = 'tipocurso';
    protected $listRoute   = '/admin/tipo-cursos';
}