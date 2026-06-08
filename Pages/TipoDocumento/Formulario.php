<?php

namespace Tuqan\Pages\TipoDocumento;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tipodocumento';
    protected $title       = 'Tipo documento';
    protected $templateDir = 'tipodocumento';
    protected $flashPrefix = 'tipodocumento';
    protected $listRoute   = '/admin/tipo-documento';
}