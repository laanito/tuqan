<?php

namespace Tuqan\Pages\TipoCursos;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tipocursos';
    protected string $title       = 'Tipo Cursos';
    protected string $templateDir = 'tipocursos';
    protected string $flashPrefix = 'tipocurso';
}