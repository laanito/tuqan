<?php
/**
* LICENSE see LICENSE.md file
 *
 * @author Luis Alberto Amigo Navarro
 * @version 0.3b
 * Archivo que genera los árboles de permisos
 */

use Tuqan\Classes\FakePage;
use Tuqan\Classes\Generador_Arboles;
use Tuqan\Classes\Manejador_Base_Datos;

require_once 'boton.php';
require_once('Classes/generador_arboles.php');
require_once('Classes/Manejador_Base_Datos.class.php');
require_once('Classes/FakePage.php');


function arbol_permisos($iUserid, $sLogin, $sPass, $sBdatos)
{
    $oDb = new Manejador_Base_Datos($sLogin, $sPass, $sBdatos);
    $aCampos = array('case when menu.menu like \'%administracion%\' then \'Administracion\' else \'Usuarios\' end as nodosuperior', 'submenu', 'nodo',
        'etiqueta', 'menu.permisos[' . $iUserid . ']', 'menu.id', 'botones_idiomas.valor', 'botones.permisos[' . $iUserid . ']', 'botones.id');
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos($aCampos);
    $oDb->construir_Tablas(array('menu', 'botones', 'botones_idiomas'));

    $oDb->construir_Order(array('nodosuperior,submenu,nodo,menu.id,botones.id'));
    $oDb->construir_Where(array('menu.id=botones.menu', 'botones_idiomas.boton=botones.id', "botones_idiomas.idioma='" . $_SESSION['idioma'] . "'"));
    $oDb->consulta();
    $_SESSION['filas_arbol'] = array();
    $_SESSION['filas_botones'] = array();
    $_SESSION['usuario_permisos'] = $iUserid;
    if ($aIterador = $oDb->coger_Fila()) {

        $aMenu = array($aIterador[0] => array($aIterador[1] => array($aIterador[2] => array($aIterador[3] => array($aIterador[6] => 1)))));
        if ($aIterador[4] == 't') {
            $aPermisos[$aIterador[0]]['valor_raiz'] = 1;
            $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 1;
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]]['valor_raiz'] = 1;
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]]['valor_raiz'] = 1;
        } else {
            $aPermisos[$aIterador[0]]['valor_raiz'] = 0;
            $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 0;
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]]['valor_raiz'] = 0;
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]]['valor_raiz'] = 0;
        }
        if ($aIterador[7] == 't')
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[6]] = 1;
        else
            $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[6]] = 0;
        $_SESSION['filas_arbol'][0] = $aIterador[5];
        $_SESSION['filas_botones'][0] = $aIterador[8];
        $i = 1;
        $j = 0;
        while ($aIterador = $oDb->coger_Fila()) {
            $aMenu[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[6]] = 1;
            if ($aIterador[5] != $_SESSION['filas_arbol'][$j]) {
                $j++;
                $_SESSION['filas_arbol'][$j] = $aIterador[5];
            }
            $_SESSION['filas_botones'][$i] = $aIterador[8];
            if ($aIterador[4] == 't') {
                $aPermisos[$aIterador[0]]['valor_raiz'] = 1;
                $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 1;
                $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]]['valor_raiz'] = 1;
                $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]]['valor_raiz'] = 1;
            } else
                $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]]['valor_raiz'] = 0;
            if ($aIterador[7] == 't')
                $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[6]] = 1;
            else
                $aPermisos[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[6]] = 0;
            $i++;
        }
        //Fin while
    }
    $menu = new Generador_Arboles();
    $menu->Inicia_Arbol('pepe');
    if (is_array($aMenu)) {
        foreach ($aMenu as $i => $valor) {
            if (is_array($valor)) {
                $menu->valor_check($aPermisos[$i]['valor_raiz'], ' ');
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a1'); //nodo1
                foreach ($valor as $i2 => $valor2) {
                    $menu->valor_check($aPermisos[$i][$i2]['valor_raiz'], ' ');
                    $nodo = $menu->Nuevo_Nodo($i2, 'a2');  //nodo2
                    if (is_array($valor2)) {
                        foreach ($valor2 as $i3 => $valor3) {
                            $menu->valor_check($aPermisos[$i][$i2][$i3]['valor_raiz'], ' ');
                            $nodo2 = $menu->Nuevo_Nodo($i3, 'a2');  //nodo3
                            if (is_array($valor3)) {
                                foreach ($valor3 as $i4 => $valor4) {
                                    $menu->valor_check($aPermisos[$i][$i2][$i3][$i4]['valor_raiz'], 'aplicable');
                                    $nodo3 = $menu->Nuevo_Nodo($i4, 'a3');  //nodo4
                                    if (is_array($valor4)) {
                                        foreach ($valor4 as $i5 => $valor5) {
                                            $menu->valor_check($aPermisos[$i][$i2][$i3][$i4][$i5], 'boton');
                                            $nodo4 = $menu->Nuevo_Nodo($i5, 'a3');  //nodo4
                                            $menu->Situa_Nodo($nodo4, $nodo3);
                                        }
                                    }
                                    $menu->Situa_Nodo($nodo3, $nodo2);
                                }
                            }
                            $menu->Situa_Nodo($nodo2, $nodo);
                        }
                    }
                    $menu->Situa_Nodo($nodo, $nodo_padre);
                }
                $menu->Situa_Nodo($nodo_padre);
            } else {
                $menu->valor_check($aPermisos[$i]['valor_raiz'], ' ');
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a2');    //nodo2
                $menu->Situa_Nodo($nodo_padre);
            }
        }
    }

    $oPagina = new FakePage();
    $oPagina->addScript('/javascript/cursor.js', "text/javascript");
    $oPagina->addStyleDeclaration('/css/tuqan.ccs', 'text/css');

    $sPremenu = $menu->Pinta_Arbol();

    $oPagina->addBodyContent("<div id=\"arbol_centro\">" . $sPremenu . "</div>");
    return $oPagina->toHTML();

}

function ver_arbol_permisos($iUserid, $sLogin, $sPass, $sBdatos)
{
    $oDb = new Manejador_Base_Datos($sLogin, $sPass, $sBdatos);
    $sTabla = 'menu_nuevo';
    $aCampos = array('case when menu_nuevo.menu_nuevo like \'%administracion%\' then \'Administracion\' else \'Usuarios\' end as nodosuperior', 'submenu', 'nodo',
        'etiqueta', 'botones.texto');
    $oDb->iniciar_Consulta('SELECT');
    $oDb->construir_Campos($aCampos);
    $oDb->construir_Tablas(array($sTabla, 'botones'));
    $oDb->construir_Order(array('nodosuperior,submenu,nodo,menu_nuevo.id,botones.id'));
    $oDb->construir_Where(array('menu_nuevo.id=botones.menu', 'botones.permisos[' . $iUserid . ']=true'));
    $oDb->consulta();
    if ($aIterador = $oDb->coger_Fila()) {

        $aMenu = array($aIterador[0] => array($aIterador[1] => array($aIterador[2] => array($aIterador[3] => array($aIterador[4] => 1)))));
        while ($aIterador = $oDb->coger_Fila()) {
            $aMenu[$aIterador[0]][$aIterador[1]][$aIterador[2]][$aIterador[3]][$aIterador[4]] = 1;
        }
        //Fin while
    }
    $menu = new Generador_Arboles();
    $menu->Inicia_Arbol('menu_nuevo');
    if (is_array($aMenu)) {
        foreach ($aMenu as $i => $valor) {
            if (is_array($valor)) {
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a1'); //nodo1
                foreach ($valor as $i2 => $valor2) {
                    $nodo = $menu->Nuevo_Nodo($i2, 'a2');  //nodo2
                    if (is_array($valor2)) {
                        foreach ($valor2 as $i3 => $valor3) {
                            $nodo2 = $menu->Nuevo_Nodo($i3, 'a2');  //nodo3
                            if (is_array($valor3)) {
                                foreach ($valor3 as $i4 => $valor4) {
                                    $nodo3 = $menu->Nuevo_Nodo($i4, 'a3');  //nodo4
                                    if (is_array($valor4)) {
                                        foreach ($valor4 as $i5 => $valor5) {
                                            $nodo4 = $menu->Nuevo_Nodo($i5, 'a3');  //nodo4
                                            $menu->Situa_Nodo($nodo4, $nodo3);
                                        }
                                    }
                                    $menu->Situa_Nodo($nodo3, $nodo2);
                                }
                            }
                            $menu->Situa_Nodo($nodo2, $nodo);
                        }
                    }
                    $menu->Situa_Nodo($nodo, $nodo_padre);
                }
                $menu->Situa_Nodo($nodo_padre);
            } else {
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a2');    //nodo2
                $menu->Situa_Nodo($nodo_padre);
            }
        }
    }

    $oPagina = new FakePage();
    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
    $sPremenu = $menu->Pinta_Arbol();
    $oPagina->addBodyContent("<div id=\"arbol_centro\">" . $sPremenu . "</div>");
    return $oPagina->toHTML();
}

/**
 * Funcion auxiliar de permisos documentos para asignar la id a todos los nodos de cada documento
 */
function asignar_Menu($iId)
{
    return array('Ver' => $iId, 'Nueva Version' => $iId, 'Modificar' => $iId, 'Revisar' => $iId,
        'Aprobar' => $iId, 'Historico' => $iId, 'Tareas' => $iId);
}

function asignar_Permisos($aIterador)
{
    $aPerm = array();
    if ($aIterador[2] == 't')
        $aPerm['Ver'] = 1;
    else
        $aPerm['Ver'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Nueva Version']=$aIterador[9];
    if ($aIterador[3] == 't')
        $aPerm['Nueva Version'] = 1;
    else
        $aPerm['Nueva Version'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Modificar']=$aIterador[9];
    if ($aIterador[4] == 't')
        $aPerm['Modificar'] = 1;
    else
        $aPerm['Modificar'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Revisar']=$aIterador[9];
    if ($aIterador[5] == 't')
        $aPerm['Revisar'] = 1;
    else
        $aPerm['Revisar'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Aprobar']=$aIterador[9];
    if ($aIterador[6] == 't')
        $aPerm['Aprobar'] = 1;
    else
        $aPerm['Aprobar'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Historico']=$aIterador[9];
    if ($aIterador[7] == 't')
        $aPerm['Historico'] = 1;
    else
        $aPerm['Historico'] = 0;
//    $aMenu[$aIterador[0]][$aIterador[1]]['Tareas']=$aIterador[9];
    if ($aIterador[8] == 't')
        $aPerm['Tareas'] = 1;
    else
        $aPerm['Tareas'] = 0;

    return $aPerm;
}

function permisos_documentos($iUserid, $bUsuario, $sLogin, $sPass, $sBdatos)
{
    $oDb = new Manejador_Base_Datos($sLogin, $sPass, $sBdatos);
    $aTabla = array('documentos', 'tipo_documento', 'tipo_documento_idiomas');
    $oDb->iniciar_Consulta('SELECT');
    /*if($bUsuario==true)
    {
    $aCampos=array('tipo_documento.nombre','documentos.nombre','permisos_ver['.$iUserid.']',
        'permisos_nueva['.$iUserid.']','permisos_modificar['.$iUserid.']','permisos_revisar['.$iUserid.']','permisos_aprobar['.$iUserid.']',
        'permisos_historico['.$iUserid.']','permisos_tareas['.$iUserid.']','documentos.id');
    $oDb->construir_Group(array('tipo_documento.nombre','documentos.nombre','permisos_ver['.$iUserid.']',
        'permisos_nueva['.$iUserid.']','permisos_modificar['.$iUserid.']','permisos_revisar['.$iUserid.']','permisos_aprobar['.$iUserid.']',
        'permisos_historico['.$iUserid.']','permisos_tareas['.$iUserid.']','documentos.id'));
    }
    else
    {*/
    $aCampos = array('tipo_documento_idioma.valor', 'documentos.nombre', 'perfil_ver[' . $iUserid . ']',
        'perfil_nueva[' . $iUserid . ']', 'perfil_modificar[' . $iUserid . ']', 'perfil_revisar[' . $iUserid . ']', 'perfil_aprobar[' . $iUserid . ']',
        'perfil_historico[' . $iUserid . ']', 'perfil_tareas[' . $iUserid . ']', 'documentos.id', 'documentos.codigo');
    $oDb->construir_Group(array('tipo_documento_idioma.valor', 'documentos.nombre', 'perfil_ver[' . $iUserid . ']',
        'perfil_nueva[' . $iUserid . ']', 'perfil_modificar[' . $iUserid . ']', 'perfil_revisar[' . $iUserid . ']', 'perfil_aprobar[' . $iUserid . ']',
        'perfil_historico[' . $iUserid . ']', 'perfil_tareas[' . $iUserid . ']', 'documentos.id', 'documentos.codigo'));
//}
    $oDb->construir_Campos($aCampos);
    $oDb->construir_Tablas($aTabla);
//$oDb->construir_Where(array('tipo_documento.id=documentos.tipo_documento','documentos.estado='.iVigor));
    $oDb->construir_Where(array('tipo_documento.id=documentos.tipo_documento', 'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
    $oDb->construir_Order(array('tipo_documento_idiomas.valor,documentos.nombre DESC'));
    $oDb->consulta();
//$_SESSION['filas_arbol']=array();
    $_SESSION['codigo_arbol'] = array();
    $_SESSION['nombre_arbol'] = array();
    $_SESSION['usuario_permisos'] = $iUserid;
    if ($aIterador = $oDb->coger_Fila()) {

        $aMenu[$aIterador[0]][$aIterador[1]] = asignar_Menu($aIterador[9]);
        $aPermisos[$aIterador[0]][$aIterador[1]] = asignar_Permisos($aIterador);

        if ($aIterador[2] == 't' || $aIterador[3] == 't' || $aIterador[4] == 't' || $aIterador[5] == 't' || $aIterador[6] == 't' || $aIterador[7] == 't' || $aIterador[8] == 't') {
            $aPermisos[$aIterador[0]]['valor_raiz'] = 1;
            $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 1;
        } else {
            $aPermisos[$aIterador[0]]['valor_raiz'] = 0;
            $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 0;
        }
        //    $_SESSION['filas_arbol'][0]=$aIterador[9];
        $_SESSION['codigo_arbol'][0] = $aIterador[10];
        $_SESSION['nombre_arbol'][0] = $aIterador[1];

        $i = 1;
        while ($aIterador = $oDb->coger_Fila()) {
            //Este if es por lo mismo que los permisos de menus, metemos documentos duplicados en la sesion, los cuales
            //Hacen que se descuadre la comparacion de los check con lo guardado en sesion
            if (!isset($aMenu[$aIterador[0]][$aIterador[1]])) {
                $aMenu[$aIterador[0]][$aIterador[1]] = asignar_Menu($aIterador[9]);
                $aPermisos[$aIterador[0]][$aIterador[1]] = asignar_Permisos($aIterador);

                if ($aIterador[2] == 't' || $aIterador[3] == 't' || $aIterador[4] == 't' || $aIterador[5] == 't' || $aIterador[6] == 't' || $aIterador[7] == 't' || $aIterador[8] == 't') {
                    $aPermisos[$aIterador[0]]['valor_raiz'] = 1;
                    $aPermisos[$aIterador[0]][$aIterador[1]]['valor_raiz'] = 1;
                }
                $_SESSION['codigo_arbol'][$i] = $aIterador[10];
                $_SESSION['nombre_arbol'][$i] = $aIterador[1];
                $i++;
            }
        }//Fin while
    }
    $menu = new Generador_Arboles();
    $menu->Inicia_Arbol('pepe');
    if (is_array($aMenu)) {
        foreach ($aMenu as $i => $valor) {
            if (is_array($valor)) {
                $menu->valor_check($aPermisos[$i]['valor_raiz'], ' ');
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a1'); //nodo1
                foreach ($valor as $i2 => $valor2) {
                    $menu->valor_check($aPermisos[$i][$i2]['valor_raiz'], 'aplicable');
                    $nodo = $menu->Nuevo_Nodo($i2, 'a2');  //nodo2
                    if (is_array($valor2)) {
                        foreach ($valor2 as $i3 => $valor3) {
                            $menu->valor_check($aPermisos[$i][$i2][$i3], 'aplicable');
                            $nodo2 = $menu->Nuevo_Nodo($i3, 'a3');  //nodo3
                            $menu->Situa_Nodo($nodo2, $nodo);
                        }
                        $menu->Situa_Nodo($nodo, $nodo_padre);
                    }
                }
                $menu->Situa_Nodo($nodo_padre);
            } else {
                $menu->valor_check($aPermisos[$i]['valor_raiz'], 'aplicable');
                $nodo_padre = $menu->Nuevo_Nodo($i, 'a2');    //nodo2
                $menu->Situa_Nodo($nodo_padre);
            }
        }
    }



    $oPagina = new FakePage();
    $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');

    $sPremenu = $menu->Pinta_Arbol();
    $oPagina->addBodyContent($sPremenu);
    return $oPagina->toHTML();
}
