<?php
/**
 * Controller for Messages
 */

namespace Tuqan\Controllers;


use boton;
use Tuqan\Classes\Botones;
use Tuqan\Classes\FormManager;
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

        $oPager = $this->listMessages($_SESSION['userid'], true, $action, $aDatos );
        if($oPager) {
            $result = "contenedor|" . $oPager->muestra_Pagina();
        } else {
            $result = "contenedor|". gettext("There was an error, contact your system admin");
        }
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

    function anyNew() {
        $action=$_POST['action'];
        if(isset($_POST['datos'])) {
            $aDatos = $_POST['datos'];
        }
        else {
            $aDatos = array();
        }
        $aFormulario = array(
             'mensajes' => array(
                 array('etiqueta' => gettext('sFCOContenido') . ': ', 'columna' => 'contenido'),
                 array('etiqueta' => '', 'columna' => 'destinatario', 'hidden' => 'null'),
                 array('etiqueta' => '', 'columna' => 'activo', 'hidden' => 't'),
                 array('etiqueta' => '', 'columna' => 'origen', 'hidden' => $_SESSION['userid']),
                 array('etiqueta' => gettext('sFCOTitulo') . ': ', 'columna' => 'titulo'),
                )
            );
/*            if ($sTipoForm == 'UPDATE') {
                $aFormulario['mensajes']['id'] = $iId;
            }*/
        $form = new FormManager($action, $aDatos, $aFormulario);
        $result = 'contenedor|'.$form->process();
        return $result;
    }

    /**
     * @param $user
     * @param $active
     * @param $action
     * @param null $aDatos
     * @return generador_listados|bool
     */
    private function listMessages($user, $active, $action, $aDatos = null) {
        try {
            if($active){
                $active = 't';
            } else {
                $active = 'f';
            }
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
            $oDb->construir_Where(array("(destinatario=$user) OR (destinatario=0)", "(mensajes.activo='$active')",
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

            return $oPager;
        } catch (\Exception $e) {
            TuqanLogger::error("Exception:", ['Exception' => print_r($e,1)]);
            return false;
        }
    }
}
