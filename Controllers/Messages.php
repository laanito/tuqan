<?php
/**
 * Controller for Messages
 */

namespace Tuqan\Controllers;


use boton;
use Tuqan\Classes\Botones;
use Tuqan\Classes\generador_listados;
use Tuqan\Classes\Manejador_Base_Datos;
use Tuqan\Classes\TuqanLogger;

class Messages
{
    public function anyIndex()
    {
        $action=$_POST['action'];
        if(isset($_POST['datos'])) {
            $aDatos = $_POST['datos'];
        }
        else {
            $aDatos = array();
        }
        TuqanLogger::debug(
            "Arrived Mensajes Controller",
            ["action" => $action, 'aDatos' =>print_r($aDatos,1)]
        );
        $result = "contenedor|We arrived Mensajes controller";
        return $result;
    }

    /**
     * @return string
     */
    public function anyGet() {
 /*

                            case 'inicio:mensajes:ver:fila' :
                                $Comunes = new Procesar_Funciones_Comunes();
                                $this->sHtml = "contenedor|" . $Comunes->procesa_Ver_Mensaje($this->aParametros);
                                break;

                $sTabla = 'mensajes';
                $aCampos = array('mensajes.id', "mensajes.titulo as \"" . gettext('sPCTitulo') . "\"",
                    "to_char(mensajes.fecha, 'DD/MM/YYYY') as \"" . gettext('sPCEnviado') . "\"",
                    "to_char(mensajes.fecha, 'hh24:mi') as \"" . gettext('sPCHora') . "\"",
                    "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . gettext('sPCRemitente') . "\"");
                $aBuscar = array('nombres' => array('titulo', 'enviado'),
                    'campos' => array('titulo', 'fecha'));
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos($aCampos);
                $oDb->construir_Tablas(array($sTabla, 'usuarios'));
                if ($_SESSION['perfil'] != 0) {
                    $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ") OR (destinatario=0)", "(mensajes.activo='t')",
                        "usuarios.id=mensajes.origen"
                    ));
                } else {
                    $oDb->construir_Where(array("usuarios.id=mensajes.origen AND mensajes.activo='t'"));
                }
        $Listados = new Manejador_Listados();
        $aParametros = $Listados->prepara_Listado_Inicial($sMenu, $this->aDatos, $this->sCodigo);
        $aParametros['accion'] = $this->sCodigo;
        TuqanLogger::debug(
            "Parameter in case: ",
            ['aParametros'=>$aParametros]
        );*/


        $action=$_POST['action'];
        if(isset($_POST['datos'])) {
            $aDatos = $_POST['datos'];
        }
        else {
            $aDatos = array();
        }
        TuqanLogger::debug(
            "Arrived Mensajes Controller",
            ["action" => $action, 'aDatos' =>print_r($aDatos,1)]
        );
        $sTabla = 'mensajes';
        $aCampos = array('mensajes.id', "mensajes.titulo as \"" . gettext('sPCTitulo') . "\"",
            "to_char(mensajes.fecha, 'DD/MM/YYYY') as \"" . gettext('sPCEnviado') . "\"",
            "to_char(mensajes.fecha, 'hh24:mi') as \"" . gettext('sPCHora') . "\"",
            "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . gettext('sPCRemitente') . "\"");
        $aBuscar = array('nombres' => array('titulo', 'enviado'),
            'campos' => array('titulo', 'fecha'));
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array($sTabla, 'usuarios'));
        $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ") OR (destinatario=0)", "(mensajes.activo='t')",
                "usuarios.id=mensajes.origen"
            ));
        $oPager = new generador_listados($action, $oDb, $aDatos['pagina'], $aDatos['numLinks'],
            $aDatos['numPaginas'], $aDatos['order'], $aDatos['sentido'],
            $sTabla, $aDatos['where'], $aBuscar);
        $botones = Botones::getButtons($action);
        if (is_array($botones)) {
            foreach ($botones as $aBoton) {
                $oPager->agrega_Boton($aBoton[0], $aBoton[1], $aBoton[2]);
            }
        }

        $oPager->agrega_Desplegable(gettext('sElemPag'),
            $action, array($aDatos['numPaginas'], 10, 20, 30, 50));

        $result = "contenedor|". $oPager->muestra_Pagina();
        return $result;
    }


    /**
     * @param $aParametros
     * @return string
     */
    function getView($aParametros)
    {
        $iIdMensaje = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton(gettext('sBotonVolver'), "atras(-1)", "noafecta");
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('contenido'));
        $oBaseDatos->construir_Tablas(array('mensajes'));
        $oBaseDatos->construir_Where(array('id=\'' . $iIdMensaje . '\''));
        $oBaseDatos->consulta();
        $aFila = $oBaseDatos->coger_Fila();
        $sHtml = "<p align='center'><br /><span class='titulo'>" . gettext('sContMsj') . ": </span></p><span class='texto'> " .
            $aFila[0] . "</span><br /><p align='center'>" . $oVolver->to_Html() . "</p><br /><br />";
        return $sHtml;
    }
}
