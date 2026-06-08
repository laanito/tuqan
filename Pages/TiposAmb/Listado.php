<?php

namespace Tuqan\Pages\TiposAmb;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tiposamb';
    protected $title       = 'Tipos Amb. Aplicable';
    protected $templateDir = 'tiposamb';
    protected $flashPrefix = 'tiposamb';
}