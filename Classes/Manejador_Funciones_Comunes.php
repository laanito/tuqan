<?php
namespace Tuqan\Classes;
/**
 * @param $sAccion
 * @param $aDatos
 * @return array
 */

Class Manejador_Funciones_Comunes
{
    function prepara_AltaBaja_Filas($sAccion, $aDatos)
    {
        return array('accion' => $sAccion, 'filas' => $aDatos[0]);
    }

    /**
     * @param $sCodigo
     * @param $aParametros
     * @return array
     */
    function prepara_Iframe_Documento($sCodigo, $aParametros)
    {
        return array('tipo' => $aParametros[0], 'idtipo' => $aParametros[1], 'accion' => $sCodigo, 'vigor' => $aParametros[2]);
    }


    /**
     * funcion que recibe un array con las id de perfiles y los permisos y prepara los datos en 2 array
     */
    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Permisos($sAccion, $aDatos = null)
    {
        $aDatos = explode(separadorCadenas, $_REQUEST['datos']);
        $iLongDatos = count($aDatos);
        $aPerfiles = array();
        for ($i = 0; $i < $iLongDatos - 1; $i = $i + 2) {
            $aPerfiles[$aDatos[$i + 1]][] = $aDatos[$i];
        }
        return (array('id' => $aPerfiles, 'accion' => $sAccion));
    }


    /**
     * Esta funcion prepara el calendario
     * @param $sMenu
     * @param $aDatos
     * @param $sCodigo
     * @return array
     */

    function prepara_Calendario_Anual($sMenu, $aDatos, $sCodigo)
    {
        unset ($_SESSION['where']);
        unset ($_SESSION['ultimolistado']);
        unset ($_SESSION['ultimolistadodatos']);
        unset ($_SESSION['paginaanterior']);

        $_SESSION['ultimolistado'] = array($sCodigo);
        $_SESSION['ultimolistadodatos'] = array($aDatos);
        $_SESSION['paginaanterior'] = array($_SESSION['pagina']);
        if ($aDatos[0] == "") {
            $mes = getdate();
            return (array('accion' => 'equipos:calendario:listado:ver', 'agno' => $mes['year']));
        } else {
            return ($aParametros = array('accion' => 'equipos:calendario:listado:ver', 'agno' => $aDatos[0]));
        }
    }


    /**
     * Esta funcion prepara para ver documentos
     * @param $aDatos
     * @param $sCodigo
     * @return array
     */
    function prepara_Ver_Documento($aDatos, $sCodigo)
    {
        //Miramos si es interno o externo
        require_once 'Manejador_Base_Datos.class.php';
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('tipo_documento', 'id'));
        $oBaseDatos->construir_Tablas(array('documentos'));
        $oBaseDatos->construir_Where(array('id=' . $_SESSION['pagina'][$aDatos[0]]));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        $iId = $aIterador[1];

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('internoexterno', 'id'));
        $oBaseDatos->construir_Tablas(array('tipo_documento'));
        $oBaseDatos->construir_Where(array('id=' . $aIterador[0]));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        if ($aIterador[1] == iIdProceso) {
            $sAccion = 'catalogo:verdocumentoprocesosinfilahistorial:listado:ver:fila';
            return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $iId));
        } else if ($aIterador[0] == 'interno') {
            $aParametros['accion'] = 'editor:documento:editor:ver';
            $aParametros['iddoc'] = $_SESSION['pagina'][$aDatos[0]];
            return ($aParametros);
        } else {
            return (array('accion' => $sCodigo, 'documento' => $aDatos[0]));
        }

    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_Detalles_TareaDocumentoId($sCodigo, $aDatos)
    {
        return array('accion' => 'inicio:documentoid:detalles:ver:fila');
    }

    /**
     * Esta funcion prepara para ver una fila de un listado
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Ver_Fila($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $aDatos[1]));
    }


    /**
     *  Esta funcion prepara los arboles
     * @param $sAccion
     * @param $aDatos
     * @return array
     */

    function prepara_Arbol($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'userid' => $aDatos[0]));
    }


    /**
     * Esta funcion prepara para ver documentos en un sitio que no es un listado
     * @param $aDatos
     * @param $sCodigo
     * @return array
     */
    function prepara_Ver_DocumentoSinFila($aDatos, $sCodigo)
    {
        require_once 'Manejador_Base_Datos.class.php';
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('tipo_documento'));
        $oBaseDatos->construir_Tablas(array('documentos'));
        $oBaseDatos->construir_Where(array('id=' . $aDatos[0]));

        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('internoexterno'));
        $oBaseDatos->construir_Tablas(array('tipo_documento'));
        $oBaseDatos->construir_Where(array('id=' . $aIterador[0]));

        $oBaseDatos->consulta();

        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] == 'interno') {
            $aParametros['accion'] = 'editor:documento:editor:ver';
            $aParametros['iddoc'] = $aDatos[0];
            return ($aParametros);
        } else {
            return (array('accion' => $sCodigo, 'id' => $aDatos[0]));
        }
    }


    /**
     * Con esta funcion preparamos los datos para editar un documento, ademas comprobamos si esta el editor creado,
     * esta funcion es para cuando queremos editar desde un sitio que no es un listado
     * @param $aDatos
     * @param $sCodigo
     * @return array
     */

    function prepara_Editar_DocumentoSinFila($aDatos, $sCodigo)
    {
        $bEditor = 0;
        if ($_COOKIE['ed'] == 1) {
            $bEditor = 1;
        }
        require_once 'constantes.inc.php';
        require_once 'Manejador_Base_Datos.class.php';
        //Ponemos el nombre del tipo (esta en idiomas), y la id (esta en constantes.inc.php)
        switch ($aDatos[0]) {
            case iIdPg:
                {
                    $aParametros = array('tipo' => gettext('sPg'), 'idtipo' => iIdPg);
                    break;
                }
            case iIdPe:
                {
                    $aParametros = array('tipo' => gettext('sPe'), 'idtipo' => iIdPe);
                    break;
                }
            case iIdManual:
                {
                    $aParametros = array('tipo' => gettext('sManual'), 'idtipo' => iIdManual);
                    break;
                }
            case iIdExterno:
                {
                    $aParametros = array('tipo' => gettext('sTExterno'), 'idtipo' => iIdExterno);
                    break;
                }
            case iIdPolitica:
                {
                    $aParametros = array('tipo' => gettext('sTPoliticaI'), 'idtipo' => iIdPolitica);
                    break;
                }
            case iIdObjetivos:
                {
                    $aParametros = array('tipo' => gettext('sObjetivos'), 'idtipo' => iIdObjetivos);
                    break;
                }
            case iIdFichaMa:
                {
                    $aParametros = array('tipo' => gettext('sItma'), 'idtipo' => iIdFichaMa);
                    break;
                }
            case iIdArchivoProc:
                {
                    $aParametros = array('tipo' => gettext('sMIProceso'), 'idtipo' => iIdArchivoProc);
                    break;
                }

            default:
                {
                    $aParametros = array('tipo' => 'error');
                    break;
                }
        }
        $aParametros['editor'] = $bEditor;
        $aParametros['id'] = $aDatos[1];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('internoexterno'));
        $oBaseDatos->construir_Tablas(array('tipo_documento'));
        $oBaseDatos->construir_Where(array('id=' . $aParametros['idtipo']));

        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] == 'interno') {
            $aParametros['accion'] = 'editor:documento:editor:editar';
            $aParametros['idDocumento'] = $aDatos[1];
        } else {
            $aParametros['accion'] = $sCodigo;
        }
        return $aParametros;
    }


    /**
     * Funcion que prepara los datos para revisar un documento
     */
    function prepara_RevisarAprobar_Documento($aDatos)
    {
        return (array('docid' => $aDatos[0], 'userid' => $aDatos[1]));
    }


    /**
     * Preparamos los datos necesarios para añadir una tarea a un documento especifico
     * @param string $sCodigo
     * @param array $aDatos
     * @return array
     */

    function prepara_Tarea_Documento($aDatos)
    {
        return (array('origen' => $aDatos[1], 'documento' => $aDatos[0]));
    }


    function prepara_NuevaVersion_Documento($aDatos, $sCodigo)
    {
        $bEditor = 0;
        if ($_COOKIE['ed'] == 1) {
            $bEditor = 1;
        }
        require_once 'constantes.inc.php';
        require_once 'Manejador_Base_Datos.class.php';
        //Ponemos el nombre del tipo (esta en idiomas), y la id (esta en constantes.inc.php)
        switch ($aDatos[1]) {
            case iIdPg:
                {
                    $aParametros = array('tipo' => gettext('sPg'), 'idtipo' => iIdPg);
                    break;
                }
            case iIdPe:
                {
                    $aParametros = array('tipo' => gettext('sPe'), 'idtipo' => iIdPe);
                    break;
                }
            case iIdManual:
                {
                    $aParametros = array('tipo' => gettext('sManual'), 'idtipo' => iIdManual);
                    break;
                }
            case iIdPolitica:
                {
                    $aParametros = array('tipo' => gettext('sTPoliticaI'), 'idtipo' => iIdPolitica);
                    break;
                }
            case iIdObjetivos:
                {
                    $aParametros = array('tipo' => gettext('sTVerObjetivos'), 'idtipo' => iIdObjetivos);
                    break;
                }
            case iIdFichaMa:
                {
                    $aParametros = array('tipo' => gettext('sItma'), 'idtipo' => iIdFichaMa);
                    break;
                }
            case iIdExterno:
                {
                    $aParametros = array('tipo' => gettext('sItma'), 'idtipo' => iIdFichaMa);
                    break;
                }
            case iIdAai:
                {
                    $aParametros = array('tipo' => gettext('sIAai'), 'idtipo' => iIdAai);
                    break;
                }
            case iIdArchivoProc:
                {
                    $aParametros = array('tipo' => gettext('sMIProceso'), 'idtipo' => iIdArchivoProc);
                    break;
                }
            default:
                {
                    $aParametros = array('tipo' => 'error');
                    break;
                }
        }
        $aParametros['editor'] = $bEditor;
        $aParametros['id'] = $aDatos[0];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('internoexterno'));
        $oBaseDatos->construir_Tablas(array('tipo_documento'));
        $oBaseDatos->construir_Where(array('id=' . $aParametros['idtipo']));

        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] == 'interno') {
            $aParametros['accion'] = 'editor:documento:editor:nuevaversion';
            $aParametros['idDocumento'] = $aDatos[0];
            $aParametros['nuevaVersion'] = 1;
        } else {
            $aParametros['accion'] = $sCodigo;
        }
        return $aParametros;
    }


    /**
     * Esta funcion prepara el calendario de los formularios
     * @param array $aDatos
     * @return array
     */
    function prepara_Calendario_Mes($aDatos)
    {
        return (array('accion' => 'calendario', 'mes' => $aDatos[0], 'procesa' => $aDatos[1]));
    }


    /**
     * Esta funcion prepara los listados
     * @param array $aDatos
     * @return array
     */
    function prepara_Listado($aDatos)
    {
        if (($aDatos[1] == "undefined") || ($aDatos[1] < 1)) {
            $aDatos[1] = 1;
        }
        $aWhere = array();
        if ($aDatos[4] == "limpiar") {
            $aWhere = "limpiar";
        } else {
            $aSplitWhere = explode(separadorCadenas, $aDatos[4]);
            for ($iContador = 1; $aSplitWhere[$iContador] != null; $iContador = $iContador + 2) {
                $aWhere[$aSplitWhere[$iContador]] = $aSplitWhere[$iContador + 1];
            }
            $aSplitSelect = explode(separadorCadenas, $aDatos[5]);
            for ($iContador = 1; $aSplitSelect[$iContador] != null; $iContador = $iContador + 2) {
                $aSelect[$aSplitSelect[$iContador]] = $aSplitSelect[$iContador + 1];
            }
        }
        return (array('accion' => $aDatos[0], 'pagina' => $aDatos[1], 'order' => $aDatos[2], 'numLinks' => 5,
            'numPaginas' => $aDatos[3], 'where' => $aWhere, 'select' => $aSelect, 'botones' => $_SESSION['botones']));
    }


    /* Para preparar legislacion */
    function prepara_Ver_Legislacion($sCodigo = null, $aParametros)
    {
        //Debemos sacar el documento asociado a la ley y pasarselo a ver:documento
        require_once 'Manejador_Base_Datos.class.php';
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id_ficha'));
        $oBaseDatos->construir_Tablas(array('legislacion_aplicable'));
        $oBaseDatos->construir_Where(array('(id=\'' . $_SESSION['pagina'][$aParametros[0]] . '\')'));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            if ($aIterador[0] != null) {
                return (array('accion' => 'documentos:legislacion:comun:ver:fila', 'id' => $aIterador[0]));
            } else {
                return (array('accion' => 'documentos:legislacion:comun:fichanodef'));
            }
        } else {
            return (array('accion' => 'documentos:legislacion:comun:fichanodef'));
        }
    }


    function prepara_Ver_Grafica($sAccion, $aDatos)
    {
        return (array('accion' => 'catalogo:graficaindicador:listado:ver:fila', 'tipo' => $aDatos[0], 'modo' => $aDatos[1]));
    }


    function prepara_Listado_InicialSeleccionar($sAccion, $aDatos)
    {
        switch ($sAccion) {
            case 'documentosas:legislacion:fichama:selecciona':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "id_ficha";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'administracion:usuarios:seleccionaficha':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "ficha";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'documentacion:legislacion:seleccionaficha':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta"),
                    array(gettext('sMCNuevo'), "sndReq('documentacion:frlvigor:formulario:nuevo','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "fichaleg";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'documentacion:legislacion:seleccionaley':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta"),
                    array(gettext('sMCNuevo'), "sndReq('documentacion:documentonormativavigor:formulario:nuevo','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "ley";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'mejora:acmejora:seleccionausuario':
            case 'auditorias:auditor:seleccionausuario':
            case 'formacion:planes:seleccionausuario':
            case 'inicio:tarea:seleccionausuario':
                //    al boton afila le pasamos el nombre del campo
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "usuarios";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'inicio:tarea:seleccionaindicadores':
                //    al boton afila le pasamos el nombre del campo
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "indicadores";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'formacion:planes:seleccionadocumentoext':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(array(gettext('sMCOSeleccionar'), "sndReq('general:general:comun:aform:fila','',1)", "fila"),
                    array(gettext('sMCOCerrar'), "sndReq('inicio:general:comun:selecciona:cerrar','',1)", "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "documentosext";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;

            case 'administracion:usuarios:seleccionarequisitos':
                $_SESSION['campoform'] = $aDatos[0];
                $aBotones = array(
                    array(
                        gettext('sMCOSeleccionar'),
                        "sndReq('general:general:comun:aform:fila','',1)",
                        "fila"),
                    array(
                        gettext('sMCOCerrar'),
                        "sndReq('inicio:general:comun:selecciona:cerrar','',1)",
                        "noafecta")
                );
                $_SESSION['botones'] = $aBotones;
                $_SESSION['listadoaform'] = "requisitos";
                return (array('accion' => $sAccion, 'pagina' => 1,
                    'order' => gettext('sMCONombre') . " ASC", 'numLinks' => 5, 'numPaginas' => 20, 'botones' => $aBotones,
                    'campo' => $aDatos[0]));
                break;
        }
    }

    /**
     * @param $sCodigo
     * @param $aDatos
     * @return array
     */
    function prepara_MandarAFormulario($sCodigo, $aDatos)
    {
        $aParametros = array('accion' => $sCodigo, 'campo' => $_SESSION['campoform'], 'fila' => $aDatos[0]);
        return ($aParametros);
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_Alta_Baja($sAccion, $aDatos)
    {
        return (array('accion' => $sAccion, 'numeroDeFila' => $aDatos[0], 'proviene' => $aDatos[1]));
    }

    /**
     * @param $sAccion
     * @param $aDatos
     * @return array
     */
    function prepara_NuevaVersion_DocumentoProceso($sAccion, $aDatos)
    {
        $aParametros = array('accion' => $sAccion);
        $aParametros['documento'] = $aDatos[0];
        $aParametros['ficha'] = $aDatos[1];
        return $aParametros;
    }


    /**
     * Esta funcion prepara los menus o las acciones simples
     * @param string $sAccion
     * @return array
     */

    function prepara_Menu($sAccion, $aDatos)
    {
        if ($aDatos[0]) {
            return (array('accion' => $sAccion, 'proviene' => $aDatos[0]));
        } else {
            return (array('accion' => $sAccion));
        }
    }

    function prepara_Datos_Permisos_Botones($iId, $sAccion)
    {
        $aParametros['id'] = $iId;
        $aParametros['accion'] = $sAccion;
        return $aParametros;
    }

    function prepara_Datos_Permisos_Menu($iId, $sAccion)
    {
        $aParametros['id'] = $iId;
        $aParametros['accion'] = $sAccion;
        return $aParametros;
    }

    function prepara_Permisos_Botones($sAccion, $aDatos)
    {
        $aPermisos = str_split(trim($aDatos[0]));
        foreach ($aPermisos as $sKey => $sValue) {
            $aPerfiles[$sKey] = $sValue;
        }
        return (array('id' => $aPerfiles, 'accion' => $sAccion));
    }

    function prepara_Permisos_Menu($sAccion, $aDatos)
    {
        $aPermisos = str_split(trim($aDatos[0]));
        foreach ($aPermisos as $sKey => $sValue) {
            $aPerfiles[$sKey] = $sValue;
        }
        return (array('id' => $aPerfiles, 'accion' => $sAccion));
    }
}