<?php

namespace Tuqan\Pages\TiposAmb;

use Tuqan\Pages\Catalog\CatalogFormulario;

class Formulario extends CatalogFormulario
{
    protected $table       = 'tiposamb';
    protected $title       = 'Tipo Amb. Aplicable';
    protected $templateDir = 'tiposamb';
    protected $flashPrefix = 'tiposamb';
    protected $listRoute   = '/admin/tiposamb';
}