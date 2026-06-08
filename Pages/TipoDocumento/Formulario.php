<?php

namespace Tuqan\Pages\TipoDocumento;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tipodocumento';
    protected string $title       = 'Tipo documento';
    protected string $templateDir = 'tipodocumento';
    protected string $flashPrefix = 'tipodocumento';
    protected string $listRoute   = '/admin/tipo-documento';
}