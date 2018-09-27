<?php
namespace Tuqan;

include 'include.php';
/**
 * PEAR Files
 */
require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'HTML/TreeMenu.php';
require_once 'HTML/Table.php';
require_once 'Pager/Pager.php';


/**
 * Forms
 */
require_once 'Classes/generador_Formularios.php';
require_once 'Classes/Form_Administracion.php';
require_once 'Classes/Form_Comun.php';
require_once 'Classes/Form_Calidad.php';
require_once 'Classes/Form_Medio.php';
require_once 'Classes/Form_Nuevo.php';
require_once 'Classes/forms.php';


/**
 * Old Generators
 */
require_once 'Classes/generador_SQL.php';
require_once 'Classes/generador_arboles.php';
require_once 'Classes/generador_listados.php';


/**
 * New Tuqan Classes
 */
require_once 'Classes/TuqanLogger.php';
require_once 'Classes/Auth.php';
require_once 'Classes/User.php';
require_once 'Classes/AjaxHandler.php';

/**
 * Misc
 */
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/FormularioIdentificacion.php';
require_once 'Classes/generador_SQL.php';
require_once 'Classes/Config.php';
require_once 'encriptador.php';
require_once 'constantes.inc.php';
require_once 'estilo.php';
require_once 'Classes/Titulos.php';
require_once 'boton.php';


/**
 * Old Handlers
 */
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Manejador_Editor.php';
require_once 'Classes/Manejador_Funciones_Comunes.php';
require_once 'Classes/Manejador_Formularios.php';
require_once 'Classes/Manejador_Base_Datos.class.php';
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


$router->get('/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'MuestraPagina']);
$router->post('/login/empresa/', ['Tuqan\Pages\LoginEmpresa', 'ProcesaPagina']);
$router->get('/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'MuestraPagina']);
$router->post('/login/usuario/', ['Tuqan\Pages\LoginUsuario', 'ProcesaPagina']);
$router->get('/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth']);
$router->get('/main/', ['Tuqan\Pages\MainPage', 'ShowPage'], ['before' => 'auth']);
$router->controller('/ajax','Tuqan\Classes\AjaxHandler',['before' => 'auth']);

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
