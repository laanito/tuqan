<?php
namespace Tuqan;
/**
 * Created on 24-oct-2005
 *

 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * @version 0.1.3h
 *
 * Este archivo nos muestra el segundo login, seguimos usando PEAR como index
 */

/**
 * Ademas incluimos nuestro fichero de librerias include.php el cual contiene el resto de includes y datos
 * tales como el servidor y el puerto a donde conectar
 */

if (!isset($_SESSION)) {
    session_start();
}

require_once 'PEAR.php';
require_once 'vendor/autoload.php';
require_once 'DB.php';
require_once 'DB/pgsql.php';
require_once 'HTML/Page.php';
require_once 'Classes/FormatoPagina.php';
require_once 'Classes/Manejador_De_Peticiones.php';
require_once 'Classes/Manejador_Ayuda.php';
require_once 'Classes/Debugger.php';
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
require_once 'Auth.php';

use Tuqan\Classes\Config;
use Tuqan\Classes\Formato_Pagina;
use Tuqan\Classes\Manejador_Base_Datos;



/**
 * Ponemos los datos que haciamos antes en login_empresa
 */

$config=new Config();

if ($_SESSION['loginempresa'] == 1) {

    $_SESSION['encodingdb'] = $config->dbEncoding;
    $_SESSION['encodingapache'] = $config->apacheEncoding;

    $aParametrosNav = explode(';', $_SERVER['HTTP_USER_AGENT']);

    $_SESSION['sistema_operativo'] = trim($aParametrosNav[2]);
    if (preg_match('/Gecko/', $_SERVER['HTTP_USER_AGENT'])) {
        $_SESSION['navegador'] = 'Netscape';
        $bNavegadorOk = true;
    } else if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
        $_SESSION['navegador'] = 'Microsoft Internet Explorer';
        $bNavegadorOk = true;
    } else {
        $bNavegadorOk = false;
    }
    $_SESSION['cliente'] = $_SERVER['HTTP_USER_AGENT'];
    if (isset($_REQUEST['anchura'])) {
        $_SESSION['ancho'] = $_REQUEST['anchura'];
        $_SESSION['alto'] = $_REQUEST['altura'];
    }

    /**
     *             Anchura de la pantalla
     * @var integer
     */
    if (isset($_SESSION['ancho'])) {
        $iAnchura = $_SESSION['ancho'];
    }
    /**
     *         Altura de la pantalla
     * @var integer
     */
    if (isset($_SESSION['alto'])) {
        $iAltura = $_SESSION['alto'];
    }
    $sNavegador = $_SESSION['navegador'];
    $sistema = $_SESSION['sistema_operativo'];
    /**
     * Este es el objeto donde cargamos el estilo. Es una instancia de nuestra clase estilo pagina la cual
     * extiende de HTML_CSS.
     * @var object
     */

    $oEstilo = new \estilo_Pagina($iAnchura, $iAltura, $sNavegador);


    /**
     * Este es el objeto donde cargamos el formulario de login. Es una instancia de la clase HTML_QuickForm.
     * @var object
     */
    /*
     $oFormulario2 = new Formulario_Identificacion('Identificacion', 'POST', 'procesa_Identificacion.php');
     $oFormulario2->inicializar($PHPSESSID, 2, $sLoginEmp,$sPassEmp,$sDbEmp,2);
    */

    /**
     * Este es el objeto que imprimiremos como la pgina. Es una instancia de la clase HTMl_Page.
     * @var object
     */

//if (!$_SESSION['conectado']){
//    header("Location: error.php?motivo=nologeado");    
//}

    /**
     * Aqu incluimos el cdigo necesario para detectar el navegador y formatear la pgina
     */


    /**
     * Aqu es donde vamos a insertar los parmetros que nos van a permitir formatear
     * la pgina segn el navegador y el sistema operativo.
     */
    $formatoPagina =new Formato_Pagina();
    $aParametros = $formatoPagina->variables_Pagina($sNavegador, $sistema);



    $oPagina = new \HTML_Page(array(
        'charset' => $aParametros[0],
        'language' => $aParametros[1],
        'cache' => $aParametros[2],
        'lineend' => $aParametros[3]
    ));


    $oPagina->addScript('javascript/cookies.js', "text/javascript");
    $oPagina->addScript('javascript/focus.js', "text/javascript");
    $oPagina->addScript('javascript/recargarIndex.js', 'text/javascript');

//Esto ahora lo hace qnova.php en el manejador ajax al inicializar


    $oPagina->setTitle("Identificacion");

    $oPagina->addStyleDeclaration($oEstilo, 'text/css');

    /**
     * Aadimos las cabeceras, el submen de las pginas
     */

    $oPagina->addBodyContent("<body onload=setFocus()>");
    $oPagina->addBodyContent("<div id=\"cabecera1\"></div>" .
        "<div id=\"cabecera2\"><img class=\"logo_tuqan\" src=\"images/logotipo-tuqan.svg\"></div>" .
        "<div id=\"cabecera3\"><h1 class=\"titulo_cabecera\">Gestión de Calidad y Medioambiente</h1></div>" .
        "<div id=\"cabecera4\"></div>" .
        "<div id=\"cabecera5\"></div>");
    $oPagina->addBodyContent("<div id=\"login\" align=\"center\"> <br />".
        gettext('sIdentUsuario') . "<b>&nbsp; &nbsp; &nbsp;</b> " . $_SESSION['empresa'] . " </div>");
    $oPagina->addBodyContent("<br /><br />");
    $oPagina->addBodyContent("<div id=\"BordeIzq\"></div>");
    $oPagina->addBodyContent("<div id=\"central\" align=\"center\">");


    $oPagina->addBodyContent("<div class=\"formulario1\"><p class=\"parrafo\">" . gettext('sWelcome2') . "</p>");

    $oPagina->addBodyContent("<br />");

    /**
     * Aqui pasamos el formulario a codigo HTML para incluirlo en el objeto $oPagina.
     */


    if (!$bNavegadorOk) {
        $oPagina->addBodyContent("<p class=\"parrafo\">Navegador no soportado, es posible que la aplicacion tenga problemas</p>");
    }


    function loginFunction()
    {
        global $oPagina;

        $sForm = '<form method="post" action="login_Usuario.php">' .
            '<table class="quickform">' .
            '<tr>' .
            '<td class="campos" align="justify" valign="top"><b>Usuario: </b></td>' .
            '<td align="left" valign="top"><input type="text" id="enfocar" name="username"></td>' .
            '</tr>' .
            '<tr>' .
            '<td class="campos" align="justify" valign="top"><b>Contraseña: </b></td>' .
            '<td align="left" valign="top"><input type="password" name="password"></td>' .
            '</tr>' .
            '</table>' .
            '<input type="submit" class="b_activo campos" value="Aceptar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
            '<input type="reset" class="b_activo" value="Limpiar">' .
            '</form>';

        $oPagina->addBodyContent($sForm . "</div>");
    }

    $sLoginEmp = $_SESSION['login'];
    $sDbEmp = $_SESSION['db'];


    //Creamos el encriptador, las claves de acceso a la DB van encriptadas
    $css =& new \encriptador();
    $clave = 'encriptame';
    $pass = (string)$css->decrypt(trim($config->sPassEtc), $clave);


    //Cadena de conexión a la BD
    $dsn = 'pgsql://' . $sLoginEmp . ':' . $pass . '@' . $config->sServidorEtc . '/' . $sDbEmp;

    $options = array(
        'dsn' => $dsn,
        'table' => 'usuarios',
        'usernamecol' => 'login',
        'passwordcol' => 'password',
        'cryptType' => 'md5'
    );

    $optional = true;

//Creamos objeto de autentificación e iniciamos sesion.
    $a = new \Auth("DB", $options, "\Tuqan\loginFunction", $optional);
    $a->start();

//Auth configuration
    $a->setAdvancedSecurity(TRUE);

//Si usuario logeado
    if ($a->getAuth()) {
        //Si se ha echo un logout -> cerramos sesion.
        if ($_GET['action'] == "logout") {
            $a->logout();
            $a->start();
        } else if (isset($_POST['password'])) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

            $sClaveMd5 = md5($_POST['password']);

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id', 'login', 'perfil', 'area'));
            $oBaseDatos->construir_Tablas(array('usuarios'));
            $oBaseDatos->construir_Where(array('(login=\'' . $_POST["username"] . '\')', '(activo=\'t\')', '(password=\'' . $sClaveMd5 . '\')'));
            $oBaseDatos->consulta();

            $aIterador = $oBaseDatos->coger_Fila();
            $_SESSION['usuarioconectado'] = true;
            $_SESSION['userid'] = $aIterador[0];
            $_SESSION['nombreUsuario'] = $aIterador[1];
            $_SESSION['perfil'] = $aIterador[2];
            $_SESSION['areausuario'] = $aIterador[3];

            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Tablas(array('usuarios'));
            $oBaseDatos->construir_Where(array("id='" . $_SESSION['userid'] . "'"));
            $oBaseDatos->construir_SetSin(array('ultimo_acceso', 'numero_accesos'),
                array('now()', 'numero_accesos+1'));
            $oBaseDatos->consulta();
            $oBaseDatos->desconexion();
            header('Location:qnova.php');
        } else {
            header('Location:qnova.php');
        }
    }

    /**
     * En caso de que el procesa nos devuelva a la pagina 'index.php' con un valor de error 1
     * aqui comprobamos este valor e indicamos que la identificacion ha sido incorrecta.
     */

    if ($_GET['error'] == 1) {
        $oPagina->addBodyContent("<p class=\"prueba\">" . gettext('sIdIncorrecta') . "</p>");
    }

    $oPagina->addBodyContent("</center></div>");
    $oPagina->addBodyContent("<div id=\"logos\">");

    $oPagina->addBodyContent("<div id=\"BordeDer\"></div>");

    $oPagina->addBodyContent("<div id=\"Esq_Izq\"></div>");
    $oPagina->addBodyContent("<div id=\"Borde_Inf\"></div>");
    $oPagina->addBodyContent("<div id=\"Esq_Der\"></div>");
    $oPagina->display();
} else {
    echo("error");
}
