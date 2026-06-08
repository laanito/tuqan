<?php

namespace Tuqan\Pages\Clientes;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'clientes';
    protected string $title       = 'Cliente';
    protected string $templateDir = 'clientes';
    protected string $flashPrefix = 'cliente';
    protected string $listRoute   = '/admin/clientes';
}