<?php

namespace Tuqan\Pages\TiposAreas;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tiposareas';
    protected string $title       = 'Tipo Area';
    protected string $templateDir = 'tiposareas';
    protected string $flashPrefix = 'tiposarea';
    protected string $listRoute   = '/admin/tipos-areas';
}