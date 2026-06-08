<?php

namespace Tuqan\Pages\TiposImp;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tiposimp';
    protected $title       = 'Tipo Imp. Amb.';
    protected $templateDir = 'tiposimp';
    protected $flashPrefix = 'tiposimp';
    protected $listRoute   = '/admin/tiposimp';
}