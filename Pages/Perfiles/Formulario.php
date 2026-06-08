<?php

namespace Tuqan\Pages\Perfiles;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'perfiles';
    protected $title       = 'Perfil';
    protected $templateDir = 'perfiles';
    protected $flashPrefix = 'perfil';
    protected $listRoute   = '/admin/perfiles';
}