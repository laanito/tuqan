<?php

namespace Tuqan\Pages\TiposImp;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tiposimp';
    protected string $title       = 'Tipo Imp. Amb.';
    protected string $templateDir = 'tiposimp';
    protected string $flashPrefix = 'tiposimp';
    protected string $listRoute   = '/admin/tiposimp';
}