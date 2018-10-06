<?php
namespace Tuqan\Classes;
/**
 * Created on 12-sep-2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


function procesa_Ayuda($aParametros)
{

    switch ($_SESSION['idioma']) {
        case 'castellano':
            $idioma = 1;
            break;
        case 'catalan':
            $idioma = 2;
            break;
    }


    if ($aParametros['accionanterior'] != 'ayuda:ayuda:ayuda:ayuda') {

        /**
         * Probamos primero si es ayuda de menu
         */

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id'));
        $oBaseDatos->construir_Tablas(array('menu_nuevo'));
        $oBaseDatos->construir_Where(array('(accion=\'' . $aParametros['accionanterior'] . '\')'));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('texto'));
            $oBaseDatos->construir_Tablas(array('division_ayuda'));
            $oBaseDatos->construir_Where(array('(menu=\'' . $aIterador[0] . '\' and idioma=\'' . $idioma . '\')'));

            $oBaseDatos->consulta();

            if ($aIteradorMenu = $oBaseDatos->coger_Fila()) {
                $sHtml = $aIteradorMenu[0];
            } else {
                $sHtml = "No existe ayuda para esta seccion";
            }
        } else {
            /**
             * Menu de Ayuda de Botones
             */

            $aAccion = explode(':', $aParametros['accionanterior']);

            if ($aAccion[2] == 'formulario' && $aAccion[3] == 'general') {
                $sAccion = $aParametros['accioniframe'];
            } else {
                $sAccion = $aParametros['accionanterior'];
            }


            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('botones'));
            $oBaseDatos->construir_Where(array("accion='sndReq(\\'" . $sAccion . "\\',\\'\\',1)' OR accion='parent.sndReq(\\'" . $sAccion . "\\',\\'\\',1)'"));
            $oBaseDatos->consulta();

            $aIterador = $oBaseDatos->coger_Fila();
            if ($aIterador) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('texto'));
                $oBaseDatos->construir_Tablas(array('division_ayuda'));
                $oBaseDatos->construir_Where(array('boton=\'' . $aIterador[0] . '\' and idioma=\'' . $idioma . '\''));

                $oBaseDatos->consulta();

                if ($aIteradorBoton = $oBaseDatos->coger_Fila()) {
                    $sHtml = $aIteradorBoton[0];
                } else {
                    $sHtml = "No existe ayuda para esta seccion";
                }
            }
        }
        if (!$sHtml) {
            if ($idioma == 1 && $aAccion[3] == 'inicial') {

                $sHtml = '<br /><b>Ayuda</b><br />
                      <p align=\'left\'><b>Inicio--><br />Mensajes--><br />Listados</b><br />
                      En esta secci�n el usuario ver� en la página una lista con los mensajes ' .
                    'que ha recibido. Estos mensajes pueden ser generados de dos formas: ' .
                    'por los usuarios desde el m�dulo de formaci�n o por el administrador ' .
                    'desde el m�dulo de mantenimiento. Siguiendo con la descripci�n del m�dulo,' .
                    ' en esta página de "Mensajes", el usuario ver� que aparecen habilitados dos ' .
                    'botones: "Enviar Mensaje" e "Histórico". Sin embargo, si el usuario selecciona ' .
                    'cualquier mensaje de la lista, podr� "Eliminar" y "Ver" los mensajes.";';
            } elseif ($idioma == 2 && $aAccion[3] == 'inicial') {
                $sHtml = '<br /><b>Ajuda</b><br />' .
                    '<p aling=\'lef\'><b>Inici--><br />Missatges--><br />Llistats</b><br />' .
                    'En aquesta secci� l\'usuari veur� en la página una llista amb els missatges' .
                    ' que ha rebut. Aquests missatges poden ser generats de dues formes: pels usuaris' .
                    ' des del m�dul de formaci� o per l\'administrador des del m�dul de manteniment. ' .
                    'Seguint amb la descripci� del m�dul, en aquesta página de "Missatges", l\'usuari veur� ' .
                    'que apareixen habilitats dos botons: "Enviar Missatge" i "Hist�ric". No obstant aix�,' .
                    ' si l\'usuari selecciona qualsevol missatge de la llista, podr� "Eliminar" i "Veure" ' .
                    'els missatges.';
            } else {
                $sHtml = "No existe ayuda para esta seccion";
            }
        }

        $_SESSION['ayuda'] = $sHtml;

    } else {
        $sHtml = $_SESSION['ayuda'];
    }


    return $sHtml;
}

