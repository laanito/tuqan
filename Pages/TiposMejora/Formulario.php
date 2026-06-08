<?php

namespace Tuqan\Pages\TiposMejora;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tipoaccionesmejora';
    protected $title       = 'Tipo Acc. Mejora';
    protected $templateDir = 'tiposmejora';
    protected $flashPrefix = 'tipomejora';
    protected $listRoute   = '/admin/tipos-mejora';
}