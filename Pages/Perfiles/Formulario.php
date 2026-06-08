<?php

namespace Tuqan\Pages\Perfiles;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'perfiles';
    protected string $title       = 'Perfil';
    protected string $templateDir = 'perfiles';
    protected string $flashPrefix = 'perfil';
    protected string $listRoute   = '/admin/perfiles';
}