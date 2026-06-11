<?php

namespace Tuqan\Pages\Proveedores;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table         = 'proveedores';
    protected string $title         = 'Proveedores';
    protected string $templateDir = 'proveedores';
    protected string $flashPrefix = 'proveedor';
}
