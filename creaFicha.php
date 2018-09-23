<?php

/**
* LICENSE see LICENSE.md file
 *
 *

 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 *
 */

require 'fpdf153/fpdf.php';

class PDF extends FPDF
{
    /**
     * Esta funcion nos pone la x,y, la fuente y la celda
     * @param int $iX posicion donde ponemos la X
     * @param int $iY posicion donde ponemos la Y
     * @param $aCell array aqui traemos los datos del cell
     * @param $aFuente array aqui ponemos los datos de la fuente
     */
    function pon_Caja($iX, $iY, $aCell, $aFuente, $aRelleno)
    {
        $this->SetXY($iX, $iY);
        $this->SetFont($aFuente['familia'], $aFuente['estilo'], $aFuente['size']);
        $sLongitud = $this->GetStringWidth($aCell['texto']);
        $i = 1;
        while ($sLongitud > $aCell['ancho']) {
            $this->SetFont($aFuente['familia'], $aFuente['estilo'], $aFuente['size'] - $i);
            $sLongitud = $this->GetStringWidth($aCell['texto']);
            $i++;
        }
        $this->setFillColor($aRelleno['rojo']);
        $this->cell($aCell['ancho'], $aCell['alto'], $aCell['texto'], $aCell['borde'],
            $aCell['linea'], $aCell['alineacion'], $aCell['relleno']);
    }

    function pon_MultiCaja($iX, $iY, $aCell, $aFuente, $aRelleno)
    {
        $this->SetXY($iX, $iY);
        $this->SetFont($aFuente['familia'], $aFuente['estilo'], $aFuente['size']);
        $this->setFillColor($aRelleno['rojo']);
        $this->MultiCell($aCell['ancho'], $aCell['alto'], $aCell['texto'], $aCell['borde'],
            $aCell['alineacion'], $aCell['relleno']);
    }
}

/**
 * Esta funcion es la que nos crea las fichas de personal
 */
function ficha_personal($iIdFicha)
{
//Primero vamos a sacar los datos necesarios
    require_once 'Manejador_Base_Datos.class.php';
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nombre', 'apellidos', 'to_char(fecha_nac, \'DD/MM/YYYY\')', 'localidad', 'provincia',
        'direccion', 'telefono', 'poblacion', 'provincia_residencia', 'observaciones', 'vehiculo_propio'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_datos_personales'));
    $oBaseDatos->construir_Where(array('id=' . $iIdFicha));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sNombre = $aIterador[0];
        $sApellidos = $aIterador[1];
        $sFecha = $aIterador[2];
        $sLocalidad = $aIterador[3];
        $sProvincia = $aIterador[4];
        $sDireccion = $aIterador[5];
        $sTelefono = $aIterador[6];
        $sPoblacion = $aIterador[7];
        $sProvinciaRes = $aIterador[8];
        $sObservaciones = $aIterador[9];
        $sVehiculoPropio = $aIterador[10];

    }

    $pdf = new PDF();
//Primera Pagina Ficha personal

    $pdf->AddPage();
//Titulo
    $pdf->pon_Caja(20, 10, array('ancho' => 169, 'alto' => 11, 'texto' => gettext('sCFFICHADEPERSONAL'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );

//1 Caja Datos Personales
//Titulo
    $pdf->pon_Caja(20, 24, array('ancho' => 8, 'alto' => 45, 'texto' => '', 'borde' => 1, 'linea' => 0,
        'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//1 linea
//Nombre
    $pdf->pon_Caja(28, 24, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFNombre'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 24, array('ancho' => 67, 'alto' => 9, 'texto' => $sNombre,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Apellidos
    $pdf->pon_Caja(96, 24, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFApellidos'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(95, 24, array('ancho' => 63, 'alto' => 9, 'texto' => $sApellidos,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Nulo
    $pdf->pon_Caja(158, 24, array('ancho' => 31, 'alto' => 9, 'texto' => '', 'borde' => 1,
        'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Nacimiento
    $pdf->pon_Caja(28, 33, array('ancho' => 22, 'alto' => 3, 'texto' => gettext('sCFFechaNac'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 33, array('ancho' => 67, 'alto' => 9, 'texto' => $sFecha,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Localidad
    $pdf->pon_Caja(95, 33, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFLocalidad'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(95, 33, array('ancho' => 42, 'alto' => 9, 'texto' => $sLocalidad,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Provincia
    $pdf->pon_Caja(137, 33, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFProvincia'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(137, 33, array('ancho' => 21, 'alto' => 9, 'texto' => $sProvincia,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Foto
    $pdf->pon_Caja(158, 33, array('ancho' => 31, 'alto' => 36, 'texto' => gettext('sCFFOTO'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 15),
        array('rojo' => 220)
    );

//Segunda linea
//Direccion
    $pdf->pon_Caja(28, 42, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFDireccion') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 42, array('ancho' => 82, 'alto' => 9, 'texto' => $sDireccion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Telefono
    $pdf->pon_Caja(110, 42, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFTelefono') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(110, 42, array('ancho' => 48, 'alto' => 9, 'texto' => $sTelefono,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Tercera linea
//Poblacion
    $pdf->pon_Caja(28, 51, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFPoblacion') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 51, array('ancho' => 82, 'alto' => 9, 'texto' => $sPoblacion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Provincia
    $pdf->pon_Caja(110, 51, array('ancho' => 12, 'alto' => 3, 'texto' => gettext('sCFProvinciaRes') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(110, 51, array('ancho' => 48, 'alto' => 9, 'texto' => $sProvinciaRes,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Cuarta linea
//Observaciones
    $pdf->pon_Caja(28, 60, array('ancho' => 16, 'alto' => 3, 'texto' => gettext('sCFObservaciones') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 60, array('ancho' => 82, 'alto' => 9, 'texto' => $sObservaciones,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Vehiculo Propio
    $pdf->pon_Caja(110, 60, array('ancho' => 18, 'alto' => 3, 'texto' => gettext('sCFVehiculoprop') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(110, 60, array('ancho' => 48, 'alto' => 9, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    if ($sVehiculoPropio == 't') {
        $pdf->pon_Caja(135, 63, array('ancho' => 4, 'alto' => 4, 'texto' => 'X',
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );
    } else {
        $pdf->pon_Caja(135, 63, array('ancho' => 4, 'alto' => 4, 'texto' => '',
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );
    }

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('titulos_oficiales', 'fecha_fin', 'centro'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_formacion_academica'));
    $oBaseDatos->construir_Where(array('id=' . $iIdFicha));
    $oBaseDatos->consulta();

    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sTitulos = $aIterador[0];
        $sFechaFin = $aIterador[1];
        $sCentro = $aIterador[2];
    }

//Segunda Caja Formacion Academica
//Titulo
    $pdf->pon_Caja(20, 71, array('ancho' => 8, 'alto' => 26, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//1� linea
//Titulos Oficiales
    $pdf->pon_Caja(28, 71, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFTitulosOficiales') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 71, array('ancho' => 161, 'alto' => 8, 'texto' => $sTitulos,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Fecha Fin
    $pdf->pon_Caja(28, 79, array('ancho' => 25, 'alto' => 3, 'texto' => gettext('sCFFechaFin') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 79, array('ancho' => 161, 'alto' => 9, 'texto' => $sFechaFin,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Centro
    $pdf->pon_Caja(28, 88, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFCentro') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 88, array('ancho' => 161, 'alto' => 9, 'texto' => $sCentro,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );


    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('empresa', 'fecha_incorporacion', 'perfil', 'departamento'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_incorporacion'));
    $oBaseDatos->construir_Where(array('id=' . $iIdFicha));
    $oBaseDatos->consulta();

    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sEmpresa = $aIterador[0];
        $sFechaInc = $aIterador[1];
        $sPerfil = $aIterador[2];
        $sDepartamento = $aIterador[3];
    }


//Tercera Caja Incorporacion
//Titulo
    $pdf->pon_Caja(20, 99, array('ancho' => 8, 'alto' => 32, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Primera linea
//Empresa
    $pdf->pon_Caja(28, 99, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFEmpresa') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 99, array('ancho' => 88, 'alto' => 8, 'texto' => $sEmpresa,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Fecha Incorporacion
    $pdf->pon_Caja(116, 99, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFFechaIncorp') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(116, 99, array('ancho' => 73, 'alto' => 8, 'texto' => $sFechaInc,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Segunda Linea
//Perfil
    $pdf->pon_Caja(28, 107, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFPerfil') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 107, array('ancho' => 88, 'alto' => 8, 'texto' => $sPerfil,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Departamento
    $pdf->pon_Caja(116, 107, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFDepartamento') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(116, 107, array('ancho' => 73, 'alto' => 8, 'texto' => $sDepartamento,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Tercera Linea
//Fecha Cambio Perfil

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('to_char(fecha_cambio_perfil, \'DD/MM/YYYY\')'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_cambio_perfil'));
    $oBaseDatos->construir_Where(array('ficha=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();


    $pdf->pon_Caja(28, 115, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFFechacambioperfil') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 115, array('ancho' => 77, 'alto' => 16, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Celdas para cambio perfil
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(66, 115, array('ancho' => 39, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(66, 119, array('ancho' => 39, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(66, 123, array('ancho' => 39, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(66, 127, array('ancho' => 39, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

//Fecha Cambio departamento

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('to_char(fecha_cambio_departamento, \'DD/MM/YYYY\')'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_cambio_departamento'));
    $oBaseDatos->construir_Where(array('ficha=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();

    $pdf->pon_Caja(105, 115, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFFechacambiodep') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(105, 115, array('ancho' => 84, 'alto' => 16, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Celdas para cambio departamento
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(147, 115, array('ancho' => 42, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(147, 119, array('ancho' => 42, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(147, 123, array('ancho' => 42, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sPerfil = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sPerfil = $aIterador[0];
    }
    $pdf->pon_Caja(147, 127, array('ancho' => 42, 'alto' => 4, 'texto' => $sPerfil,
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

//Cuarta Caja Preformacion
//Titulos
    $pdf->pon_Caja(20, 133, array('ancho' => 8, 'alto' => 24, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(103, 133, array('ancho' => 9, 'alto' => 24, 'texto' => '', 'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('curso1', 'curso2', 'curso3'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_preformacion'));
    $oBaseDatos->construir_Where(array('id=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sCurso1 = $aIterador[0];
        $sCurso2 = $aIterador[1];
        $sCurso3 = $aIterador[2];
    }

//Primera Linea
//Curso Calidad
    $pdf->pon_Caja(28, 133, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFCurso') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 133, array('ancho' => 75, 'alto' => 8, 'texto' => $sCurso1,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Ingles

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nivel_ingles', 'nivel_frances', 'otros', 'nivel_otros'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_idiomas'));
    $oBaseDatos->construir_Where(array('id=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sNivelIngles = $aIterador[0];
        $sNivelFrances = $aIterador[1];
        $sOtros = $aIterador[2];
        $sNivelOtros = $aIterador[3];
    }

    $pdf->pon_Caja(112, 133, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFIngles') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(112, 133, array('ancho' => 38, 'alto' => 8, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $sMarcado = '';
    if ($sNivelIngles != null) {
        $sMarcado = 'X';
    }
    $pdf->pon_Caja(130, 135, array('ancho' => 4, 'alto' => 4, 'texto' => $sMarcado,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Nivel ingles
    $pdf->pon_Caja(150, 133, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFNivel') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(150, 133, array('ancho' => 39, 'alto' => 8, 'texto' => $sNivelIngles,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Curso XXXX
    $pdf->pon_Caja(28, 141, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFCurso') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 141, array('ancho' => 75, 'alto' => 8, 'texto' => $sCurso2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Frances
    $sMarcado = '';
    if ($sNivelFrances != null) {
        $sMarcado = 'X';
    }
    $pdf->pon_Caja(112, 141, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFFrances') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(112, 141, array('ancho' => 38, 'alto' => 8, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(130, 143, array('ancho' => 4, 'alto' => 4, 'texto' => $sMarcado,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Nivel frances
    $pdf->pon_Caja(150, 141, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFNivel') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(150, 141, array('ancho' => 39, 'alto' => 8, 'texto' => $sNivelFrances,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Curso
    $pdf->pon_Caja(28, 149, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFCurso') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(28, 149, array('ancho' => 75, 'alto' => 8, 'texto' => $sCurso3,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Otros
    $pdf->pon_Caja(112, 149, array('ancho' => 10, 'alto' => 3, 'texto' => ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(112, 149, array('ancho' => 38, 'alto' => 8, 'texto' => $sOtros,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
//Nivel otros
    $pdf->pon_Caja(150, 149, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFNivel') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(150, 149, array('ancho' => 39, 'alto' => 8, 'texto' => $sNivelOtros,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

//Quinto Experiencia Laboral
//Titulos
    $pdf->pon_Caja(20, 159, array('ancho' => 8, 'alto' => 92, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Cabeceras
    $pdf->pon_Caja(28, 159, array('ancho' => 62, 'alto' => 8, 'texto' => gettext('sCFEmpresa'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(90, 159, array('ancho' => 34, 'alto' => 8, 'texto' => gettext('sCFPuestocargo'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(124, 159, array('ancho' => 32, 'alto' => 8, 'texto' => gettext('sCFFechaInicio'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(156, 159, array('ancho' => 33, 'alto' => 8, 'texto' => gettext('sCFFechaFin'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('empresa', 'puesto', 'to_char(fecha_inicio, \'DD/MM/YYYY\')',
        'to_char(fecha_fin, \'DD/MM/YYYY\')'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_experiencia_laboral'));
    $oBaseDatos->construir_Where(array('ficha=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();
    for ($iContador = 0; $iContador < 12; $iContador++) {
        $sEmpresa = '';
        $sPuesto = '';
        $sFechaInicio = '';
        $sFechaFin = '';
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sEmpresa = $aIterador[0];
            $sPuesto = $aIterador[1];
            $sFechaInicio = $aIterador[2];
            $sFechaFin = $aIterador[3];
        }
        $pdf->pon_Caja(28, 167 + $iContador * 7, array('ancho' => 62, 'alto' => 7, 'texto' => $sEmpresa,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 7),
            array('rojo' => 220)
        );
        $pdf->pon_Caja(90, 167 + $iContador * 7, array('ancho' => 34, 'alto' => 7, 'texto' => $sPuesto,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 7),
            array('rojo' => 220)
        );
        $pdf->pon_Caja(124, 167 + $iContador * 7, array('ancho' => 32, 'alto' => 7, 'texto' => $sFechaInicio,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 7),
            array('rojo' => 220)
        );
        $pdf->pon_Caja(156, 167 + $iContador * 7, array('ancho' => 33, 'alto' => 7, 'texto' => $sFechaFin,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 7),
            array('rojo' => 220)
        );
    }

//
//Segunda Pagina Ficha Personal
//

    $pdf->AddPage();

//Titulo
    $pdf->pon_Caja(20, 10, array('ancho' => 169, 'alto' => 5, 'texto' => gettext('sCFFormacionComp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 14),
        array('rojo' => 220)
    );

//1� Caja Formacion Tecnica
//Titulo
    $pdf->pon_Caja(20, 17, array('ancho' => 8, 'alto' => 103, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//1� linea
//Cabeceras
    $pdf->pon_Caja(28, 17, array('ancho' => 60, 'alto' => 7, 'texto' => gettext('sCFNombreCurso'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(88, 17, array('ancho' => 40, 'alto' => 7, 'texto' => gettext('sCFLugarimparticion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(128, 17, array('ancho' => 38, 'alto' => 3, 'texto' => gettext('sCFDuracion'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(128, 20, array('ancho' => 19, 'alto' => 4, 'texto' => gettext('sCFDesde'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(147, 20, array('ancho' => 19, 'alto' => 4, 'texto' => gettext('sCFHasta'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(166, 17, array('ancho' => 23, 'alto' => 7, 'texto' => gettext('sCFPeriodotiempo') . '.',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nombre', 'lugar', 'to_char(desde, \'DD/MM/YYYY\')',
        'to_char(hasta, \'DD/MM/YYYY\')', 'periodo'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_formacion_tecnica'));
    $oBaseDatos->construir_Where(array('ficha=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();
    for ($iContador = 0; $iContador < 12; $iContador++) {
        $sNombre = '';
        $sLugar = '';
        $sFechaInicio = '';
        $sFechaFin = '';
        $sPeriodo = '';
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sNombre = $aIterador[0];
            $sLugar = $aIterador[1];
            $sFechaInicio = $aIterador[2];
            $sFechaFin = $aIterador[3];
            $sPeriodo = $aIterador[4];
        }
        $pdf->pon_Caja(28, 24 + $iContador * 8, array('ancho' => 60, 'alto' => 8, 'texto' => $sNombre,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(88, 24 + $iContador * 8, array('ancho' => 40, 'alto' => 8, 'texto' => $sLugar,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(128, 24 + $iContador * 8, array('ancho' => 19, 'alto' => 8, 'texto' => $sFechaInicio,
            'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 6),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(147, 24 + $iContador * 8, array('ancho' => 19, 'alto' => 8, 'texto' => $sFechaFin,
            'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 6),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(166, 24 + $iContador * 8, array('ancho' => 23, 'alto' => 8, 'texto' => $sPeriodo,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 8),
            array('rojo' => 220)
        );
    }
//Segunda Caja Formacion Tecnica
//Titulo
    $pdf->pon_Caja(20, 122, array('ancho' => 8, 'alto' => 103, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Seguna linea
//Cabeceras
    $pdf->pon_Caja(28, 122, array('ancho' => 60, 'alto' => 7, 'texto' => gettext('sCFNombreCurso'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(88, 122, array('ancho' => 40, 'alto' => 7, 'texto' => gettext('sCFLugarimparticion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(128, 122, array('ancho' => 38, 'alto' => 3, 'texto' => gettext('sCFDuracion'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(128, 125, array('ancho' => 19, 'alto' => 4, 'texto' => gettext('sCFDesde'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(147, 125, array('ancho' => 19, 'alto' => 4, 'texto' => gettext('sCFHasta'),
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(166, 122, array('ancho' => 23, 'alto' => 7, 'texto' => gettext('sCFPeriodotiempo') . '.',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );

//
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nombre', 'lugar', 'to_char(inicio, \'DD/MM/YYYY\')',
        'to_char(fin, \'DD/MM/YYYY\')', 'periodo'));
    $oBaseDatos->construir_Tablas(array('ficha_personal_otros_cursos'));
    $oBaseDatos->construir_Where(array('ficha=' . $iIdFicha));
    $oBaseDatos->construir_Order(array('id'));
    $oBaseDatos->consulta();
    for ($iContador = 0; $iContador < 12; $iContador++) {
        $sNombre = '';
        $sLugar = '';
        $sFechaInicio = '';
        $sFechaFin = '';
        $sPeriodo = '';
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sNombre = $aIterador[0];
            $sLugar = $aIterador[1];
            $sFechaInicio = $aIterador[2];
            $sFechaFin = $aIterador[3];
            $sPeriodo = $aIterador[4];
        }
        $pdf->pon_Caja(28, 129 + $iContador * 8, array('ancho' => 60, 'alto' => 8, 'texto' => $sNombre,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(88, 129 + $iContador * 8, array('ancho' => 40, 'alto' => 8, 'texto' => $sLugar,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(128, 129 + $iContador * 8, array('ancho' => 19, 'alto' => 8, 'texto' => $sFechaInicio,
            'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 6),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(147, 129 + $iContador * 8, array('ancho' => 19, 'alto' => 8, 'texto' => $sFechaFin,
            'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 6),
            array('rojo' => 220)
        );

        $pdf->pon_Caja(166, 129 + $iContador * 8, array('ancho' => 23, 'alto' => 8, 'texto' => $sPeriodo,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 8),
            array('rojo' => 220)
        );
    }
    $pdf->Output();
}


/**
 * Esta funcion es la que nos crea el pdf del requisito del puesto
 */
function req_puesto($iIdReq)
{
    require_once 'Manejador_Base_Datos.class.php';
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nombre_puesto', 'categoria', 'depende_de', 'area', 'requiere_ant',
        'antiguedad', 'observaciones'));
    $oBaseDatos->construir_Tablas(array('requisitos_puesto_datos_puesto'));
    $oBaseDatos->construir_Where(array('id=' . $iIdReq));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sNombre = $aIterador[0];
        $sCategoria = $aIterador[1];
        $sDepende = $aIterador[2];
        $sArea = $aIterador[3];
        $sRequiereAnt = $aIterador[4];
        $sAntiguedad = $aIterador[5];
        $sObservaciones = $aIterador[6];
    }
    $pdf = new PDF();

    $pdf->AddPage();
//Titulo
    $pdf->pon_Caja(20, 10, array('ancho' => 169, 'alto' => 11, 'texto' => gettext('sCFRequisitos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );

//Primera Caja Datos Puesto
//Titulo
    $pdf->pon_Caja(20, 23, array('ancho' => 11, 'alto' => 27, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Primera linea
//Nombre puesto trabajo
    $pdf->pon_Caja(31, 23, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sCFNombrepuestotrab'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(31, 23, array('ancho' => 50, 'alto' => 9, 'texto' => $sNombre,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Categoria
    $pdf->pon_Caja(81, 23, array('ancho' => 10, 'alto' => 3, 'texto' => gettext('sCFCategoria'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 23, array('ancho' => 108, 'alto' => 9, 'texto' => $sCategoria,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//2� linea
//Depende de
    $pdf->pon_Caja(31, 32, array('ancho' => 25, 'alto' => 3, 'texto' => gettext('sCFDependede') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(31, 32, array('ancho' => 50, 'alto' => 9, 'texto' => $sDepende,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Area
    $pdf->pon_Caja(81, 32, array('ancho' => 10, 'alto' => 4, 'texto' => gettext('sCFArea') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 32, array('ancho' => 108, 'alto' => 9, 'texto' => $sArea,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Tercera linea
//Antiguedad
    if ($sRequiereAnt == 't') {
        $sMarcadoSi = "X";
        $sMarcadoNo = "";
    } else {
        $sMarcadoNo = "X";
        $sMarcadoSi = "";
    }
    $pdf->pon_Caja(31, 41, array('ancho' => 25, 'alto' => 3, 'texto' => gettext('sCFAntiguedad') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(31, 41, array('ancho' => 50, 'alto' => 9, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(46, 43, array('ancho' => 7, 'alto' => 9, 'texto' => gettext('sCFSi'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(53, 45, array('ancho' => 4, 'alto' => 4, 'texto' => $sMarcadoSi,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(57, 43, array('ancho' => 7, 'alto' => 9, 'texto' => gettext('sCFNo'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(64, 45, array('ancho' => 4, 'alto' => 4, 'texto' => $sMarcadoNo,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Número de Años
    $pdf->pon_Caja(81, 41, array('ancho' => 10, 'alto' => 4, 'texto' => gettext('sCFNYears') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 41, array('ancho' => 30, 'alto' => 9, 'texto' => $sAntiguedad,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Observaciones
    $pdf->pon_Caja(111, 41, array('ancho' => 25, 'alto' => 4, 'texto' => gettext('sCFObservaciones') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(111, 41, array('ancho' => 78, 'alto' => 9, 'texto' => $sObservaciones,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Segunda Formacion especifica requerida
//Titulo
    $pdf->pon_Caja(20, 52, array('ancho' => 11, 'alto' => 38, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//Primera linea
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('titulos_oficiales'));
    $oBaseDatos->construir_Tablas(array('requisitos_puesto_formacion'));
    $oBaseDatos->construir_Where(array('id=' . $iIdReq));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sTitulos = $aIterador[0];
    }
//Titulos oficiales
    $pdf->pon_Caja(31, 52, array('ancho' => 25, 'alto' => 3, 'texto' => gettext('sCFTitulosOficiales') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(31, 52, array('ancho' => 158, 'alto' => 8, 'texto' => $sTitulos,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Segunda linea
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('formacion_tecnica', 'opcional', 'horas'));
    $oBaseDatos->construir_Tablas(array('requisitos_puesto_ft'));
    $oBaseDatos->construir_Where(array('requisitos=' . $iIdReq));
    $oBaseDatos->consulta();
//Formacion Tecnica
    $pdf->pon_Caja(31, 60, array('ancho' => 25, 'alto' => 3, 'texto' => gettext('sCFFormacionTecnica') . ':',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(31, 60, array('ancho' => 34, 'alto' => 30, 'texto' => '',
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Cabeceras
    $pdf->pon_Caja(65, 60, array('ancho' => 57, 'alto' => 6, 'texto' => '',
        'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(122, 60, array('ancho' => 48, 'alto' => 6, 'texto' => gettext('sCFOpcional'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(170, 60, array('ancho' => 19, 'alto' => 6, 'texto' => gettext('sCFHoras'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
//Lineas
    for ($iContador = 0; $iContador < 6; $iContador++) {
        $sNombre = '';
        $sOpcional = '';
        $sHoras = '';
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $sNombre = $aIterador[0];
            $sOpcional = $aIterador[1];
            $sHoras = $aIterador[2];
        }
        $pdf->pon_Caja(65, 66 + $iContador * 4, array('ancho' => 57, 'alto' => 4, 'texto' => $sNombre,
            'borde' => 'B', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );
        $pdf->pon_Caja(122, 66 + $iContador * 4, array('ancho' => 48, 'alto' => 4, 'texto' => $sOpcional,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );
        $pdf->pon_Caja(170, 66 + $iContador * 4, array('ancho' => 19, 'alto' => 4, 'texto' => $sHoras,
            'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
            array('familia' => 'Times', 'estilo' => null, 'size' => 10),
            array('rojo' => 220)
        );
    }

//Titulo 2
    $pdf->pon_Caja(20, 92, array('ancho' => 169, 'alto' => 5,
        'texto' => gettext('Competences and skills for job'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 12),
        array('rojo' => 220)
    );

//Tercero Conocimientos generales y especificos
//Titulo
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('conocimientos', 'funciones'));
    $oBaseDatos->construir_Tablas(array('requisitos_puesto_competencias'));
    $oBaseDatos->construir_Where(array('id=' . $iIdReq));
    $oBaseDatos->consulta();
    $sConocimientos = '';
    $sFunciones = '';
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sConocimientos = $aIterador[0];
        $sFunciones = $aIterador[1];
    }
    $pdf->pon_Caja(20, 100, array('ancho' => 11, 'alto' => 43, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_MultiCaja(31, 100, array('ancho' => 158, 'alto' => 6, 'texto' => $sConocimientos,
        'borde' => 'TR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 106, array('ancho' => 158, 'alto' => 6, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 112, array('ancho' => 158, 'alto' => 6, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 118, array('ancho' => 158, 'alto' => 6, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Funciones y responsabilidades
    $pdf->pon_Caja(20, 143, array('ancho' => 11, 'alto' => 56, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );

    $pdf->pon_MultiCaja(31, 143, array('ancho' => 158, 'alto' => 7, 'texto' => $sFunciones,
        'borde' => 'TR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 150, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 157, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 164, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 171, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 178, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 185, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(31, 192, array('ancho' => 158, 'alto' => 7, 'texto' => '',
        'borde' => 'RB', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );

//Promocion y desarrollo
    $pdf->pon_Caja(20, 201, array('ancho' => 11, 'alto' => 31, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

//titulos
    $pdf->pon_Caja(31, 201, array('ancho' => 50, 'alto' => 7, 'texto' => gettext('sCFAutonomiayresp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 201, array('ancho' => 53, 'alto' => 7, 'texto' => gettext('sCFRelacionesyCom'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 201, array('ancho' => 55, 'alto' => 7, 'texto' => gettext('sCFHabilidadessociales'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
//Filas 1
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('autonomia', 'relaciones', 'liderazgo', 'mando',
        'motivacion', 'negociacion', 'trabajo', 'formacion'));
    $oBaseDatos->construir_Tablas(array('requisitos_puesto_promocion'));
    $oBaseDatos->construir_Where(array('id=' . $iIdReq));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        if ($aIterador[2] == 't') {
            $sLiderazgo = 'X';
        }
        if ($aIterador[3] == 't') {
            $sMando = 'X';
        }
        if ($aIterador[4] == 't') {
            $sMotivacion = 'X';
        }
        if ($aIterador[5] == 't') {
            $sNegociacion = 'X';
        }
        if ($aIterador[6] == 't') {
            $sTrabajo = 'X';
        }
        if ($aIterador[7] == 't') {
            $sFormacion = 'X';
        }
        switch ($aIterador[0]) {
            case 1:
                $sTotal = 'X';
                break;
            case 2:
                $sMucha = 'X';
                break;
            case 3:
                $sBastante = 'X';
                break;
            case 4:
                $sPoca = 'X';
                break;
            case 5:
                $sNinguna = 'X';
                break;
            default:
                break;
        }
        switch ($aIterador[1]) {
            case 1:
                $sTotal2 = 'X';
                break;
            case 2:
                $sMucha2 = 'X';
                break;
            case 3:
                $sBastante2 = 'X';
                break;
            case 4:
                $sPoca2 = 'X';
                break;
            case 5:
                $sNinguna2 = 'X';
                break;
            default:
                break;
        }
    }
    $pdf->pon_Caja(31, 208, array('ancho' => 37, 'alto' => 4, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 208, array('ancho' => 13, 'alto' => 4, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 208, array('ancho' => 40, 'alto' => 4, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 208, array('ancho' => 13, 'alto' => 4, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 208, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFLiderazgoyevaluacion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 208, array('ancho' => 13, 'alto' => 4, 'texto' => $sLiderazgo,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Fila 2
    $pdf->pon_Caja(31, 212, array('ancho' => 37, 'alto' => 4, 'texto' => gettext('sCFTotal'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 212, array('ancho' => 13, 'alto' => 4, 'texto' => $sTotal,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 212, array('ancho' => 40, 'alto' => 4, 'texto' => gettext('sCFMuyAlta'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 212, array('ancho' => 13, 'alto' => 4, 'texto' => $sTotal2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 212, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFMandoEvaluacion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 212, array('ancho' => 13, 'alto' => 4, 'texto' => $sMando,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Fila 3
    $pdf->pon_Caja(31, 216, array('ancho' => 37, 'alto' => 4, 'texto' => gettext('sCFMucha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 216, array('ancho' => 13, 'alto' => 4, 'texto' => $sMucha,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 216, array('ancho' => 40, 'alto' => 4, 'texto' => gettext('sCFAlta'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 216, array('ancho' => 13, 'alto' => 4, 'texto' => $sMucha2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 216, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFMotivacionDelegacion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 216, array('ancho' => 13, 'alto' => 4, 'texto' => $sMotivacion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Fila 4
    $pdf->pon_Caja(31, 220, array('ancho' => 37, 'alto' => 4, 'texto' => gettext('sCFBastante'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 220, array('ancho' => 13, 'alto' => 4, 'texto' => $sBastante,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 220, array('ancho' => 40, 'alto' => 4, 'texto' => gettext('sCFMedia'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 220, array('ancho' => 13, 'alto' => 4, 'texto' => $sBastante2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 220, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFNegociacion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 220, array('ancho' => 13, 'alto' => 4, 'texto' => $sNegociacion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Fila 5
    $pdf->pon_Caja(31, 224, array('ancho' => 37, 'alto' => 4, 'texto' => gettext('sCFPoca'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 224, array('ancho' => 13, 'alto' => 4, 'texto' => $sPoca,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 224, array('ancho' => 40, 'alto' => 4, 'texto' => gettext('sCFBaja'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 224, array('ancho' => 13, 'alto' => 4, 'texto' => $sPoca2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 224, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFTrabajoequipo'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 224, array('ancho' => 13, 'alto' => 4, 'texto' => $sTrabajo,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
//Fila 6
    $pdf->pon_Caja(31, 228, array('ancho' => 37, 'alto' => 4, 'texto' => gettext('sCFNinguna'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(68, 228, array('ancho' => 13, 'alto' => 4, 'texto' => $sNinguna,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(81, 228, array('ancho' => 40, 'alto' => 4, 'texto' => gettext('sCFNula'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(121, 228, array('ancho' => 13, 'alto' => 4, 'texto' => $sNinguna2,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(134, 228, array('ancho' => 42, 'alto' => 4, 'texto' => gettext('sCFFormacionEnse'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(176, 228, array('ancho' => 13, 'alto' => 4, 'texto' => $sFormacion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 6),
        array('rojo' => 220)
    );
    $pdf->Output();
}

//Funcion para crearnos el pdf asociado a una accion de mejora

function acc_mejora($iIdAccMejora)
{
    require_once 'Manejador_Base_Datos.class.php';
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('tipo',
        'case when acciones_mejora.cliente is null then \'Ninguno\' else (select clientes.nombre'.
        ' from clientes where id=acciones_mejora.cliente) end as cliente',
        'to_char(fecha, \'DD/MM/YYYY\')', 'descripcion', 'analisis', 'requiere_tratamiento', 'tratamiento',
        'accion_preventiva', 'plazo', 'observaciones', 'coste'));
    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios us1'));
    $oBaseDatos->construir_Where(array('acciones_mejora.id=' . $iIdAccMejora));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        switch ($aIterador[0]) {
            case '1':
                $sNC = 'X';
                break;
            case '2':
                $sReclamacion = 'X';
                break;
            case '3':
                $sPreventiva = 'X';
                break;
            case '4':
                $sAuditoria = 'X';
                break;
        }
        $sCliente = $aIterador[1];
        $sFecha = $aIterador[2];
        $sDescripcion = $aIterador[3];
        $sAnalisis = $aIterador[4];
        if ($aIterador[5] != 't') {
            $sRequiereTrat = 'X';
        }
        $sTratamiento = $aIterador[6];
        $sAccion = $aIterador[7];
        $sPlazo = $aIterador[8];
        $sObservaciones = $aIterador[9];
        $sCoste = $aIterador[10];
    }
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios'));
    $oBaseDatos->construir_Where(array('acciones_mejora.id=' . $iIdAccMejora, 'acciones_mejora.usuario_detectado=usuarios.id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sUsuarioDetecta = $aIterador[0];
    }
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios'));
    $oBaseDatos->construir_Where(array('acciones_mejora.id=' . $iIdAccMejora, 'acciones_mejora.usuario_verifica=usuarios.id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sUsuarioVerifica = $aIterador[0];
    }
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
    $oBaseDatos->construir_Tablas(array('acciones_mejora', 'usuarios'));
    $oBaseDatos->construir_Where(array('acciones_mejora.id=' . $iIdAccMejora, 'acciones_mejora.usuario_cerrado=usuarios.id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $sUsuarioCerrado = $aIterador[0];
    }
    $pdf = new PDF();

    $pdf->AddPage();
    //Titulo
    $pdf->pon_Caja(15, 10, array('ancho' => 132, 'alto' => 11, 'texto' => gettext('sCFAccionmejora'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(147, 10, array('ancho' => 49, 'alto' => 11, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );

    //Cabecera

    $pdf->pon_Caja(15, 23, array('ancho' => 36, 'alto' => 5, 'texto' => gettext('sCFNC'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 11),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(42, 24, array('ancho' => 3, 'alto' => 3, 'texto' => $sNC,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(51, 23, array('ancho' => 51, 'alto' => 5, 'texto' => gettext('sCFReclamacion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 11),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(90, 24, array('ancho' => 3, 'alto' => 3, 'texto' => $sReclamacion,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(102, 23, array('ancho' => 45, 'alto' => 5, 'texto' => gettext('sCFAuditoria'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 11),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(135, 24, array('ancho' => 3, 'alto' => 3, 'texto' => $sAuditoria,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(147, 23, array('ancho' => 49, 'alto' => 5, 'texto' => gettext('sCFPreventiva'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 11),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(184, 24, array('ancho' => 3, 'alto' => 3, 'texto' => $sPreventiva,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    //IDENTIFICACION
    $pdf->pon_Caja(15, 28, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFIdentificacion'),
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(15, 34, array('ancho' => 181, 'alto' => 7, 'texto' => gettext('sCFCliente') . ': ',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(45, 34, array('ancho' => 151, 'alto' => 7, 'texto' => $sCliente,
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(15, 41, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFProductoproyecto') . ':',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(45, 41, array('ancho' => 151, 'alto' => 7, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(15, 47, array('ancho' => 62, 'alto' => 7, 'texto' => gettext('sCFFecha') . ': ',
        'borde' => 'L', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(35, 47, array('ancho' => 42, 'alto' => 7, 'texto' => $sFecha,
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(77, 47, array('ancho' => 119, 'alto' => 7, 'texto' => gettext('sCFDetectadapor') . ': ',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(97, 47, array('ancho' => 151, 'alto' => 7, 'texto' => $sUsuarioDetecta,
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    //DESCRIPCION
    $pdf->pon_Caja(15, 54, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFDescripcion'),
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 60, array('ancho' => 181, 'alto' => 7, 'texto' => $sDescripcion,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 67, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 74, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 81, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    //ANALISIS
    $pdf->pon_Caja(15, 88, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFAnalisis'),
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 94, array('ancho' => 181, 'alto' => 6, 'texto' => $sAnalisis,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 100, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 106, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 112, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 118, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    //TRATAMIENTO
    $pdf->pon_Caja(15, 124, array('ancho' => 151, 'alto' => 6, 'texto' => gettext('sCFTratamientoinmediato'),
        'borde' => 'LT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );


    $pdf->pon_Caja(166, 124, array('ancho' => 30, 'alto' => 6, 'texto' => gettext('sCFNotiene'),
        'borde' => 'RT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(169, 126, array('ancho' => 3, 'alto' => 3, 'texto' => $sRequiereTrat,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_MultiCaja(15, 130, array('ancho' => 181, 'alto' => 7, 'texto' => $sTratamiento,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 137, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 144, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 150, array('ancho' => 181, 'alto' => 6, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    //ACCION
    $pdf->pon_Caja(15, 156, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFAccionCORPREV'),
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 16),
        array('rojo' => 220)
    );

    $pdf->pon_MultiCaja(15, 162, array('ancho' => 181, 'alto' => 7, 'texto' => $sAccion,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 169, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 176, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 183, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 190, array('ancho' => 181, 'alto' => 7, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    $pdf->pon_MultiCaja(15, 197, array('ancho' => 181, 'alto' => 7, 'texto' => '', '
    borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 8),
        array('rojo' => 220)
    );
    //CABECERAS
    $pdf->pon_Caja(15, 204, array('ancho' => 61, 'alto' => 4, 'texto' => gettext('sCFResponsableimp') . ':',
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 9),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(76, 204, array('ancho' => 60, 'alto' => 4, 'texto' => gettext('sCFPlazoaproximado') . ':',
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 9),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(136, 204, array('ancho' => 60, 'alto' => 4, 'texto' => gettext('sCFVerificadopor') . ':',
        'borde' => 'LRT', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 9),
        array('rojo' => 220)
    );

    //ESPACIO EN BLANCO (RESPUESTAS)
    $pdf->pon_Caja(15, 208, array('ancho' => 61, 'alto' => 8, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(76, 208, array('ancho' => 60, 'alto' => 8, 'texto' => $sPlazo,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(136, 208, array('ancho' => 60, 'alto' => 8, 'texto' => $sUsuarioVerifica,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    //PARTE DE ABAJO
    $pdf->pon_Caja(15, 216, array('ancho' => 20, 'alto' => 4, 'texto' => gettext('sCFFirmayfecha') . ':',
        'borde' => 'L', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(35, 216, array('ancho' => 41, 'alto' => 4, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(76, 216, array('ancho' => 60, 'alto' => 4, 'texto' => '',
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(136, 216, array('ancho' => 20, 'alto' => 4, 'texto' => gettext('sCFFirmayfecha') . ':',
        'borde' => 'L', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(156, 216, array('ancho' => 40, 'alto' => 4, 'texto' => '',
        'borde' => 'R', 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    //OBSERVACIONES
    $pdf->pon_Caja(15, 220, array('ancho' => 181, 'alto' => 6, 'texto' => gettext('sCFObservaciones'),
        'borde' => 'LTR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 12),
        array('rojo' => 220)
    );
    //TEXTO DE OBSERVACIONES
    $pdf->pon_Caja(15, 226, array('ancho' => 121, 'alto' => 12, 'texto' => $sObservaciones,
        'borde' => 'L', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    //COSTE
    $pdf->pon_Caja(15, 238, array('ancho' => 121, 'alto' => 10, 'texto' => gettext('sCFCoste') . ': ' . $sCoste,
        'borde' => 'LB', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 12),
        array('rojo' => 220)
    );

    //CERRADO POR
    $pdf->pon_Caja(136, 226, array('ancho' => 60, 'alto' => 4, 'texto' => gettext('sCFCerradopor') . ':',
        'borde' => 'LTR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(136, 230, array('ancho' => 60, 'alto' => 14, 'texto' => $sUsuarioCerrado,
        'borde' => 'LR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(136, 244, array('ancho' => 60, 'alto' => 4, 'texto' => gettext('sCFFirmayfecha') . ':',
        'borde' => 'LBR', 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => '', 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->Output();
}


// Funcion para mostrar los PDF's de los indicadores
function indicadores($iIdIndi)
{
    require_once 'Manejador_Base_Datos.class.php';
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('objetivos.nombre', 'indicadores.nombre'));
    $oBaseDatos->construir_Tablas(array('objetivos', 'indicadores'));
    $oBaseDatos->construir_Where(array('objetivos.id=' . $iIdIndi . ' AND objetivos.indicadores=indicadores.id'));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $objetivos = $aIterador[0];
        $indicador = $aIterador[1];
    }

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('numero_meta', 'recursos', 'responsable', 'fecha_consecucion', 'plan_accion'));
    $oBaseDatos->construir_Tablas(array('metas_indicadores'));
    $oBaseDatos->construir_Where(array('objetivo_id=' . $iIdIndi));
    $oBaseDatos->consulta();
    for ($iContador = 0; $iContador < 11; $iContador++) {
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $aMetas[$iContador]['numero_meta'] = $aIterador[0];
            $aMetas[$iContador]['recursos'] = $aIterador[1];
            $aMetas[$iContador]['responsable'] = $aIterador[2];
            $aMetas[$iContador]['fecha_consecucion'] = $aIterador[3];
            $aMetas[$iContador]['plan_accion'] = $aIterador[4];
        }
    }

    $pdf = new PDF('L');

    $pdf->AddPage();
    //Titulo
    $pdf->pon_Caja(15, 4, array('ancho' => 271, 'alto' => 15, 'texto' => 'NOVASOFT',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );
    // Caja decorativa exterior
    $pdf->pon_Caja(15, 23, array('ancho' => 11, 'alto' => 16, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Caja objetivos
    $pdf->pon_Caja(26, 23, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sIOObjetivos'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(26, 23, array('ancho' => 260, 'alto' => 8, 'texto' => $objetivos,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Caja Indicador
    $pdf->pon_Caja(26, 31, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sIOIndicador'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(26, 31, array('ancho' => 260, 'alto' => 8, 'texto' => $indicador,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //**************** Meta 1 ********************

    // Caja decorativa exterior
    $pdf->pon_Caja(15, 44, array('ancho' => 11, 'alto' => 102, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja PlanAccion
    $pdf->pon_Caja(26, 44, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Metas
    $pdf->pon_Caja(146, 44, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 44, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 1 PlanAccion
    $pdf->pon_Caja(26, 52, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[0]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 1 Metas
    $pdf->pon_Caja(146, 52, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[0]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 1 Fecha
    $pdf->pon_Caja(166, 52, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[0]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 60, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja  Responsable
    $pdf->pon_Caja(156, 60, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 2 Recursos
    $pdf->pon_Caja(26, 68, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[0]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 2 Responsable
    $pdf->pon_Caja(156, 68, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[0]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 1 Separacion
    $pdf->pon_Caja(26, 76, array('ancho' => 260, 'alto' => 3, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //************** Meta 2 ****************


    //Caja PlanAccion
    $pdf->pon_Caja(26, 79, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Metas
    $pdf->pon_Caja(146, 79, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 79, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Metas
    $pdf->pon_Caja(26, 87, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[1]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Responsable
    $pdf->pon_Caja(146, 87, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[1]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 3 Fecha
    $pdf->pon_Caja(166, 87, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[1]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 95, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Responsable
    $pdf->pon_Caja(156, 95, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 4 Recursos
    $pdf->pon_Caja(26, 103, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[1]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 4 Responsable
    $pdf->pon_Caja(156, 103, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[1]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Meta 2 Separacion
    $pdf->pon_Caja(26, 111, array('ancho' => 260, 'alto' => 3, 'texto' => '', 'borde' => 1, 'linea' => 0,
        'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //************ Meta 3 ****************


    //Caja PlanAccion
    $pdf->pon_Caja(26, 114, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja meta
    $pdf->pon_Caja(146, 114, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 114, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 PlanAccion
    $pdf->pon_Caja(26, 122, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[2]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Metas
    $pdf->pon_Caja(146, 122, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[2]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 3 Fecha
    $pdf->pon_Caja(166, 122, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[2]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 130, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Responsable
    $pdf->pon_Caja(156, 130, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 4 Recursos
    $pdf->pon_Caja(26, 138, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[2]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 4 PlanAccion
    $pdf->pon_Caja(156, 138, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[2]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Caja decorativa exterior
    $pdf->pon_Caja(15, 149, array('ancho' => 11, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Caja verificado por
    $pdf->pon_Caja(26, 149, array('ancho' => 130, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(26, 149, array('ancho' => 30, 'alto' => 5, 'texto' => gettext('sIOVerificado'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(56, 149, array('ancho' => 100, 'alto' => 5, 'texto' => $verificado,
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(26, 154, array('ancho' => 130, 'alto' => 5, 'texto' => gettext('sIOFirma'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(31, 154, array('ancho' => 130, 'alto' => 25, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    // Caja aprobado por
    $pdf->pon_Caja(156, 149, array('ancho' => 130, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(156, 149, array('ancho' => 30, 'alto' => 5, 'texto' => gettext('sIOAprobado'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(186, 149, array('ancho' => 100, 'alto' => 5, 'texto' => $aprobado,
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(156, 154, array('ancho' => 130, 'alto' => 5, 'texto' => gettext('sIOFirma'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(161, 154, array('ancho' => 130, 'alto' => 25, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->Output();
}


// Funcion para mostrar los PDF's de los indicadores
function documentacion($IdDoc)
{
    require_once 'Manejador_Base_Datos.class.php';
    $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('nombre'));
    $oBaseDatos->construir_Tablas(array('objetivos_globales'));
    $oBaseDatos->construir_Where(array('id=' . $IdDoc));
    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        $objetivos = $aIterador[0];
    }

    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('numero_meta', 'recursos', 'responsable',
        'fecha_consecucion', 'plan_accion'));
    $oBaseDatos->construir_Tablas(array('metas_objetivos'));
    $oBaseDatos->construir_Where(array('objetivo_id=' . $IdDoc));
    $oBaseDatos->consulta();
    for ($iContador = 0; $iContador < 11; $iContador++) {
        if ($aIterador = $oBaseDatos->coger_Fila()) {
            $aMetas[$iContador]['numero_meta'] = $aIterador[0];
            $aMetas[$iContador]['recursos'] = $aIterador[1];
            $aMetas[$iContador]['responsable'] = $aIterador[2];
            $aMetas[$iContador]['fecha_consecucion'] = $aIterador[3];
            $aMetas[$iContador]['plan_accion'] = $aIterador[4];
        }
    }

    $pdf = new PDF('L');

    $pdf->AddPage();
    //Titulo
    $pdf->pon_Caja(15, 4, array('ancho' => 271, 'alto' => 15, 'texto' => 'NOVASOFT',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => 'B', 'size' => 20),
        array('rojo' => 220)
    );
    // Caja decorativa exterior
    $pdf->pon_Caja(15, 23, array('ancho' => 11, 'alto' => 16, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Caja objetivos
    $pdf->pon_Caja(26, 23, array('ancho' => 30, 'alto' => 3, 'texto' => gettext('sIOObjetivos'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(26, 23, array('ancho' => 260, 'alto' => 8, 'texto' => $objetivos,
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Caja Indicador
    $pdf->pon_Caja(26, 31, array('ancho' => 30, 'alto' => 3, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );
    $pdf->pon_Caja(26, 31, array('ancho' => 260, 'alto' => 8, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //**************** Meta 1 ********************

    // Caja decorativa exterior
    $pdf->pon_Caja(15, 44, array('ancho' => 11, 'alto' => 102, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja PlanAccion
    $pdf->pon_Caja(26, 44, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Metas
    $pdf->pon_Caja(146, 44, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 44, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 1 PlanAccion
    $pdf->pon_Caja(26, 52, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[0]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 1 Metas
    $pdf->pon_Caja(146, 52, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[0]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 1 Fecha
    $pdf->pon_Caja(166, 52, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[0]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 60, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja  Responsable
    $pdf->pon_Caja(156, 60, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 2 Recursos
    $pdf->pon_Caja(26, 68, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[0]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 2 Responsable
    $pdf->pon_Caja(156, 68, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[0]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 1 Separacion
    $pdf->pon_Caja(26, 76, array('ancho' => 260, 'alto' => 3, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //************** Meta 2 ****************


    //Caja PlanAccion
    $pdf->pon_Caja(26, 79, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Metas
    $pdf->pon_Caja(146, 79, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 79, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Metas
    $pdf->pon_Caja(26, 87, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[1]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Responsable
    $pdf->pon_Caja(146, 87, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[1]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 3 Fecha
    $pdf->pon_Caja(166, 87, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[1]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 95, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Responsable
    $pdf->pon_Caja(156, 95, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 4 Recursos
    $pdf->pon_Caja(26, 103, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[1]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 4 Responsable
    $pdf->pon_Caja(156, 103, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[1]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Meta 2 Separacion
    $pdf->pon_Caja(26, 111, array('ancho' => 260, 'alto' => 3, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //************ Meta 3 ****************


    //Caja PlanAccion
    $pdf->pon_Caja(26, 114, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOPlanAccion'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja meta
    $pdf->pon_Caja(146, 114, array('ancho' => 20, 'alto' => 8, 'texto' => gettext('sIOMetas'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Fecha
    $pdf->pon_Caja(166, 114, array('ancho' => 120, 'alto' => 8, 'texto' => gettext('sIOFecha'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 PlanAccion
    $pdf->pon_Caja(26, 122, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[2]['plan_accion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Linea 3 Metas
    $pdf->pon_Caja(146, 122, array('ancho' => 20, 'alto' => 8, 'texto' => $aMetas[2]['numero_meta'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Linea 3 Fecha
    $pdf->pon_Caja(166, 122, array('ancho' => 120, 'alto' => 8, 'texto' => $aMetas[2]['fecha_consecucion'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    //Caja Recursos
    $pdf->pon_Caja(26, 130, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIORecursos'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    //Caja Responsable
    $pdf->pon_Caja(156, 130, array('ancho' => 130, 'alto' => 8, 'texto' => gettext('sIOResp'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Linea 4 Recursos
    $pdf->pon_Caja(26, 138, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[2]['recursos'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );
    // Linea 4 PlanAccion
    $pdf->pon_Caja(156, 138, array('ancho' => 130, 'alto' => 8, 'texto' => $aMetas[2]['responsable'],
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );


    // Caja decorativa exterior
    $pdf->pon_Caja(15, 149, array('ancho' => 11, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    // Caja verificado por
    $pdf->pon_Caja(26, 149, array('ancho' => 130, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(26, 149, array('ancho' => 30, 'alto' => 5, 'texto' => gettext('sIOVerificado'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(56, 149, array('ancho' => 100, 'alto' => 5, 'texto' => $verificado,
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(26, 154, array('ancho' => 130, 'alto' => 5, 'texto' => gettext('sIOFirma'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(31, 154, array('ancho' => 130, 'alto' => 25, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    // Caja aprobado por
    $pdf->pon_Caja(156, 149, array('ancho' => 130, 'alto' => 35, 'texto' => '',
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(156, 149, array('ancho' => 30, 'alto' => 5, 'texto' => gettext('sIOAprobado'),
        'borde' => 1, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 10),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(186, 149, array('ancho' => 100, 'alto' => 5, 'texto' => $aprobado,
        'borde' => 1, 'linea' => 1, 'alineacion' => 'C', 'relleno' => 1),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(156, 154, array('ancho' => 130, 'alto' => 5, 'texto' => gettext('sIOFirma'),
        'borde' => 0, 'linea' => 0, 'alineacion' => 'L', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->pon_Caja(161, 154, array('ancho' => 130, 'alto' => 25, 'texto' => '',
        'borde' => 0, 'linea' => 0, 'alineacion' => 'C', 'relleno' => 0),
        array('familia' => 'Times', 'estilo' => null, 'size' => 7),
        array('rojo' => 220)
    );

    $pdf->Output();
}


if (!isset($_SESSION)) {
    session_start();
}
//Llamamos a la funcion correspondiente dependiendo de que queramos una ficha personal
if ($_GET['tipo'] == 'requisito') {
    req_puesto($_GET['id']);
} else if ($_GET['tipo'] == 'ficha') {
    ficha_personal($_GET['id']);
} else if ($_GET['tipo'] == 'objetivos') {
    indicadores($_GET['id']);
} else if ($_GET['tipo'] == 'objetivos_generales') {
    documentacion($_GET['id']);
} else {
    acc_mejora($_GET['id']);
}
