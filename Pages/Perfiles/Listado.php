<?php

namespace Tuqan\Pages\Perfiles;

use Tuqan\Pages\Catalog\CatalogListado;

class Listado extends CatalogListado
{
    protected $table       = 'perfiles';
    protected $title       = 'Perfiles';
    protected $templateDir = 'perfiles';
    protected $flashPrefix = 'perfil';
}