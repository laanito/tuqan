<?php

namespace Tuqan\Pages\Sedes;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'sedes';
    protected $title       = 'Sede';
    protected $templateDir = 'sedes';
    protected $flashPrefix = 'sede';
    protected $listRoute   = '/admin/sedes';
}