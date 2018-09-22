<?php
/**
* LICENSE see LICENSE.md file
 *

 */
include_once 'include.php';

function procesa_Editor($aParametros)
{
    require_once 'Manejador_Base_Datos.class.php';
    require_once 'constantes.inc.php';
    require_once 'boton.php';
    if (is_numeric($aParametros['idDocumento'])) {
        //Es para editar, sacamos los valores
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'contenido_texto.contenido', 'estado'));
        $oBaseDatos->construir_Tablas(array('documentos', 'contenido_texto'));
        $oBaseDatos->construir_Where(array('documentos.id=contenido_texto.id', 'documentos.id=' . $aParametros['idDocumento']));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        $_SESSION['datoseditor'] = $aIterador[2];
        $_SESSION['codigo'] = $aIterador[0];
        $_SESSION['nombre'] = $aIterador[1];
        if (!isset($aParametros['nuevaVersion'])) {
            $_SESSION['iddoc'] = $aParametros['idDocumento'];
            if ($aIterador[3] != iBorrador) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'contenido_texto.contenido'));
                $oBaseDatos->construir_Tablas(array('documentos', 'contenido_texto'));
                $oBaseDatos->construir_Where(array('estado=' . iBorrador, 'documentos.id=contenido_texto.id', 'documentos.codigo=' . $aIterador[0], 'documentos.nombre=' . $aIterador[1]));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
            }
            $_SESSION['datoseditor'] = $aIterador[2];
            $_SESSION['codigo'] = $aIterador[0];
            $_SESSION['nombre'] = $aIterador[1];
        } else {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('doc1.codigo'));
            $oBaseDatos->construir_Tablas(array('documentos doc1', 'documentos doc2'));
            $oBaseDatos->construir_Where(array('(doc1.codigo=doc2.codigo)',
                '(doc1.nombre=doc2.nombre)',
                '(doc2.id=' . $aParametros['idDocumento'] . ')',
                '(doc1.estado=' . iBorrador . ') OR (doc1.estado=' . iPendRevision .
                ') OR (doc1.estado=' . iPendAprobacion . ') OR (doc1.estado=' . iRevisado .
                ')'));
            $oBaseDatos->consulta();
            if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                //Eso es que ya hay un borrador, abortamos
                return "alert|" . gettext('sBorraAlert');
            }
        }

    } else {
        $_SESSION['codigo'] = "";
        $_SESSION['datoseditor'] = "";
        $_SESSION['nombre'] = "";
        $_SESSION['idtipo'] = $aParametros['idtipo'];
    }
    if ($_SESSION['editor'] == 0) {
        //Esto quiere decir que el editor aun no esta creado por lo que debemos crearlo
        $sHtml = "diveditor|<iframe id=\"FCKEDITOR\" src=\"fckeditor.php?codigo=" . $_SESSION['codigo'] . "&nombre=" . $_SESSION['nombre'] . "&datos=\" width=\"100%\" height=\"600px\"" .
            "frameborder=\"0\" scrolling=\"auto\" style=\"z-index: 0\"><\iframe>|";
        $_SESSION['editor'] = 1;
    } else {
        $sHtml = "sacareditor|" . $_SESSION['datoseditor'] . separadorCadenas . $_SESSION['codigo'] . separadorCadenas . $_SESSION['nombre'];
    }
    return $sHtml;
}

