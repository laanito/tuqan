<?php

namespace Tuqan\Pages\Perfiles;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected string $table       = 'perfiles';
    protected string $title       = 'Perfiles';
    protected string $templateDir = 'perfiles';
    protected string $flashPrefix = 'perfil';
}