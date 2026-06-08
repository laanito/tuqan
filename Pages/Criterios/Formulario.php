<?php

namespace Tuqan\Pages\Criterios;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'criterios';
    protected $title       = 'Criterio';
    protected $templateDir = 'criterios';
    protected $flashPrefix = 'criterio';
    protected $listRoute   = '/admin/criterios';
}