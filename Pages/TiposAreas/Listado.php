<?php

namespace Tuqan\Pages\TiposAreas;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tiposareas';
    protected string $title       = 'Tipos Area';
    protected string $templateDir = 'tiposareas';
    protected string $flashPrefix = 'tiposarea';
}