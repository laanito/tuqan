<?php

namespace Tuqan\Pages\Clientes;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'clientes';
    protected string $title       = 'Clientes';
    protected string $templateDir = 'clientes';
    protected string $flashPrefix = 'cliente';
}