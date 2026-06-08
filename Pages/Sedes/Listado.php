<?php

namespace Tuqan\Pages\Sedes;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'sedes';
    protected string $title       = 'Sedes';
    protected string $templateDir = 'sedes';
    protected string $flashPrefix = 'sede';
}