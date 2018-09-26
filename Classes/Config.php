<?php
/**
 * Class storing all config variables
 */

namespace Tuqan\Classes;


class Config
{
    public static $sServidorEtc;
    public static $iPuertoEtc;
    public static $sTipoBdEtc;
    public static $sLoginEtc;
    public static $sPassEtc;
    public static $sDbEtc;
    public static $sMemoriaHtml2Pdf;
    public static $sMaxTiempoHtml2Pdf;
    public static $sFormulaMatrizAmbiental;
    public static $iValorNoSignificativo;
    public static $iValorSignificativoModerado;
    public static $iValorSignificativoCritico;
    public static $dbEncoding;
    public static $apacheEncoding;

    public static $iValorRiesgoBajo;
    public static $iValorRiesgoMedio;
    public static $iValorRiesgoAlto;

//Idiomas, posibles valores: 1=>'castellano',2=>'catalan','ingles'
    public static $sIdioma;
    public static $sIdiomaInicial;
    public static $sLogo;
    public static $sPathUploadEditor;
    public static $sPathUploadURL;
    public static $sPathXls;

    public static $aCharset;

    public static $base_path;

    public static $template_path;

    public static $cache_path;

    private static $initialized = false;

    /**
     * Config constructor.
     */
public static function initialize()
{
    if(self::$initialized) {
        return;
    }
    self::$sServidorEtc = "localhost";
    self::$iPuertoEtc = 5432;
    self::$sTipoBdEtc = 'pgsql';
    self::$sLoginEtc = 'qnova';
    self::$sPassEtc = 'ZTBlMWI2YTBlYmnDeYFE';
    self::$sDbEtc = 'qnova';
    self::$sMemoriaHtml2Pdf = '128M';
    self::$sMaxTiempoHtml2Pdf = '240';
    self::$sFormulaMatrizAmbiental= '(3*aspectos.magnitud+2*aspectos.gravedad)*aspectos.frecuencia';
    self::$iValorNoSignificativo = 20;
    self::$iValorSignificativoModerado = 50;
    self::$iValorSignificativoCritico = 60;
    self::$dbEncoding = 'UNICODE';
    self::$apacheEncoding = 'UTF-8';

    self::$iValorRiesgoBajo = 1;
    self::$iValorRiesgoMedio = 3;
    self::$iValorRiesgoAlto = 6;

//Idiomas, posibles valores: 1=>'castellano',2=>'catalan','ingles'
    self::$sIdioma = '1';
    self::$sIdiomaInicial = "castellano";
    self::$sLogo = 'logo-islanda.png';
    self::$sPathUploadEditor = $_SERVER['DOCUMENT_ROOT'].self::$base_path."/userfiles/";
    self::$sPathUploadURL = $_SERVER['DOCUMENT_ROOT'].self::$base_path."/userfiles/";
    self::$sPathXls = "/tmp/";

    self::$aCharset = array('ISO-8859-15' => 'LATIN1',
        'ISO-8859-1' => 'LATIN1',
        'utf-8' => 'UNICODE'
    );
    self::$base_path='';

    self::$template_path = $_SERVER['DOCUMENT_ROOT'].self::$base_path.'/templates/';

    self::$cache_path = self::$template_path.'cache/';
    }
}
