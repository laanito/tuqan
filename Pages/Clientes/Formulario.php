<?php

namespace Tuqan\Pages\Clientes;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'clientes';
    protected $title       = 'Cliente';
    protected $templateDir = 'clientes';
    protected $flashPrefix = 'cliente';
    protected $listRoute   = '/admin/clientes';
}