<?php

namespace Tuqan\Pages\TipoCursos;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tipocursos';
    protected $title       = 'Tipo Cursos';
    protected $templateDir = 'tipocursos';
    protected $flashPrefix = 'tipocurso';
}