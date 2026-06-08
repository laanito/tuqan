<?php

namespace Tuqan\Pages\TipoDocumento;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tipodocumento';
    protected string $title       = 'Tipo documento';
    protected string $templateDir = 'tipodocumento';
    protected string $flashPrefix = 'tipodocumento';
}