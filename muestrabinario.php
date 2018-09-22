<?php
/**
* LICENSE see LICENSE.md file
 */

function resizeImage($img)
{
    //Get size for thumbnail
    $tamanno = 400;
    $width = imagesx($img);
    $height = imagesy($img);
    if ($width > $height) {
        $n_height = $height * ($tamanno / $width);
        $n_width = $tamanno;
    } else {
        $n_width = $width * ($tamanno / $height);
        $n_height = $tamanno;
    }

    $x = 0;
    $y = 0;
    if ($n_width < $tamanno) $x = round(($tamanno - $n_width) / 2);
    if ($n_height < $tamanno) $y = round(($tamanno - $n_height) / 2);

    $thumb = imagecreatetruecolor($tamanno, $tamanno);

    #Background colour fix by:
    #Ben Lancaster (benlanc@ster.me.uk)
    $bgcolor = imagecolorallocate($thumb, 255, 255, 255);
    imagefill($thumb, 0, 0, $bgcolor);

    if (function_exists("imagecopyresampled")) {
        if (!($result = @imagecopyresampled($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height))) {
            $result = imagecopyresized($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height);
        }
    } else {
        $result = imagecopyresized($thumb, $img, $x, $y, 0, 0, $n_width, $n_height, $width, $height);
    }
    return ($result) ? $thumb : false;
}

if (!isset($_SESSION)) {
    session_start();
}
require_once 'Manejador_Base_Datos.class.php';
require_once 'constantes.inc.php';
require 'etc/qnova.conf.php';
include_once 'include.php';

$iId = $_GET['id'];
$sTipo = $_GET['tipo'];
$sMemoriaInicial = ini_get('memory_limit');
$sTiempoLimiteInicial = ini_get('max_execution_time');
ini_set('memory_limit', $sMemoriaHtml2Pdf);
ini_set('max_execution_time', $sMaxTiempoHtml2Pdf);
$oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

if (($iId != null) || ($sTipo != "politica") || ($sTipo != "objetivos")) {
    switch ($sTipo) {
        case "imagent":
        case "imagen":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('flujogramas.archivo_oid', 'tipos_fichero.mime', 'flujogramas.size', 'tipos_fichero.extension'));
                $oBaseDatos->construir_Tablas(array('flujogramas', 'tipos_fichero'));
                $oBaseDatos->construir_Where(array('flujogramas.id=' . $iId, 'tipos_fichero.id=flujogramas.tipo_fichero'));
                break;
            }
        case "politica":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', 'documentos.codigo', 'documentos.nombre'));
                $oBaseDatos->construir_Tablas(array('contenido_binario', 'documentos', 'tipos_fichero'));
                $oBaseDatos->construir_Where(array('contenido_binario.id=documentos.id', 'documentos.tipo_documento=' . iIdPolitica,
                        'documentos.estado=' . iVigor, 'tipos_fichero.id=contenido_binario.tipo_fichero'
                    )
                );
                break;
            }
        case "objetivos":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', 'documentos.codigo', 'documentos.nombre'));
                $oBaseDatos->construir_Tablas(array('contenido_binario', 'documentos', 'tipos_fichero'));
                $oBaseDatos->construir_Where(array('contenido_binario.id=documentos.id', 'documentos.tipo_documento=' . iIdObjetivos,
                        'documentos.estado=' . iVigor, 'tipos_fichero.id=contenido_binario.tipo_fichero'
                    )
                );
                break;
            }
        case "ley":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documentos.id'));
                $oBaseDatos->construir_Tablas(array('legislacion_aplicable', 'documentos'));
                $oBaseDatos->construir_Where(array('legislacion_aplicable.id=' . $iId, 'legislacion_aplicable.id_ley=documentos.id'
                    )
                );
                $oBaseDatos->consulta();

                if ($aIterador = $oBaseDatos->coger_Fila(false)) {


                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', 'documentos.codigo', 'documentos.nombre'));
                    $oBaseDatos->construir_Tablas(array('contenido_binario', 'documentos', 'tipos_fichero', 'legislacion_aplicable'));
                    $oBaseDatos->construir_Where(array('contenido_binario.id=documentos.id', 'documentos.tipo_documento=' . iIdNormativa,
                            'documentos.estado=' . iVigor, 'tipos_fichero.id=contenido_binario.tipo_fichero',
                            'legislacion_aplicable.id_ley=' . $aIterador[0]
                        )
                    );
                } else {
                    echo $sErrorNoEncontrado;
                    die();
                }
                break;
            }
        case "adjuntoauditoria":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documento'));
                $oBaseDatos->construir_Tablas(array('auditores'));
                $oBaseDatos->construir_Where(array('id=' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila(false);
                if ($iIdDoc = $aIterador[0]) {

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', "'adjunto'"));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto', 'tipos_fichero'));
                    $oBaseDatos->construir_Where(array('contenido_adjunto.id=' . $iIdDoc,
                            'tipos_fichero.id=contenido_adjunto.tipo_fichero'
                        )
                    );
                } else {
                    echo $sErrorNoEncontrado;
                    die();
                }
                break;
            }
        case "objetivoseguimiento":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documento'));
                $oBaseDatos->construir_Tablas(array('seguimientos'));
                $oBaseDatos->construir_Where(array('id=' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila(false);
                if ($iIdDoc = $aIterador[0]) {

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', "'adjunto'"));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto', 'tipos_fichero'));
                    $oBaseDatos->construir_Where(array('contenido_adjunto.id=' . $iIdDoc,
                            'tipos_fichero.id=contenido_adjunto.tipo_fichero'
                        )
                    );
                } else {
                    echo $sErrorNoEncontrado;
                    die();
                }
                break;
            }
        case "adjuntohojafirmas":
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('hoja_firmas'));
                $oBaseDatos->construir_Tablas(array('cursos'));
                $oBaseDatos->construir_Where(array('id=' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila(false);
                if ($iIdDoc = $aIterador[0]) {

                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', "'adjunto'"));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto', 'tipos_fichero'));
                    $oBaseDatos->construir_Where(array('contenido_adjunto.id=' . $iIdDoc,
                            'tipos_fichero.id=contenido_adjunto.tipo_fichero'
                        )
                    );
                } else {
                    echo $sErrorNoEncontrado;
                    die();
                }
                break;
            }
        default:
            {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('archivo_oid', 'mime', 'size', 'tipos_fichero.extension', 'documentos.codigo', 'documentos.nombre'));
                $oBaseDatos->construir_Tablas(array('contenido_binario', 'documentos', 'tipos_fichero'));
                $oBaseDatos->construir_Where(array('contenido_binario.id=documentos.id', 'documentos.id=' . $iId,
                        'tipos_fichero.id=contenido_binario.tipo_fichero'
                    )
                );
                break;
            }
    }
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila(false)) {
        $sNombreArchivo = $aIterador[4] . "-" . $aIterador[5] . "." . $aIterador[3];
        Header('Content-Type: ' . $aIterador[1]);

        Header('Content-Disposition: attachment; filename="' . $sNombreArchivo . '"');
        $oBaseDatos->comienza_transaccion();
        $iBlob = $oBaseDatos->abrir_LOB($aIterador[0], "r");
        if ($sTipo == 'imagent') {
            $sAux = $oBaseDatos->leer_LOB_Imagen($iBlob, $aIterador[2]);
            $rImg = @imagecreatefromstring($sAux);
            $sCont = @resizeImage($rImg);
            switch ($aIterador[1]) {
                case 'image/jpeg':
                    {
                        imagejpeg($sCont);
                        break;
                    }

                case 'image/gif':
                    {
                        imagegif($sCont);
                        break;
                    }
                case 'image/png':
                    {
                        imagepng($sCont);
                        break;
                    }
                default:
                    echo "No existe el archivo pedido";
                    break;
            }
        } else {
            Header('Content-Length: ' . $aIterador[2]);
            $oBaseDatos->leer_LOB_Completo($iBlob);
        }

        $oBaseDatos->leer_LOB_Completo($iBlob);
        # Cierra el objeto
        $oBaseDatos->cerrar_LOB($iBlob);
        # Compromete la transaccin
        $oBaseDatos->termina_transaccion();
    }
} else {
    echo "No existe el archivo pedido";
}
ini_set('memory_limit', $sMemoriaInicial);
ini_set('max_execution_time', $sTiempoLimiteInicial);
