<?php

namespace Tuqan\Pages\TiposAmb;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected string $table       = 'tiposamb';
    protected string $title       = 'Tipo Amb. Aplicable';
    protected string $templateDir = 'tiposamb';
    protected string $flashPrefix = 'tiposamb';
    protected string $listRoute   = '/admin/tiposamb';
}