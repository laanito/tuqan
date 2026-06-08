<?php

namespace Tuqan\Pages\TiposAreas;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tiposareas';
    protected $title       = 'Tipo Area';
    protected $templateDir = 'tiposareas';
    protected $flashPrefix = 'tiposarea';
    protected $listRoute   = '/admin/tipos-areas';
}