<?php
/**
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

class html2pdf
{

    var $atts;

    var $siteRoot;
    var $html2psPath;
    var $tempPath;
    var $outputPath;

    var $output = 'client';
    var $conexion;

    function filtrar($sCadena)
    {
        $sSeparador = 'amp;';
        $aTrocear = explode($sSeparador, $sCadena);
        return trim((implode("", $aTrocear)));
    }

    function __construct($aParametros = null)
    {

        $this->atts['pixels'] = 1024;
        $this->atts['scalepoints'] = true;
        $this->atts['renderimages'] = true;
        $this->atts['renderlinks'] = true;
        $this->atts['media'] = 'A4';
        $this->atts['cssmedia'] = 'screen';
        $this->atts['leftmargin'] = 10;
        $this->atts['rightmargin'] = 10;
        $this->atts['topmargin'] = 50;
        $this->atts['bottommargin'] = 40;
        $this->atts['landscape'] = false;
        $this->atts['pageborder'] = false;
        $this->atts['debugbox'] = false;
        $this->atts['encoding'] = NULL;
        $this->atts['method'] = 'fpdf';
        $this->atts['pdfversion'] = 1.3;
        $this->atts['compress'] = false;
        $this->atts['transparency_workaround'] = false;
        $this->atts['imagequality_workaround'] = false;
        $this->conexion = $aParametros;

        $this->siteRoot = '';        // relative path from the page calling this class to the root of your site
        $this->html2psPath = 'public_html/';    // relative path from the root of your site to the folder that contains html2ps.php
        $this->tempPath = 'public_html/temp/';        // relative path from the root of your site to the folder where the html string temporarily saved
        $this->outputPath = 'public_html/out/';    // relative path from the root of your site to the folder where the pdf will be saved
        $this->outputFile = 'mypdf';        // pdf file name

    }

    function createPDF($data)
    {

        // all the code from here to the next comment basically take the
        // siteRoot root relative path, and the absolute script path from
        // the SCRIPT_NAME enviroment variable, and work out what the absolute
        // path to the root of the site should be

        $hostName = "localhost";

        $scriptPath = getenv('SCRIPT_NAME');
        $scriptPath = dirname($scriptPath);
        $scriptPath = explode('/', $scriptPath);
        $levelsUp = substr_count($this->siteRoot, '../');

        $newPath = NULL;
        foreach ($scriptPath as $key => $value) {
            if ($key < count($scriptPath) - $levelsUp) {
                $newPath .= $value . '/';
            }
        }
        // these are the paths used in the script:
        $scr2fnc = $this->siteRoot . $this->html2psPath;                // relative: the script calling this class to the html2ps folder
        $scr2tmp = $this->siteRoot . $this->tempPath;                    // relative: the script calling this class to the temp folder
        $scr2out = $this->siteRoot . $this->outputPath;                // relative: the script calling this class to the output folder
        $rem2tmp = 'http://' . $hostName . $newPath . $this->tempPath;        // absolute: the remote path to the temp folder *
        $rem2fnc = 'http://' . $hostName . $newPath . $this->html2psPath;    // absolute: the remote path to the html2ps folder **           

        // * this is because i dont know how to work out the relative path
        //   from the page calling this class to the html2ps folder, and
        //   because html2ps automatically sticks http:// at the begining of
        //   the url if it dosent have it already, so relative paths dont work

        // ** this is because you cant use a relative path in file_get_contents,
        //    if you do, php will read the file server side, and not actually
        //    send the http request to it


        // write the data to a file, because html2ps will only read a remote
        // file, you cant send it a string
        $file = fopen($scr2tmp . 'temp.html', 'w');
        fwrite($file, $data);
        fclose($file);

        // check the request attributes
        foreach ($this->atts as $key => $value) {

            if (is_bool($value) && $value == true) {
                $this->atts[$key] = 1;
            }
            if (is_bool($value) && $value == false) {
                unset($this->atts[$key]);
            } else if (is_null($value)) {
                $this->atts[$key] = '';
            }

        }

        // create the request url
        $this->atts['URL'] = $rem2tmp . 'temp.html';
        $urlString = http_build_query($this->atts);
        $url = $rem2fnc . 'html2ps.php?' . $urlString . "&login=" . $this->conexion['login'] . "&pass=" . $this->conexion['pass'] .
            "&db=" . $this->conexion['db'] . "&doc=" . $this->conexion['doc'];
        $url2 = $this->filtrar($url);
        $url = $url2;
        // request the pdf
        switch ($this->output) {

            case 'client':
                $url .= '&output=0';
                header("Content-type: application/pdf");
                echo file_get_contents($url);
            //passthru('php file_get_contents('.$url.')');
            /*   echo exec('php public_html/html2ps.php \''.$url.'\' \''.$this->conexion['login'].'\' \''.$this->conexion['pass'].'\' \'' .
                      ''.$this->conexion['db'].'\' \''.$this->conexion['doc'].'\'');*/
               break;

            case 'server':
                $url .= '&output=1';
                $output = file_get_contents($url);
                $pdf = fopen($scr2out . $this->outputFile . '.pdf', 'w');
                fwrite($pdf, $output);
                fclose($pdf);
                break;

        }
        //Devolvemos los valores de memoria y tiempo maximo anteriores
        ini_set('memory_limit', $this->conexion['memoria']);
        ini_set('max_execution_time', $this->conexion['tiempo']);
        //delete the tempoary file            
        unlink($scr2tmp . 'temp.html');
        //Borrar si procede los ficheros temporales de flujogramas

    }

}

/*------------------------------------------------------------*/
/*------------------------------------------------------------*/
if (!isset($_SESSION)) {
    session_start();
}
$iIdDoc = $_GET['docu'];
$iIdProc = $_GET['proc'];
require 'etc/qnova.conf.php';
require_once 'Manejador_Base_Datos.class.php';
//Cambiamos los valores de memoria y tiempo de ejecucion por que la creacion del pdf lo requiere
$sMemoriaInicial = ini_get('memory_limit');
$sTiempoLimiteInicial = ini_get('max_execution_time');
ini_set('memory_limit', $sMemoriaHtml2Pdf);
ini_set('max_execution_time', $sMaxTiempoHtml2Pdf);
$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
if (isset($iIdDoc) && ($iIdDoc != 'undefined')) {
    $sTabla = 'contenido_texto';
    $aCampos = array('contenido');
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos($aCampos);
    $oDb->construir_Tablas(array($sTabla));
    //    He cambiado el order para que los id que metemos en sesion vayan conforme a lo que metemos en los arboles
    //    $oDb->construir_Order(array('nodosuperior,nodo,id'));
    $oDb->construir_Where(array('id=' . $iIdDoc));
    $oDb->consulta();

    $aIterador = $oDb->coger_Fila();
    $pdf = new html2pdf(array('login' => $_SESSION['login'], 'pass' => $_SESSION['pass'], 'db' => $_SESSION['db'], 'doc' => $iIdDoc,
        'memoria' => $sMemoriaInicial, 'tiempo' => $sTiempoLimiteInicial));
    $pdf->createPDF($aIterador[0]);
} else if (isset($iIdProc) && ($iIdProc != 'undefined')) {
    require_once '../Procesar_Funciones_Comunes.php';
    require_once '../include.php';
    $anchura = $_SESSION['ancho'];
    $altura = $_SESSION['alto'];
    $lenguaje = $_SESSION['idioma'];
    $browser = $_SESSION['navegador'];
    $sistema = $_SESSION['sistema_operativo'];
    $cliente_usuario = $_SESSION['cliente'];


    $oEstilo = new Estilo_Pagina($anchura, $altura, $browser);
    $oPagina = new HTML_Page();

    $oPagina->addStyleDeclaration($oEstilo, 'text/css');

    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos(array('proceso', 'documento'));
    $oDb->construir_Tablas(array('contenido_procesos'));
    //    He cambiado el order para que los id que metemos en sesion vayan conforme a lo que metemos en los arboles
    $oDb->construir_Where(array('id=' . $iIdProc));
    $oDb->consulta();
    if ($aIterador = $oDb->coger_Fila()) {
        $oPagina->addBodyContent(procesa_Ver_Ficha_Proceso(null, array('numeroDeFila' => $aIterador[0]), null, false));
        $iIdDoc = $aIterador[1];
        //$oPagina->display();
        $pdf = new html2pdf(array('login' => $_SESSION['login'], 'pass' => $_SESSION['pass'], 'db' => $_SESSION['db'], 'doc' => $iIdDoc,
            'memoria' => $sMemoriaInicial, 'tiempo' => $sTiempoLimiteInicial));
        $pdf->createPDF($oPagina->toHtml());
    }
}
?>