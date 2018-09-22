<?php
/**
* LICENSE see LICENSE.md file
 */
$sServidorEtc = "localhost";
$iPuertoEtc = 5432;
$sTipoBdEtc = 'pgsql';
$sLoginEtc = 'qnova';
$sPassEtc = 'ZTBlMWI2YTBlYmnDeYFE';
$sDbEtc = 'qnova';
$sMemoriaHtml2Pdf = '128M';
$sMaxTiempoHtml2Pdf = '240';
$sFormulaMatrizAmbiental= '(3*aspectos.magnitud+2*aspectos.gravedad)*aspectos.frecuencia';
$iValorNoSignificativo = 20;
$iValorSignificativoModerado = 50;
$iValorSignificativoCritico = 60;
$dbEncoding = 'UNICODE';
$apacheEncoding = 'UTF-8';

$iValorRiesgoBajo = 1;
$iValorRiesgoMedio = 3;
$iValorRiesgoAlto = 6;

//Idiomas, posibles valores: 1=>'castellano',2=>'catalan','ingles'
$sIdioma = '1';
$sIdiomaInicial = "castellano";
$sLogo = 'logo-islanda.png';
$sPathUploadEditor = "/var/www/html/qnova/userfiles/";
$sPathUploadURL = "/var/www/html/qnova/userfiles/";
$sPathXls = "/tmp/";

$aCharset = array('ISO-8859-15' => 'LATIN1',
    'ISO-8859-1' => 'LATIN1',
    'utf-8' => 'UNICODE'
);

