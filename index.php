<?php
namespace Tuqan;

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
$router->addRoute('GET', '/admin/usuarios', ['Tuqan\Pages\Placeholder', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/admin/perfiles', ['Tuqan\Pages\Placeholder', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/calidad/matriz-ambiental', ['Tuqan\Pages\Placeholder', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/medio/aspectos', ['Tuqan\Pages\Placeholder', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/rrhh/personal', ['Tuqan\Pages\Placeholder', 'ShowPage'], ['before' => 'auth_company']);

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
        && !preg_match('#^/(css|js|images|javascript|lib)/#', $requestedPath)
        && strpos($requestedPath, '/legacy') !== 0;

    if ($isLikelyLegacy) {
        // Let LegacyAction handle it (it will read the path as the action)
        $legacy = new \Tuqan\Pages\LegacyAction();
        echo $legacy->ShowPage();
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
