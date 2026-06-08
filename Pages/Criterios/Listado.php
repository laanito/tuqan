<?php

namespace Tuqan\Pages\Criterios;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'criterios';
    protected string $title       = 'Criterios';
    protected string $templateDir = 'criterios';
    protected string $flashPrefix = 'criterio';
}