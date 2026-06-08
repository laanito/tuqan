<?php

namespace Tuqan\Pages\Sedes;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'sedes';
    protected string $title       = 'Sede';
    protected string $templateDir = 'sedes';
    protected string $flashPrefix = 'sede';
    protected string $listRoute   = '/admin/sedes';
}