<?php

namespace Tuqan\Pages\TiposImp;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tiposimp';
    protected string $title       = 'Tipos Imp. Amb.';
    protected string $templateDir = 'tiposimp';
    protected string $flashPrefix = 'tiposimp';
}