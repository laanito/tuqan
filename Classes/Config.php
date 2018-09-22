<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 01/03/2018
 * Time: 11:01
 */

namespace Tuqan\Classes;


class Config
{
    public $sServidorEtc;
    public $iPuertoEtc;
    public $sTipoBdEtc;
    public $sLoginEtc;
    public $sPassEtc;
    public $sDbEtc;
    public $sMemoriaHtml2Pdf;
    public $sMaxTiempoHtml2Pdf;
    public $sFormulaMatrizAmbiental;
    public $iValorNoSignificativo;
    public $iValorSignificativoModerado;
    public $iValorSignificativoCritico;
    public $dbEncoding;
    public $apacheEncoding;

    public $iValorRiesgoBajo;
    public $iValorRiesgoMedio;
    public $iValorRiesgoAlto;

//Idiomas, posibles valores: 1=>'castellano',2=>'catalan','ingles'
    public $sIdioma;
    public $sIdiomaInicial;
    public $sLogo;
    public $sPathUploadEditor;
    public $sPathUploadURL;
    public $sPathXls;

    public $aCharset;

    public $base_path;

    public $template_path;

    public $cache_path;
    
public function __construct()
{
    $this->sServidorEtc = "localhost";
    $this->iPuertoEtc = 5432;
    $this->sTipoBdEtc = 'pgsql';
    $this->sLoginEtc = 'qnova';
    $this->sPassEtc = 'ZTBlMWI2YTBlYmnDeYFE';
    $this->sDbEtc = 'qnova';
    $this->sMemoriaHtml2Pdf = '128M';
    $this->sMaxTiempoHtml2Pdf = '240';
    $this->sFormulaMatrizAmbiental= '(3*aspectos.magnitud+2*aspectos.gravedad)*aspectos.frecuencia';
    $this->iValorNoSignificativo = 20;
    $this->iValorSignificativoModerado = 50;
    $this->iValorSignificativoCritico = 60;
    $this->dbEncoding = 'UNICODE';
    $this->apacheEncoding = 'UTF-8';

    $this->iValorRiesgoBajo = 1;
    $this->iValorRiesgoMedio = 3;
    $this->iValorRiesgoAlto = 6;

//Idiomas, posibles valores: 1=>'castellano',2=>'catalan','ingles'
    $this->sIdioma = '1';
    $this->sIdiomaInicial = "castellano";
    $this->sLogo = 'logo-islanda.png';
    $this->sPathUploadEditor = "/var/www/html/qnova/userfiles/";
    $this->sPathUploadURL = "/var/www/html/qnova/userfiles/";
    $this->sPathXls = "/tmp/";

    $this->aCharset = array('ISO-8859-15' => 'LATIN1',
        'ISO-8859-1' => 'LATIN1',
        'utf-8' => 'UNICODE'
    );
    $this->base_path='/qnova';

    $this->template_path = $_SERVER['DOCUMENT_ROOT'].$this->base_path.'/templates/';

    $this->cache_path = $this->template_path.'cache/';
    }
}