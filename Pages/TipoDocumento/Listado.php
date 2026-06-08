<?php

namespace Tuqan\Pages\TipoDocumento;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'tipodocumento';
    protected $title       = 'Tipo documento';
    protected $templateDir = 'tipodocumento';
    protected $flashPrefix = 'tipodocumento';
}