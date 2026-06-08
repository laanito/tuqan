<?php

namespace Tuqan\Pages\TiposMejora;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tipoaccionesmejora';
    protected $title       = 'Tipos Acc. Mejora';
    protected $templateDir = 'tiposmejora';
    protected $flashPrefix = 'tipomejora';
}