<?php

namespace Tuqan\Pages\TiposMejora;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'tipoaccionesmejora';
    protected string $title       = 'Tipos Acc. Mejora';
    protected string $templateDir = 'tiposmejora';
    protected string $flashPrefix = 'tipomejora';
}