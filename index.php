<?php

namespace Tuqan;

// Dev helper: force opcache to pick up code changes when using Docker volume mounts.
// Must be placed AFTER the namespace declaration.
if (function_exists('opcache_reset')) {
    opcache_reset();
}

include 'include.php';
/**
 * PEAR Files
 */
require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'HTML/TreeMenu.php';
require_once 'Pager/Pager.php';

/**
 * Minimal Illuminate routing bindings required when using
 * modern Illuminate packages (8+) alongside Former 5.x.
 * Former explicitly does $app->bindIf('url', 'Illuminate\Routing\UrlGenerator')
 * which then requires RouteCollectionInterface.
 */
$illuminateContainer = \Illuminate\Container\Container::getInstance();

// Bind the interface that UrlGenerator depends on
$illuminateContainer->bindIf(\Illuminate\Routing\RouteCollectionInterface::class, function () {
    return new \Illuminate\Routing\RouteCollection();
});

// Bind the 'url' string that Former looks for
$illuminateContainer->bindIf('url', function ($app) {
    $routes = $app[\Illuminate\Routing\RouteCollectionInterface::class];
    $request = $app->bound('request')
        ? $app['request']
        : \Illuminate\Http\Request::createFromGlobals();

    return new \Illuminate\Routing\UrlGenerator($routes, $request);
});

// Also bind the concrete class for good measure
$illuminateContainer->bindIf(\Illuminate\Routing\UrlGenerator::class, 'url');

// Initialize Former early with our container.
// This prevents Former from creating its own empty Container() later
// (see FormerServiceProvider::make), which was causing the
// RouteCollectionInterface resolution failure.
\Former\FormerServiceProvider::make($illuminateContainer);


/**
 * Forms
 */
require_once 'Classes/Form_Administracion.php';
require_once 'Classes/Form_Comun.php';
require_once 'Classes/Form_Calidad.php';
require_once 'Classes/Form_Medio.php';
require_once 'Classes/Form_Nuevo.php';
require_once 'Classes/forms.php';


/**
 * Old Generators
 */
require_once 'Classes/generador_arboles.php';
require_once 'Classes/generador_listados.php';


/**
 * New Tuqan Classes (loaded via PSR-4 autoloader)
 * Only a few non-namespaced legacy helpers still need manual require.
 */
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'boton.php';

/**
 * Misc
 */
require_once 'Classes/Titulos.php';


/**
 * Old Handlers
 */
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/Manejador_Formularios.php';

require_once 'Classes/Manejador_De_Respuestas.php';
require_once 'Classes/Manejador_Listados.php';
require_once 'Classes/Manejador_Detalles.php';


/**
 * Old Processors
 */
require_once 'Classes/Procesador_De_Peticiones.php';
require_once 'Classes/Procesar_Listados.php';
require_once 'Procesar_Editor.php';
require_once 'Classes/Procesar_Funciones_Comunes.php';
require_once 'Classes/Procesar_Detalles.php';
require_once 'Classes/Procesar_Formularios.php';
require_once 'Classes/Procesar_Arbol.php';
require_once 'Classes/Procesar_Ayuda.php';


/**
 * New Pages
 */
require_once 'Pages/LoginEmpresa.php';
require_once 'Pages/LoginUsuario.php';
require_once 'Pages/MainPage.php';
require_once 'Pages/NotFoundPage.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Tuqan\Classes\TuqanLogger;
use Tuqan\Pages\NotFoundPage;

if (!isset($_SESSION)) {
    session_start();
}

$router = new RouteCollector();

$router->filter('auth', function(){
    if(!isset($_SESSION['loginempresa']) || $_SESSION['loginempresa']!=1)
    {
        header('Location: /login/empresa/');
        return false;
    }
    if(!isset($_SESSION['usuarioconectado']) ||!$_SESSION['usuarioconectado'])
    {
        header('Location: /login/usuario/');
        return false;
    }
});

$router->addRoute('GET', '/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'MuestraPagina']);
$router->addRoute('POST', '/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'ProcesaPagina']);
$router->addRoute('GET', '/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'MuestraPagina']);
$router->addRoute('POST', '/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'ProcesaPagina']);

// Logout - clears session and returns to company login
$router->addRoute('GET', '/logout/', ['Tuqan\Pages\Logout', 'ShowPage']);
$router->addRoute('POST', '/logout/', ['Tuqan\Pages\Logout', 'ShowPage']);

// Note: controller() registrations for /ajax and /messages remain commented out for the
// minimum viable home page. They pull in significant additional legacy code paths and
// can be re-enabled incrementally once those areas are modernized.
// Simple auth gate for the minimal working app:
// If the user has not completed company login, redirect to the company login form.
$router->filter('auth_company', function() {
    if (!isset($_SESSION['loginempresa']) || $_SESSION['loginempresa'] != 1) {
        if (class_exists('\Tuqan\Classes\TuqanLogger')) {
            \Tuqan\Classes\TuqanLogger::debug('auth_company filter blocked', [
                'loginempresa_value' => $_SESSION['loginempresa'] ?? 'not set',
                'uri' => $_SERVER['REQUEST_URI'] ?? ''
            ]);
        }
        error_log("TUQAN_DIAG: auth_company BLOCKED - loginempresa=" . ($_SESSION['loginempresa'] ?? 'NOT SET') . " for URI=" . ($_SERVER['REQUEST_URI'] ?? ''));
        header('Location: /login/empresa/');
        exit;
    }
});

$router->addRoute('GET', '/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/main/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth_company']);

// === Legacy menu action routes (starting the Phroute mapping) ===
$router->addRoute('GET', '/legacy', ['Tuqan\Pages\LegacyAction', 'ShowPage'], ['before' => 'auth_company']);

// Example modernized routes for menu items (stubs for now)
$router->addRoute('GET', '/admin/usuarios', ['Tuqan\Pages\Usuarios\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/usuarios/nuevo', ['Tuqan\Pages\Usuarios\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/usuarios/editar/{id}', ['Tuqan\Pages\Usuarios\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Usuarios (Stage 8.6 - unblocked now that Perfiles has POST)
$router->addRoute('POST', '/admin/usuarios/nuevo', ['Tuqan\Pages\Usuarios\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/usuarios/editar/{id}', ['Tuqan\Pages\Usuarios\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy menu accion paths for modernized modules (so menu clicks work)
$router->addRoute('GET', '/administracion/usuarios/listado/ver', ['Tuqan\Pages\Usuarios\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/usuarios/nuevo', ['Tuqan\Pages\Usuarios\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/usuarios/editar', ['Tuqan\Pages\Usuarios\Listado', 'ShowPage'], ['before' => 'auth_company']); // go to list for selection
$router->addRoute('GET', '/admin/perfiles', ['Tuqan\Pages\Perfiles\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/perfiles/nuevo', ['Tuqan\Pages\Perfiles\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/perfiles/editar/{id}', ['Tuqan\Pages\Perfiles\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Perfiles (Stage 8.6)
$router->addRoute('POST', '/admin/perfiles/nuevo', ['Tuqan\Pages\Perfiles\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/perfiles/editar/{id}', ['Tuqan\Pages\Perfiles\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy menu accion paths for Perfiles (menu clicks from Aplicacion → Perfiles)
$router->addRoute('GET', '/administracion/perfiles/listado/ver', ['Tuqan\Pages\Perfiles\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/perfiles/nuevo', ['Tuqan\Pages\Perfiles\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/perfiles/editar', ['Tuqan\Pages\Perfiles\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Aplicacion → Sedes (ex-Empresas / ex-Hospitales, renamed in 8.6 per user nitpick) ===
$router->addRoute('GET', '/admin/sedes', ['Tuqan\Pages\Sedes\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/sedes/listado/ver', ['Tuqan\Pages\Sedes\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Legacy child actions for the Sedes menu entry (updated by 0012 patch)
$router->addRoute('GET', '/administracion/sedes/nuevo', ['Tuqan\Pages\Sedes\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/sedes/editar', ['Tuqan\Pages\Sedes\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Modern clean paths for Sedes (for direct links / future use)
$router->addRoute('GET', '/admin/sedes/nuevo', ['Tuqan\Pages\Sedes\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/sedes/editar/{id}', ['Tuqan\Pages\Sedes\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Sedes (Stage 8.6)
$router->addRoute('POST', '/admin/sedes/nuevo', ['Tuqan\Pages\Sedes\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/sedes/editar/{id}', ['Tuqan\Pages\Sedes\Formulario', 'Procesar'], ['before' => 'auth_company']);
// === Aplicacion → Menus, Idiomas, Permisos (Stage 8.5) ===
$router->addRoute('GET', '/admin/menus', ['Tuqan\Pages\Menus\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/menus/listado/nuevo', ['Tuqan\Pages\Menus\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/menus/nuevo', ['Tuqan\Pages\Menus\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// Basic menus editing (orden + Spanish label) - POST to the Listado for simplicity in this leg
$router->addRoute('POST', '/admin/menus', ['Tuqan\Pages\Menus\Listado', 'Procesar'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/idiomas', ['Tuqan\Pages\Idiomas\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/idiomas/listado/nuevo', ['Tuqan\Pages\Idiomas\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/idiomas/nuevo', ['Tuqan\Pages\Idiomas\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/idiomas/editar/{id}', ['Tuqan\Pages\Idiomas\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/permisos', ['Tuqan\Pages\Permisos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/modulos/listado/nuevo', ['Tuqan\Pages\Permisos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/permisos/nuevo', ['Tuqan\Pages\Permisos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/permisos/editar/{id}', ['Tuqan\Pages\Permisos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST for the basic permissions matrix (Stage 8.6)
$router->addRoute('POST', '/admin/permisos/editar/{id}', ['Tuqan\Pages\Permisos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Personalizacion children (Stage 8.7: 3 more full modules + enhancements; 2 remain basic)
$router->addRoute('GET', '/admin/clientes', ['Tuqan\Pages\Clientes\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/clientes/nuevo', ['Tuqan\Pages\Clientes\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/clientes/editar/{id}', ['Tuqan\Pages\Clientes\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST for Clientes
$router->addRoute('POST', '/admin/clientes/nuevo', ['Tuqan\Pages\Clientes\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/clientes/editar/{id}', ['Tuqan\Pages\Clientes\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy path for the Clientes menu entry (from full legacy menu)
$router->addRoute('GET', '/administracion/clientes/listado/ver', ['Tuqan\Pages\Clientes\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Criterios (full from 8.6)
$router->addRoute('GET', '/admin/criterios', ['Tuqan\Pages\Criterios\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/criterios/nuevo', ['Tuqan\Pages\Criterios\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/criterios/editar/{id}', ['Tuqan\Pages\Criterios\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// Legacy for Criterios (from full menu)
$router->addRoute('GET', '/administracion/criterios/listado/ver', ['Tuqan\Pages\Criterios\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Legacy for Tipos Mejora (tipomejora) - was missing the legacy path mapping, so /administracion/tipomejora/listado/ver fell through
$router->addRoute('GET', '/administracion/tipomejora/listado/ver', ['Tuqan\Pages\TiposMejora\Listado', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/criterios/nuevo', ['Tuqan\Pages\Criterios\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/criterios/editar/{id}', ['Tuqan\Pages\Criterios\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Stage 8.7: 3 new full modules under Personalizacion
$router->addRoute('GET', '/admin/tipos-mejora', ['Tuqan\Pages\TiposMejora\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipos-mejora/nuevo', ['Tuqan\Pages\TiposMejora\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipos-mejora/editar/{id}', ['Tuqan\Pages\TiposMejora\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tipos-mejora/nuevo', ['Tuqan\Pages\TiposMejora\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tipos-mejora/editar/{id}', ['Tuqan\Pages\TiposMejora\Formulario', 'Procesar'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/tipos-areas', ['Tuqan\Pages\TiposAreas\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipos-areas/nuevo', ['Tuqan\Pages\TiposAreas\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipos-areas/editar/{id}', ['Tuqan\Pages\TiposAreas\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tipos-areas/nuevo', ['Tuqan\Pages\TiposAreas\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tipos-areas/editar/{id}', ['Tuqan\Pages\TiposAreas\Formulario', 'Procesar'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/tipo-documento', ['Tuqan\Pages\TipoDocumento\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipo-documento/nuevo', ['Tuqan\Pages\TipoDocumento\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipo-documento/editar/{id}', ['Tuqan\Pages\TipoDocumento\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tipo-documento/nuevo', ['Tuqan\Pages\TipoDocumento\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tipo-documento/editar/{id}', ['Tuqan\Pages\TipoDocumento\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy for the new 8.7 modules (from full legacy menu)
$router->addRoute('GET', '/administracion/tiposareas/listado/ver', ['Tuqan\Pages\TiposAreas\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/tipodocumento/listado/nuevo', ['Tuqan\Pages\TipoDocumento\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Stage 8.8: finish last 2 Personalizacion (tiposamb, tiposimp) + Tipo Cursos (full modern)
$router->addRoute('GET', '/admin/tiposamb', ['Tuqan\Pages\TiposAmb\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tiposamb/nuevo', ['Tuqan\Pages\TiposAmb\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tiposamb/editar/{id}', ['Tuqan\Pages\TiposAmb\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tiposamb/nuevo', ['Tuqan\Pages\TiposAmb\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tiposamb/editar/{id}', ['Tuqan\Pages\TiposAmb\Formulario', 'Procesar'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/tiposimp', ['Tuqan\Pages\TiposImp\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tiposimp/nuevo', ['Tuqan\Pages\TiposImp\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tiposimp/editar/{id}', ['Tuqan\Pages\TiposImp\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tiposimp/nuevo', ['Tuqan\Pages\TiposImp\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tiposimp/editar/{id}', ['Tuqan\Pages\TiposImp\Formulario', 'Procesar'], ['before' => 'auth_company']);

$router->addRoute('GET', '/admin/tipo-cursos', ['Tuqan\Pages\TipoCursos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipo-cursos/nuevo', ['Tuqan\Pages\TipoCursos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/tipo-cursos/editar/{id}', ['Tuqan\Pages\TipoCursos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

$router->addRoute('POST', '/admin/tipo-cursos/nuevo', ['Tuqan\Pages\TipoCursos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/tipo-cursos/editar/{id}', ['Tuqan\Pages\TipoCursos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy for 8.8 modules (from full legacy menu accions)
$router->addRoute('GET', '/administracion/tiposamb/listado/ver', ['Tuqan\Pages\TiposAmb\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/tiposimp/listado/ver', ['Tuqan\Pages\TiposImp\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/tipo_cursos/listado/ver', ['Tuqan\Pages\TipoCursos\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Proveedores (Stage 9.2; list routes corrected in 9.3 to use Listado per standard pattern) ===
$router->addRoute('GET', '/admin/proveedores', ['Tuqan\Pages\Proveedores\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/proveedores/nuevo', ['Tuqan\Pages\Proveedores\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/proveedores/editar/{id}', ['Tuqan\Pages\Proveedores\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Proveedores
$router->addRoute('POST', '/admin/proveedores/nuevo', ['Tuqan\Pages\Proveedores\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/proveedores/editar/{id}', ['Tuqan\Pages\Proveedores\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy routes for Proveedores (from full legacy menu accions)
$router->addRoute('GET', '/administracion/proveedores/listado/ver', ['Tuqan\Pages\Proveedores\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Equipos (Stage 9.3) ===
$router->addRoute('GET', '/admin/equipos', ['Tuqan\Pages\Equipos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/equipos/nuevo', ['Tuqan\Pages\Equipos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/equipos/editar/{id}', ['Tuqan\Pages\Equipos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Equipos
$router->addRoute('POST', '/admin/equipos/nuevo', ['Tuqan\Pages\Equipos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/equipos/editar/{id}', ['Tuqan\Pages\Equipos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy routes for Equipos (from full legacy menu accions: equipos:listado:listado:ver + administracion variant)
$router->addRoute('GET', '/administracion/equipos/listado/ver', ['Tuqan\Pages\Equipos\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Equipos Revisiones / mantenimientos (Stage 9.33) ===
$router->addRoute('GET', '/admin/equipos/revisiones', ['Tuqan\Pages\Equipos\Revisiones\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/equipos/revisiones/nuevo', ['Tuqan\Pages\Equipos\Revisiones\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/equipos/revisiones/editar/{id}', ['Tuqan\Pages\Equipos\Revisiones\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/equipos/revisiones/nuevo', ['Tuqan\Pages\Equipos\Revisiones\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/equipos/revisiones/editar/{id}', ['Tuqan\Pages\Equipos\Revisiones\Formulario', 'Procesar'], ['before' => 'auth_company']);
// Legacy menu: equipos:revision:listado:ver
$router->addRoute('GET', '/administracion/equipos/revision/listado/ver', ['Tuqan\Pages\Equipos\Revisiones\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Equipos Calendario (Stage 9.36) ===
$router->addRoute('GET', '/admin/equipos/calendario', ['Tuqan\Pages\Equipos\Calendario', 'ShowPage'], ['before' => 'auth_company']);
// Legacy menu: equipos:calendario:listado:ver
$router->addRoute('GET', '/administracion/equipos/calendario/listado/ver', ['Tuqan\Pages\Equipos\Calendario', 'ShowPage'], ['before' => 'auth_company']);

// === Mejora / Acciones de Mejora (Stage 9.4) ===
$router->addRoute('GET', '/admin/mejora', ['Tuqan\Pages\Mejora\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/mejora/nuevo', ['Tuqan\Pages\Mejora\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/mejora/editar/{id}', ['Tuqan\Pages\Mejora\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Mejora
$router->addRoute('POST', '/admin/mejora/nuevo', ['Tuqan\Pages\Mejora\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/mejora/editar/{id}', ['Tuqan\Pages\Mejora\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Quick state machine actions (Stage 9.28) — direct verify/close from list
$router->addRoute('POST', '/admin/mejora/verificar/{id}', ['Tuqan\Pages\Mejora\Formulario', 'Verificar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/mejora/cerrar/{id}', ['Tuqan\Pages\Mejora\Formulario', 'Cerrar'], ['before' => 'auth_company']);

// Legacy routes for Mejora (from full legacy menu accions: mejora:listado:listado:ver)
$router->addRoute('GET', '/administracion/mejora/listado/ver', ['Tuqan\Pages\Mejora\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Formación (Planes) + Documentación shell (Stage 9.5) ===
// Formación basic slice (plan_formacion)
$router->addRoute('GET', '/admin/formacion', ['Tuqan\Pages\Formacion\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/nuevo', ['Tuqan\Pages\Formacion\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/editar/{id}', ['Tuqan\Pages\Formacion\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Formación
$router->addRoute('POST', '/admin/formacion/nuevo', ['Tuqan\Pages\Formacion\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/formacion/editar/{id}', ['Tuqan\Pages\Formacion\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy for Formación planes
$router->addRoute('GET', '/administracion/formacion/planes/listado/ver', ['Tuqan\Pages\Formacion\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Formación Cursos (Stage 9.20 subs first slice)
$router->addRoute('GET', '/admin/formacion/cursos', ['Tuqan\Pages\Formacion\Cursos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/cursos/nuevo', ['Tuqan\Pages\Formacion\Cursos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/cursos/editar/{id}', ['Tuqan\Pages\Formacion\Cursos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Formación Cursos
$router->addRoute('POST', '/admin/formacion/cursos/nuevo', ['Tuqan\Pages\Formacion\Cursos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/formacion/cursos/editar/{id}', ['Tuqan\Pages\Formacion\Cursos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Formación Inscripciones (Stage 9.22 more subs)
$router->addRoute('GET', '/admin/formacion/inscripciones', ['Tuqan\Pages\Formacion\Inscripciones\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/inscripciones/nuevo', ['Tuqan\Pages\Formacion\Inscripciones\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/formacion/inscripciones/editar/{id}', ['Tuqan\Pages\Formacion\Inscripciones\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Formación Inscripciones
$router->addRoute('POST', '/admin/formacion/inscripciones/nuevo', ['Tuqan\Pages\Formacion\Inscripciones\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/formacion/inscripciones/editar/{id}', ['Tuqan\Pages\Formacion\Inscripciones\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Documentación shell (basic list + landing over documentos)
$router->addRoute('GET', '/admin/documentacion', ['Tuqan\Pages\Documentacion\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/documentacion/nuevo', ['Tuqan\Pages\Documentacion\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/documentacion/editar/{id}', ['Tuqan\Pages\Documentacion\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Documentación shell
$router->addRoute('POST', '/admin/documentacion/nuevo', ['Tuqan\Pages\Documentacion\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/documentacion/editar/{id}', ['Tuqan\Pages\Documentacion\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Quick workflow actions (Stage 9.34)
$router->addRoute('POST', '/admin/documentacion/enviar-revision/{id}', ['Tuqan\Pages\Documentacion\Formulario', 'EnviarRevision'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/documentacion/revisar/{id}', ['Tuqan\Pages\Documentacion\Formulario', 'Revisar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/documentacion/aprobar/{id}', ['Tuqan\Pages\Documentacion\Formulario', 'Aprobar'], ['before' => 'auth_company']);

// Key legacy for Documentación (vigor / borradores + general)
$router->addRoute('GET', '/administracion/documentacion/docvigor/listado/ver', ['Tuqan\Pages\Documentacion\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/documentacion/docborrador/listado/ver', ['Tuqan\Pages\Documentacion\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Modern tree/arbol view for Documentación (Stage 9.12 first slice)
$router->addRoute('GET', '/admin/documentacion/arbol', ['Tuqan\Pages\Documentacion\Arbol', 'ShowPage'], ['before' => 'auth_company']);

// === Auditorías / Programa (Stage 9.6 basic slice) ===
$router->addRoute('GET', '/admin/auditorias', ['Tuqan\Pages\Auditorias\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/nuevo', ['Tuqan\Pages\Auditorias\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/editar/{id}', ['Tuqan\Pages\Auditorias\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Auditorías (Programa)
$router->addRoute('POST', '/admin/auditorias/nuevo', ['Tuqan\Pages\Auditorias\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/editar/{id}', ['Tuqan\Pages\Auditorias\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy routes for Auditorías Programa (from full legacy menu accions: auditorias:programa:*)
$router->addRoute('GET', '/administracion/auditorias/programa/listado/ver', ['Tuqan\Pages\Auditorias\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Auditorías Ejecución (Stage 9.19 first slice) ===
$router->addRoute('GET', '/admin/auditorias/ejecucion', ['Tuqan\Pages\Auditorias\Ejecucion\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/ejecucion/nuevo', ['Tuqan\Pages\Auditorias\Ejecucion\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/ejecucion/editar/{id}', ['Tuqan\Pages\Auditorias\Ejecucion\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Auditorías Ejecución
$router->addRoute('POST', '/admin/auditorias/ejecucion/nuevo', ['Tuqan\Pages\Auditorias\Ejecucion\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/ejecucion/editar/{id}', ['Tuqan\Pages\Auditorias\Ejecucion\Formulario', 'Procesar'], ['before' => 'auth_company']);

// === Auditorías Hallazgos (Stage 9.32 first slice) ===
$router->addRoute('GET', '/admin/auditorias/hallazgos', ['Tuqan\Pages\Auditorias\Hallazgos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/hallazgos/nuevo', ['Tuqan\Pages\Auditorias\Hallazgos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/hallazgos/editar/{id}', ['Tuqan\Pages\Auditorias\Hallazgos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/hallazgos/nuevo', ['Tuqan\Pages\Auditorias\Hallazgos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/hallazgos/editar/{id}', ['Tuqan\Pages\Auditorias\Hallazgos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// === Auditorías Horario (Stage 9.35 first slice) ===
$router->addRoute('GET', '/admin/auditorias/horario', ['Tuqan\Pages\Auditorias\Horario\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/horario/nuevo', ['Tuqan\Pages\Auditorias\Horario\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/auditorias/horario/editar/{id}', ['Tuqan\Pages\Auditorias\Horario\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/horario/nuevo', ['Tuqan\Pages\Auditorias\Horario\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/auditorias/horario/editar/{id}', ['Tuqan\Pages\Auditorias\Horario\Formulario', 'Procesar'], ['before' => 'auth_company']);

// === Aspectos Ambientales (Stage 9.7 basic slice) ===
$router->addRoute('GET', '/admin/aspectos', ['Tuqan\Pages\Aspectos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/aspectos/nuevo', ['Tuqan\Pages\Aspectos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/aspectos/editar/{id}', ['Tuqan\Pages\Aspectos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Aspectos Ambientales
$router->addRoute('POST', '/admin/aspectos/nuevo', ['Tuqan\Pages\Aspectos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/aspectos/editar/{id}', ['Tuqan\Pages\Aspectos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Key legacy for Aspectos Ambientales (maspectos + aambientales accions)
$router->addRoute('GET', '/administracion/maspectos', ['Tuqan\Pages\Aspectos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/administracion/aambientales/revision/listado/ver', ['Tuqan\Pages\Aspectos\Listado', 'ShowPage'], ['before' => 'auth_company']);

// Matrix view (Stage 9.14 first slice)
$router->addRoute('GET', '/admin/aspectos/matriz', ['Tuqan\Pages\Aspectos\Matriz', 'ShowPage'], ['before' => 'auth_company']);

// === Indicadores (Stage 9.9 basic slice) ===
$router->addRoute('GET', '/admin/indicadores', ['Tuqan\Pages\Indicadores\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/indicadores/nuevo', ['Tuqan\Pages\Indicadores\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/indicadores/editar/{id}', ['Tuqan\Pages\Indicadores\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Indicadores
$router->addRoute('POST', '/admin/indicadores/nuevo', ['Tuqan\Pages\Indicadores\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/indicadores/editar/{id}', ['Tuqan\Pages\Indicadores\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Key legacy for Indicadores (from full legacy menu accions: indicadores:indicadores:*)
$router->addRoute('GET', '/administracion/indicadores/indicadores/listado/ver', ['Tuqan\Pages\Indicadores\Listado', 'ShowPage'], ['before' => 'auth_company']);

// === Procesos (Stage 9.10 basic slice; core procesos catalog / legacy 76) ===
$router->addRoute('GET', '/admin/procesos', ['Tuqan\Pages\Procesos\Listado', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/procesos/nuevo', ['Tuqan\Pages\Procesos\Formulario', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/procesos/editar/{id}', ['Tuqan\Pages\Procesos\Formulario', 'ShowPage'], ['before' => 'auth_company']);

// POST routes for Procesos
$router->addRoute('POST', '/admin/procesos/nuevo', ['Tuqan\Pages\Procesos\Formulario', 'Procesar'], ['before' => 'auth_company']);
$router->addRoute('POST', '/admin/procesos/editar/{id}', ['Tuqan\Pages\Procesos\Formulario', 'Procesar'], ['before' => 'auth_company']);

// Legacy routes for Procesos (from menu: procesos:catalogos:arbol:ver and catalog variants)
$router->addRoute('GET', '/administracion/procesos/catalogos/arbol/ver', ['Tuqan\Pages\Procesos\Arbol', 'ShowPage'], ['before' => 'auth_company']);

// Modern Árbol / tree view (Stage 9.11)
$router->addRoute('GET', '/admin/procesos/arbol', ['Tuqan\Pages\Procesos\Arbol', 'ShowPage'], ['before' => 'auth_company']);

// No generic catch-all route to avoid conflicts with auth filter and route ordering.
// Unknown paths are handled gracefully in the exception block below.

$dispatcher =  new Dispatcher($router->getData());

try {
    TuqanLogger::debug('Launching dispatcher: ', array('request' => $_SERVER['REQUEST_URI']));
    $response=$dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    echo $response;
} catch (\Exception $e) {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

    // If this looks like a legacy module path (not a known modern route and not login/static),
    // send it to the nice LegacyAction handler instead of the scary cloud 404.
    $isLikelyLegacy = !in_array($requestedPath, ['/', '/main/', '/login/empresa/', '/login/usuario/', '/logout/'])
        && !preg_match('#^/(css|js|images|javascript|lib|admin)/#', $requestedPath)   // exclude modernized /admin/* paths
        && strpos($requestedPath, '/legacy') !== 0;

    if ($isLikelyLegacy) {
        // Let LegacyAction handle it (it will read the path as the action)
        $legacy = new \Tuqan\Pages\LegacyAction();
        echo $legacy->ShowPage();
        return;
    }

    // For modern paths (especially under /admin/), show the real exception during development
    // instead of hiding everything behind a generic 404.
    if (strpos($requestedPath, '/admin/') === 0) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Exception while handling modern route:\n\n";
        echo $e->getMessage() . "\n\n";
        echo $e->getTraceAsString();
        return;
    }

    TuqanLogger::debug(
        'Page not found: ',
        array(
            'request' => $_SERVER['REQUEST_URI'],
            'errormessage' => $e->getMessage()
        )
    );
    $page = new NotFoundPage();
    echo $page->ShowPage();
}
