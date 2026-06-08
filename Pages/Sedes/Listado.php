<?php

namespace Tuqan\Pages\Sedes;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'sedes';
    protected $title       = 'Sedes';
    protected $templateDir = 'sedes';
    protected $flashPrefix = 'sede';
}