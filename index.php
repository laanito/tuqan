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
        header('Location: /login/empresa/');
        exit;
    }
});

$router->addRoute('GET', '/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth_company']);
$router->addRoute('GET', '/main/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth_company']);

$dispatcher =  new Dispatcher($router->getData());

try {
    TuqanLogger::debug('Launching dispatcher: ', array('request' => $_SERVER['REQUEST_URI']));
    $response=$dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    echo $response;
} catch (\Exception $e) {
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
