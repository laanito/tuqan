<?php

namespace Tuqan\Pages\TiposImp;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tiposimp';
    protected $title       = 'Tipos Imp. Amb.';
    protected $templateDir = 'tiposimp';
    protected $flashPrefix = 'tiposimp';
}