<?php

namespace Tuqan\Pages\Clientes;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'clientes';
    protected $title       = 'Clientes';
    protected $templateDir = 'clientes';
    protected $flashPrefix = 'cliente';
}