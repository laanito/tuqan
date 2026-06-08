<?php

namespace Tuqan\Pages\Criterios;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'criterios';
    protected string $title       = 'Criterio';
    protected string $templateDir = 'criterios';
    protected string $flashPrefix = 'criterio';
    protected string $listRoute   = '/admin/criterios';
}