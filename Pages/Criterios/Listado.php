<?php

namespace Tuqan\Pages\Criterios;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'criterios';
    protected $title       = 'Criterios';
    protected $templateDir = 'criterios';
    protected $flashPrefix = 'criterio';
}