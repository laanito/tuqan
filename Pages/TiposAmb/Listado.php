<?php

namespace Tuqan\Pages\TiposAmb;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tiposamb';
    protected string $title       = 'Tipos Amb. Aplicable';
    protected string $templateDir = 'tiposamb';
    protected string $flashPrefix = 'tiposamb';
}