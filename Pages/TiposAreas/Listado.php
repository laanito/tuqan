<?php

namespace Tuqan\Pages\TiposAreas;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tiposareas';
    protected $title       = 'Tipos Area';
    protected $templateDir = 'tiposareas';
    protected $flashPrefix = 'tiposarea';
}