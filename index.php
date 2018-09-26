<?php
namespace Tuqan;

include 'include.php';
require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/TuqanLogger.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/Manejador_Formularios.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
require_once 'Classes/FormularioIdentificacion.php';
require_once 'Classes/generador_SQL.php';
require_once 'Classes/Config.php';
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'estilo.php';
require_once 'Classes/Procesador_De_Peticiones.php';
require_once 'Classes/Manejador_De_Respuestas.php';
require_once 'Pages/LoginEmpresa.php';
require_once 'Pages/LoginUsuario.php';
require_once 'Pages/IndexPage.php';
require_once 'Pages/MainPage.php';
require_once 'Pages/NotFoundPage.php';
require_once 'Classes/Auth.php';
require_once 'Classes/User.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Tuqan\Classes\TuqanLogger;
use Tuqan\Pages\NotFoundPage;

if (!isset($_SESSION)) {
    session_start();
}

$router = new RouteCollector();

$router->get('/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'MuestraPagina']);
$router->post('/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'ProcesaPagina']);
$router->get('/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'MuestraPagina']);
$router->post('/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'ProcesaPagina']);
$router->get('/', ['Tuqan\Pages\IndexPage', 'MuestraPagina']);
$router->get('/main/', ['Tuqan\Pages\MainPage', 'ShowPage']);

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
