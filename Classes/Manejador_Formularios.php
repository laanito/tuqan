<?php
namespace Tuqan\Classes;


class Manejador_Formularios
{

    /**
     * Esta funcion prepara los formularios
     * @param array $aDatos
     * @return array
     */

    function prepara_Formulario($aDatos)
    {
        if ($aDatos[1] == null) {
            return (array('accion' => 'formulario', 'formulario' => $aDatos[0]));
        } else {
            return (array('accion' => 'formulario', 'formulario' => $aDatos[0], 'id' => $aDatos[1]));
        }
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Formulario_Inicial_EditarId($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'id' => $aDatos[0]));
    }

    /**
     * @param $sAccion
     * @return array
     */
    function prepara_Formulario_Inicial($sAccion)
    {
        return (array('accion' => $sAccion));
    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_Formulario_Medio($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'idlegislacion' => $aDatos[0]));
    }


    /**
     * Esta funcion prepara los formularios
     * @param string $sAccion
     * @return array
     */

    function prepara_Formulario_Inicial_Calidad($sAccion)
    {
        return (array('accion' => $sAccion));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Formulario_Inicial_Editar($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0]));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Formulario_Inicial_Editar_Comun($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0]));
    }

    /**
     * @param $sAccion
     * @param null $aDatos
     * @param $sAccionCompleta
     * @return array
     */
    function prepara_Nuevo_Documento($sAccion, $aDatos = null, $sAccionCompleta)
    {
        switch ($sAccion) {
            case 'documentacion:documentopg:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sPg'), 'idtipo' => iIdPg);
                    break;
                }

            case 'documentacion:documentoaai:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sAai'), 'idtipo' => iIdAai);
                    break;
                }

            case 'documentacion:documentope:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sPe'), 'idtipo' => iIdPe);
                    break;
                }

            case 'documentacion:planemeramb:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sPe'), 'idtipo' => iIdPlanAmb);
                    break;
                }

            case 'documentacion:manual:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sManual'), 'idtipo' => iIdManual);
                    break;
                }
            case 'documentacion:frlvigor:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sItma'), 'idtipo' => iIdFichaMa, 'vigor' => true);
                    break;
                }
            case 'documentacion:frl:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sItma'), 'idtipo' => iIdFichaMa);
                    break;
                }
            case 'documentacion:objetivos:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sObjetivos'), 'idtipo' => iIdObjetivos);
                    break;
                }
            case 'documentacion:pambiental:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sPolitica'), 'idtipo' => iIdPolitica);
                    break;
                }
            case 'documentacion:formatos:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sExterno'), 'idtipo' => iIdExterno);
                    break;
                }
            case 'documentacion:documentonormativavigor:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sNormativa'), 'idtipo' => iIdNormativa, 'vigor' => true);
                    break;
                }
            case 'documentacion:documentonormativa:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sNormativa'), 'idtipo' => iIdNormativa);
                    break;
                }
            case 'documentacion:documentoarchivoproceso:nuevo':
                {
                    $aParametros = array('tipo' => gettext('sMIProceso'), 'idtipo' => iIdArchivoProc);
                    break;
                }
            case 'auditorias:adjunto:nuevo:fila':
                {
                    $aParametros = array('tipo' => gettext('sAdjunto'), 'idtipo' => iIdAdjunto);
                    $_SESSION['auditor'] = $_SESSION['pagina'][$aDatos[0]];
                    unset($_SESSION['cursofirmas']);
                    unset($_SESSION['seguimiento']);
                    break;
                }
            case 'documentacion:seguimiento:nuevo:fila':
                {
                    $aParametros = array('tipo' => gettext('sAdjunto'), 'idtipo' => iIdAdjunto);
                    $_SESSION['seguimiento'] = $_SESSION['pagina'][$aDatos[0]];
                    unset($_SESSION['cursofirmas']);
                    unset($_SESSION['auditor']);
                    break;
                }
            case 'formacion:adjunto:nuevo:fila':
                {
                    $aParametros = array('tipo' => gettext('sAdjunto'), 'idtipo' => iIdAdjunto);
                    $_SESSION['cursofirmas'] = $_SESSION['pagina'][$aDatos[0]];
                    unset($_SESSION['auditor']);
                    unset($_SESSION['seguimiento']);
                    break;
                }
            default:
                {
                    $aParametros = array('tipo' => 'error');
                    break;
                }
        }
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('internoexterno'));
        $oBaseDatos->construir_Tablas(array('tipo_documento'));
        $oBaseDatos->construir_Where(array('id=' . $aParametros['idtipo']));

        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] == 'interno') {
            $aParametros['accion'] = 'editor:documento:editor:nuevo';
        } else {
            $aParametros['accion'] = $sAccionCompleta;
        }
        return $aParametros;
    }


    /**
     * Esta funcion prepara los formularios
     * @param string $sAccion
     * @return array
     */

    function prepara_Formulario_Inicial_Comun($sAccion)
    {
        return (array('accion' => $sAccion));
    }


    /**
     * Funcion para preparar los datos del cuestionario
     */
    function prepara_Cuestionario($sCodigo, $aDatos)
    {
        return (array('accion' => $sCodigo, 'numeroDeFila' => $aDatos[0]));
    }


    /**
     * Esta funcion prepara para agregar un curso al plan vigente
     * @param array $aDatos
     * @return array
     */

    function prepara_Agregar_Fila_Comun($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $aDatos[1]));
    }

}