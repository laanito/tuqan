<?php
/**
 * Created on 21-nov-2005
 *
* LICENSE see LICENSE.md file
 *
 * To change the template for this generated file go to
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@islanda.es</u>
 * @version 1.0b
 * @link http://www.fpdf.org
 * @package FPDF
 *
 */


require_once 'GenPDF.inc';
require_once 'Manejador_Base_Datos.class.php';

class genera_pdf
{

    var $oBaseDatos;
    var $sDb;
    var $sLogin;
    var $sPass;
    var $fX = 0.0;
    var $fY = 0.0;
    var $aBloques = array();

    function __construct($sDb, $sLogin, $sPass, $sOrientacion = 'P', $sUnidades = 'mm', $sFormato = 'Letter')
    {
        $this->pdf = new PDF($sOrientacion, $sUnidades, $sFormato);
        $this->pdf->addFont('bold', 'Arial', 'B', 10);
        $this->pdf->addFont('bigbold', 'Arial', 'B', 12);
        $this->sDb = $sDb;
        $this->sLogin = $sLogin;
        $this->sPass = $sPass;
        $this->oBaseDatos = new Manejador_Base_Datos($this->sLogin, $this->sPass, $this->sDb);
    }

    function NuevoBloque($sNombre, $sTipo, $iOffset = 0, $sTitulo = "", $sAlineacionTitulo = 'C', $sFuenteTitulo = 'bold', $sBordeTitulo = 1, $aRellenoTitulo = array(240, 240, 220))
    {
        $this->oBaseDatos->iniciar_Consulta('SELECT');
        $this->aBloques[$sNombre]['tipo'] = $sTipo;
        $this->aBloques[$sNombre]['titulo'] = $sTitulo;
        $this->aBloques[$sNombre]['offset'] = $iOffset;
        $this->aBloques[$sNombre]['talineacion'] = $sAlineacionTitulo;
        $this->aBloques[$sNombre]['tfuente'] = $sFuenteTitulo;
        $this->aBloques[$sNombre]['tborde'] = $sBordeTitulo;
        $this->aBloques[$sNombre]['trelleno'] = $aRellenoTitulo;
    }

    function NuevoSelect($sNombre, $aNombres, $aCampos, $aAlineacion, $aBorde, $aRelleno)
    {
        $this->aBloques[$sNombre]['nombres'] = $aNombres;
        $this->aBloques[$sNombre]['campos'] = $aCampos;
        $this->aBloques[$sNombre]['alineacion'] = $aAlineacion;
        $this->aBloques[$sNombre]['borde'] = $aBorde;
        $this->aBloques[$sNombre]['relleno'] = $aRelleno;
    }

    function NuevoFrom($sNombre, $aFrom)
    {
        $this->aBloques[$sNombre]['tablas'] = $aFrom;
    }

    function NuevoWhere($sNombre, $aWhere)
    {
        $this->aBloques[$sNombre]['where'] = $aWhere;
    }

    function PonerBloque($sNombre)
    {
        switch ($this->aBloques[$sNombre]['tipo']) {
            case 'listado':
                {
                    $this->genera_bloque_listado($this->aBloques[$sNombre]);
                    break;
                }
            case 'registro':
                {
                    $this->genera_bloque_registro($this->aBloques[$sNombre]);
                    break;
                }

            default:
                break;
        }
    }

    function cabecera($aCabecera)
    {
        $this->pdf->setLogo($aCabecera['logo']['fichero'], $aCabecera['logo']['x'], $aCabecera['logo']['y'], $aCabecera['logo']['ancho']);
        $this->pdf->setTitulo($aCabecera['titulo']['texto'], $aCabecera['titulo']['x'], $aCabecera['titulo']['y'], $aCabecera['titulo']['ancho']);
        $this->pdf->setSubtitulo($aCabecera['subtitulo']['texto'], $aCabecera['subtitulo']['x'], $aCabecera['subtitulo']['y'], $aCabecera['subtitulo']['ancho']);
    }


    function genera_bloque_listado($aBloque)
    {
        /**
         * Iniciamos una consulta para sacar los valores de los campos dados
         */

        $this->pdf->beginBlock($aBloque['offset'], $aBloque['titulo']);
        $this->oBaseDatos->construir_Campos($aBloque['campos']);
        $this->oBaseDatos->construir_Tablas($aBloque['tablas']);
        if (array_key_exists('where', $aBloque))
            $this->oBaseDatos->construir_Where($aBloque['where']);
        if (array_key_exists('order', $aBloque))
            $this->oBaseDatos->construir_Order($aBloque['order']);
        if (array_key_exists('group', $aBloque))
            $this->oBaseDatos->construir_Group($aBloque['group']);
        $this->oBaseDatos->consulta();
        $j = 1;
        if (is_array($aBloque['nombres'])) {
            $ancho = $this->pdf->maxWidth / count($aBloque['nombres']);
            $i = 0;
            $this->pdf->ponFuente('bold');
            $this->fX = 0.0;
            foreach ($aBloque['nombres'] as $aRotulo) {
                $this->pdf->addField('nombre',
                    $this->fX,
                    0,
                    $ancho
                );
                $this->fX += $ancho;
                if (is_array($aBloque['trelleno'])) {
                    $this->pdf->SetFillColor($aBloque['trelleno'][0], $aBloque['trelleno'][1], $aBloque['trelleno'][2]);
                    $relleno = 1;
                } else
                    $relleno = 0;
                $this->pdf->printField($aBloque['nombres'][$i],
                    'nombre',
                    $aBloque['tfuente'],
                    $aBloque['tborde'],
                    $aBloque['talineacion'],
                    $relleno);
                $i++;
            }
        }
        while ($aIterador = $this->oBaseDatos->coger_Fila()) {
            //    $this->sHtml.=print_r($aIterador);
            if (is_array($aBloque['relleno'])) {
                $this->pdf->SetFillColor($aBloque['relleno'][0], $aBloque['relleno'][1], $aBloque['relleno'][2]);
                $relleno = 1;
            } else
                $relleno = 0;
            $i = 0;
            $this->fX = 0.0;
            foreach ($aIterador as $sValor) {
                $this->pdf->addField('campo',
                    $this->fX,
                    $this->pdf->lineHeight * $j,
                    $ancho);
                $this->pdf->printField($sValor,
                    'campo',
                    '',
                    $aBloque['borde'][$i],
                    $aBloque['alineacion'][$i],
                    $relleno);
                $this->fX += $ancho;
                $i++;
            }
            $j++;
        }//fin while
    }


    /*
     *
     *             BLOQUE REGISTRO
     *
     */


    function genera_bloque_registro($aBloque)
    {
        /**
         * Iniciamos una consulta para sacar los valores de los campos dados
         */
        $this->pdf->beginBlock($aBloque['offset'], $aBloque['titulo']);
        $this->oBaseDatos->iniciar_Consulta('SELECT');
        $this->oBaseDatos->construir_Campos($aBloque['campos']);
        $this->oBaseDatos->construir_Tablas($aBloque['tablas']);
        if (array_key_exists('where', $aBloque))
            $this->oBaseDatos->construir_Where($aBloque['where']);
        if (array_key_exists('order', $aBloque))
            $this->oBaseDatos->construir_Order($aBloque['order']);
        if (array_key_exists('group', $aBloque))
            $this->oBaseDatos->construir_Group($aBloque['group']);
        $this->oBaseDatos->consulta();
        $j = 1;
        $ancho = 0.0;
        $this->pdf->ponFuente('bigbold');
        if (is_array($aBloque['nombres'])) {
            foreach ($aBloque['nombres'] as $sNombre) {
                $nuevo_ancho = $this->pdf->GetStringWidth($sNombre);
                if ($nuevo_ancho > $ancho)
                    $ancho = $nuevo_ancho;
            }
        }
        $ancho += 10;
        while ($aIterador = $this->oBaseDatos->coger_Fila()) {
            $this->fY = 0.0;
            $i = 0;
            foreach ($aIterador as $llave => $sValor) {
                if (is_array($aBloque['nombres'])) {
                    $this->pdf->addField('nombre',
                        10,
                        $this->fY,
                        $ancho);
                    if (is_array($aBloque['trelleno'])) {
                        $this->pdf->SetFillColor($aBloque['trelleno'][0], $aBloque['trelleno'][1], $aBloque['trelleno'][2]);
                        $relleno = 1;
                    } else {
                        $relleno = 0;
                    }
                    $this->pdf->printField($aBloque['nombres'][$i],
                        'nombre',
                        $aBloque['tfuente'],
                        $aBloque['tborde'],
                        $aBloque['talineacion'],
                        $relleno);
                }
                if (is_array($aBloque['campos'])) {
                    if (is_array($aBloque['relleno'])) {
                        $this->pdf->SetFillColor($aBloque['relleno'][0], $aBloque['relleno'][1], $aBloque['relleno'][2]);
                        $relleno = 1;
                    } else
                        $relleno = 0;
                    $this->pdf->addField('campo',
                        10.0 + $ancho + 10.0,
                        $this->fY,
                        0);
                    $this->pdf->printField($sValor,
                        'campo',
                        '',
                        $aBloque['borde'][$i],
                        $aBloque['alineacion'][$i],
                        $relleno);
                }
                $this->fY += 2 * $this->pdf->lineHeight;
                $i++;
            }
            $j++;
        }//fin while
    }

    function salida()
    {
        return $this->pdf->Output('Hoja de pruebal', 'S');
        //return $this->sHtml;
    }
}

/*
$aCabecera['logo']=array('fichero'=>'/home/lamigo/workspace/UserFiles/Image/logo.png','x'=>150,'y'=>10,'ancho'=>50);
$aCabecera['titulo']=array('texto'=>'Nombre de empresa','x'=>10,'y'=>10,'ancho'=>140);
$aCabecera['subtitulo']=array('texto'=>'Nombre de ficha','x'=>10,'y'=>10,'ancho'=>0);

$aPrueba['offset']=10;
$aPrueba['cuadro']='dentro';
$aPrueba['nombre']['x']=array(10,10,10,10,10,10);
$aPrueba['nombre']['y']=array(10,20,30,40,50,60);
$aPrueba['nombre']['borde']=array(0,0,0,0,0,0);
$aPrueba['nombre']['alineacion']=array('L','L','L','L','L','L');

$aPrueba['campo']['x']=array(60,60,60,60,60,60);
$aPrueba['campo']['y']=array(10,20,30,40,50,60);
$aPrueba['campo']['borde']=array(0,0,0,0,0,0);
$aPrueba['campo']['alineacion']=array('L','L','L','L','L','L');

$aPrueba['nombres']=array('Nombre','Primer Apellido','Segundo Apellido','NIF','telefono','�rea');
$aPrueba['campos']=array('us.nombre','us.primer_apellido','us.segundo_apellido','us.nif','us.telefono','ar.nombre');
$aPrueba['tablas']=array('usuarios us','areas ar');
$aPrueba['where']=array('ar.id=us.area and (us.id=2)');

$aPrueba2['offset']=10;
$aPrueba2['nombre']['x']=array(10,40,70,100,130,160);
$aPrueba2['nombre']['y']=array(10,10,10,10,10,10);
$aPrueba2['nombre']['borde']=array(1,1,1,1,1,1);
$aPrueba2['nombre']['alineacion']=array('C','C','C','C','C','C');
$aPrueba2['nombre']['fuente']='bold';
$aPrueba2['nombre']['relleno']=array(240,240,240);

$aPrueba2['campo']['x']=array(10,40,70,100,130,160);
$aPrueba2['campo']['y']=array(20,20,20,20,20,20);


$aBloques=array('Datos de un usuario'=>$aPrueba,'Datos de otro usuario' => $aPrueba2);
$pepe=new genera_pdf('qnova_pruebas','qnova','qnova');
$pepe->cabecera($aCabecera);
$pepe->pdf->SetAutoPageBreak(false);
$pepe->pdf->Open();
$pepe->pdf->AddPage();
$pepe->NuevoBloque('listado','registro',0,'Listado de Usuarios','L');
$pepe->NuevoSelect('listado',array('Nombre','Primer Apellido','Segundo Apellido','NIF','telefono','�rea'),
                    array('us.nombre','us.primer_apellido','us.segundo_apellido','us.nif','us.telefono','ar.nombre'),
                    array('L','L','L','R','R','L'),array(1,1,1,1,1,1), array(230,230,230));
$pepe->NuevoFrom('listado',array('usuarios us','areas ar'));
$pepe->NuevoWhere('listado',array('ar.id=us.area and us.id=2'));
$pepe->PonerBloque('listado');


$sHtml=$pepe->salida();

Header('Content-Type: application/pdf');
Header('Content-Length: '.strlen($sHtml));
echo $sHtml;*/

