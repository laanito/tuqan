<?php

namespace Tuqan\Pages\TiposMejora;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tipoaccionesmejora';
    protected string $title       = 'Tipo Acc. Mejora';
    protected string $templateDir = 'tiposmejora';
    protected string $flashPrefix = 'tipomejora';
    protected string $listRoute   = '/admin/tipos-mejora';
}