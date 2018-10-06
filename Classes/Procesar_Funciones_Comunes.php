<?php
namespace Tuqan\Classes;

use \boton;
use \encriptador;
use \desplegable;
use \htmlcleaner;
use \AnualCalendar;
use \BaseCalendar;
use Surface\Surface;

class Procesar_Funciones_Comunes
{

    /**
     * Esta funcion
     * nos verifica una accion mejora
     *
     * @param $sAccion array
     * @param $aParametros array
     * @return string
     */
    function procesa_Verificar_Mejora($sAccion, $aParametros)
    {
        $iIdMejora = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $sHtml='';
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Primero miramos que la accion no este cerrada.
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('cerrada'));
        $oBaseDatos->construir_Tablas(array('acciones_mejora'));
        $oBaseDatos->construir_Where(array('id=' . $iIdMejora));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            if ($aIterador[0] == true) {
                $sHtml = "alert|" . gettext('sAccYaCerrada');
            } else {
                $oBaseDatos->iniciar_Consulta('UPDATE');
                $oBaseDatos->construir_SetSin(array('usuario_verifica', 'fecha_verifica'),
                    array($_SESSION['userid'], 'now()'));
                $oBaseDatos->construir_Tablas(array('acciones_mejora'));
                $oBaseDatos->construir_Where(array('id=' . $iIdMejora));
                $oBaseDatos->consulta();
                $sHtml = "alert|" . gettext('sAccVerificada');
            }
        }
        return $sHtml;
    }


    /**
     * Funcion que recibe un array con todos los permisos y los actualiza en la tabla tipo_documento
     * @param $aParametros
     * @return string
     */
    function procesar_Permisos_Documentos($aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iNumPerfiles = 7;
        $iIdDoc = $_SESSION['idDoc'];
        // Arrays de valores
        $aValores = array(0 => 'f',
            1 => 't');
        $aPerfiles = array(0 => 'perfil_ver',
            1 => 'perfil_nueva',
            2 => 'perfil_modificar',
            3 => 'perfil_revisar',
            4 => 'perfil_aprobar',
            5 => 'perfil_historico',
            6 => 'perfil_tareas');
        $oBaseDatos->comienza_transaccion();
        foreach ($aParametros['id'] as $key => $value) {
            for ($iIterador = 0; $iIterador < $iNumPerfiles; $iIterador++) {
                $oBaseDatos->iniciar_Consulta('UPDATE');
                $oBaseDatos->construir_Set(array($aPerfiles[$iIterador] . '[' . $key . ']'),
                    array($aValores[$value[$iIterador]]));
                $oBaseDatos->construir_Tablas(array('documentos'));
                $oBaseDatos->construir_Where(array('id=' . $iIdDoc));
                $oBaseDatos->consulta();
            }
        }
        $oBaseDatos->termina_transaccion();
        $oVolver = new boton(gettext('sPNVolver'), "atras(-2)", "noafecta");
        return "contenedor|" . gettext('sPNPermisosAct') . "<br />" . $oVolver->to_Html();
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesar_Permisos($aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iNumPerfiles = 7;
        // Arrays de valores
        $aValores = array(0 => 'f',
            1 => 't');
        $aPerfiles = array(0 => 'perfil_ver',
            1 => 'perfil_nueva',
            2 => 'perfil_modificar',
            3 => 'perfil_revisar',
            4 => 'perfil_aprobar',
            5 => 'perfil_historico',
            6 => 'perfil_tareas');
        $oBaseDatos->comienza_transaccion();
        foreach ($aParametros['id'] as $key => $value) {
            for ($iIterador = 0; $iIterador < $iNumPerfiles; $iIterador++) {
                $oBaseDatos->iniciar_Consulta('UPDATE');
                $oBaseDatos->construir_Set(array($aPerfiles[$iIterador] . '[' . $key . ']'),
                    array($aValores[$value[$iIterador]]));
                $oBaseDatos->construir_Tablas(array('tipo_documento'));
                $oBaseDatos->construir_Where(array('id=' . $_SESSION['idDocPermisos']));
                $oBaseDatos->consulta();
            }
        }
        $oBaseDatos->termina_transaccion();
        $oVolver = new boton(gettext('sPNVolver'), "atras(-2)", "noafecta");
        return "contenedor|" . gettext('sPNPermisosAct') . "<br />" . $oVolver->to_Html();

    }

    /**
     * @param null $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Cerrar_Mejora($sAccion = null, $aParametros)
    {
        $iIdMejora = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('UPDATE');
        $oBaseDatos->construir_SetSin(array('usuario_cerrado', 'fecha_cierre', 'cerrada'),
            array($_SESSION['userid'], 'now()', 'true'));
        $oBaseDatos->construir_Tablas(array('acciones_mejora'));
        $oBaseDatos->construir_Where(array('id=' . $iIdMejora));
        $oBaseDatos->consulta();
        $sHtml = "alert|" . gettext('sAccCerrada');
        return $sHtml;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Calendario($aParametros)
    {
        $aFechas = array();
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $aCampos = array('equipos.id', 'equipos.descripcion',
            'case when equipos.dias then extract(epoch from (max(mantenimientos.fecha_realiza)+(equipos.mantenimiento_cada*interval \'1 day\'))) 
                    else extract(epoch from (max(mantenimientos.fecha_realiza)+(equipos.mantenimiento_cada*interval \'1 month\'))) end');

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos($aCampos);
        $oBaseDatos->construir_Tablas(array('mantenimientos', 'equipos'));
        $oBaseDatos->construir_Where(array('equipos.id=mantenimientos.equipo', 'equipos.activo=true'));
        $oBaseDatos->construir_Group(array('equipos.id', 'equipos.numero', 'equipos.descripcion',
            'equipos.mantenimiento_cada', 'equipos.dias'));
        $oBaseDatos->consulta();
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $aFechas[$aIterador[2]] = array($aIterador[0], $aIterador[1]);
        }
        $oCalendario = new AnualCalendar($aParametros['agno'], 'equipos:calendario:listado:ver', $aFechas);
        return ($oCalendario->displayAgno());
    }

    /**
     * @param $iNivel
     * @param $iIdPadre
     * @return array
     */
    function sacar_Id_Procesos($iNivel, $iIdPadre)
    {
        $aNodos = array();
        if ($iNivel < 6) {
            $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id'));
            $oDb->construir_Tablas(array('procesos'));
            $oDb->construir_Where(array('padre=' . $iIdPadre));
            $oDb->construir_Order(array('nombre'));
            $oDb->consulta();
            //Cogemos todos los hijos
            while ($aIterador = $oDb->coger_Fila()) {
                $aNodos[] = $aIterador[0];
                $aNodos = array_merge($aNodos, $this->sacar_Id_Procesos($iNivel + 1, $aIterador[0]));
            }
        }
        return $aNodos;
    }

    /**
     * @param $aProcesos
     * @param $aDatos
     * @param $iNumero
     * @return array
     */
    function devuelve_Datos($aProcesos, $aDatos, $iNumero)
    {
        $result = array();
        if ((is_array($aProcesos)) && (is_array($aDatos))) {
            foreach ($aProcesos as $sKey => $sValor) {
                if (is_array($aDatos[$sKey])) {
                    $result[]=$aDatos[$sKey][$iNumero];
                } //Si son indicadores devolvemos el array
                 else {
                     $result[]='';
                }
            }
        }
        return $result;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Matriz_Procesos($sAccion, $aParametros)
    {
        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $iId = $aParametros['numeroDeFila'];
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $aProcesos = array($iId);
        $aProcesos = array_merge($aProcesos, $this->sacar_Id_Procesos(2, $iId));
        //Sacamos todos los datos de los procesos
        $sHtml='';
        if (is_array($aProcesos)) {
            $aDatos = array();
            foreach ($aProcesos as $sValor) {
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('proveedor', 'entradas', 'propietario', 'indicadores',
                    'salidas', 'cliente', 'instalaciones_ambiente', 'doc_asociada', 'registros', 'contenido_procesos.id'));
                $oDb->construir_Tablas(array('contenido_procesos,documentos'));
                $oDb->construir_Where(array('contenido_procesos.proceso=' . $sValor, 'contenido_procesos.documento=documentos.id',
                    'documentos.estado=' . iVigor));
                $oDb->consulta();
                if ($aIterador = $oDb->coger_Fila()) {
                    $aDatos[] = $aIterador;
                    $iIdFicha = $aIterador[9];
                } else {
                    $aDatos[] = "vacio";
                }
            }
            $aTableAttrs = array('class' => 'table table-responsive');
            $oTable = new Surface($aTableAttrs);
            $header=array();
            $rows=array();
            $header[]='';
            foreach ($aProcesos as $sValor) {
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array('nombre'));
                $oDb->construir_Tablas(array('procesos'));
                $oDb->construir_Where(array('id=' . $sValor));
                $oDb->consulta();
                if ($aIterador = $oDb->coger_Fila()) {
                    $header[]= $aIterador[0];
                }
            }
            $row = array();
            $row[] =  "<b>" . gettext('sMatrProv') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 0) as $value){
                $row[]=$value;
            }
            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrEntrada') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 1) as $value){
                $row[]=$value;
            }
            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrPropietario') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 2) as $value){
                $row[]=$value;
            }

            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrIndic') . "</b>";

            if (isset($iIdFicha)) {
                foreach ($aProcesos as $sValor) {
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos(array('indicadores.nombre'));
                    $oDb->construir_Tablas(array('indicadores', 'contenido_procesos'));
                    $oDb->construir_Where(array('indicadores.id=any(contenido_procesos.indicadores)',
                        'contenido_procesos.id=' . $iIdFicha));
                    $oDb->consulta();
                    $cell = "<ul>";
                    while ($aIterador = $oDb->coger_Fila()) {
                        $cell .= "<li>" . $aIterador[0] . "</li>";
                    }
                    $cell .= "</ul>";
                    $row[]=$cell;
                }
            }
            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrSalida') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 4) as $value){
                $row[]=$value;
            }

            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrCliente') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 5) as $value){
                $row[]=$value;
            };

            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrInstal') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 6) as $value){
                $row[]=$value;
            };

            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrDocAsoc') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 7) as $value){
                $row[]=$value;
            };

            $rows[]=$row;
            // new line
            $row=array();
            $row[] =  "<b>" . gettext('sMatrReg') . "</b>";
            foreach($this->devuelve_Datos($aProcesos, $aDatos, 8) as $value){
                $row[]=$value;
            };

            $rows[]=$row;
            // new line
            $row=array();

            /**
             * Sacamos los flujogramas de las fichas de procesos, nos llega el array aProcesos, del cual sacamos
             * el proceso en cuestion, sacamos los flujogramas de la ficha del proceso y agrupamos en columna
             */
            if (isset($iIdFicha)) {

                $row[] = "<b>" . gettext('sBotonFlujog') . ": </b>";

                foreach ($aProcesos as $sValor) {
                    $oDb->iniciar_Consulta('SELECT');
                    $oDb->construir_Campos(array('flujogramas.id'));
                    $oDb->construir_Tablas(array('flujogramas', 'contenido_procesos', 'procesos'));
                    $oDb->construir_Where(array('flujogramas.proceso=contenido_procesos.id', 'procesos.id=' . $sValor,
                        'contenido_procesos.proceso=procesos.id'));
                    $oDb->consulta();
                    $cell= "";
                    while ($aIterador = $oDb->coger_Fila()) {
                        $cell .= "<img src=\"muestrabinario.php?id=" . $aIterador[0] .
                            "&tipo=imagent\" onclick=\"window.open('paginaBinario.php?id=" . $aIterador[0] .
                            "&tipo=imagen','proceso','top=0,left=0,directories=no,height=1024,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,toolbar=no,width=1280')\";/><br />";
                    }
                    $row[]=$cell;
                }
            }
            $rows[]=$row;
            $sTabla = $oTable->setHead($header)
                ->addRows($rows)
                ->render();
            $sHtml .= "$sTabla";
        }
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_AltaBaja_DocumentoProceso($sAccion, $aParametros)
    {
        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdProceso = $_SESSION['contenido_proceso'];
        $aSplit = explode(":", $sAccion);
        $oBaseDatos->iniciar_Consulta('SELECT');
        if ($aSplit[1] == 'documentoproceso') {
            $oBaseDatos->construir_Campos(array('anejos'));
        } else if ($aSplit[1] = 'indicadores') {
            $oBaseDatos->construir_Campos(array('indicadores'));
        }
        $oBaseDatos->construir_Tablas(array('contenido_procesos'));
        $oBaseDatos->construir_Where(array('id=' . $iIdProceso));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sDocumentos = $aIterador[0];
        }

        $sDocumentos = trim($sDocumentos, "{}");
        $aDocumentos = explode(",", $sDocumentos);

        $aTrocear = explode(separadorCadenas, $aParametros['filas']);
        $aFilas = str_split($aTrocear[0]);

        $aUltimo = count($aDocumentos);
        //Comenzamos una transaccion
        $oBaseDatos->comienza_transaccion();
        for ($iContador = 0; $iContador < (count($aFilas)); $iContador++) {
            $iIdDocumento = $_SESSION['pagina'][$iContador];
            if ($aFilas[$iContador] == 1) {
                if ($aSplit[2] == 'baja') {
                    if ($aSplit[1] == 'documentoproceso') {
                        $sHtml = gettext('sProcQuitarDoc') . "<br />";
                    } else {
                        $sHtml = gettext('sProcQuitarInd') . "<br />";
                    }
                    $iKey = array_search($iIdDocumento, $aDocumentos);
                    unset($aDocumentos[$iKey]);
                    //Buscamos en el array y lo quitamos
                } else {
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    if ($aSplit[1] == 'documentoproceso') {
                        $sHtml = gettext('sProcPonerDoc') . "<br />";
                        $oBaseDatos->construir_SetSin(array('anejos'),
                            array('array_append(anejos,' . $iIdDocumento . ')'));
                    } else {
                        $sHtml = gettext('sProcPonerInd') . "<br />";
                        $oBaseDatos->construir_SetSin(array('indicadores'),
                            array('array_append(indicadores,' . $iIdDocumento . ')'));
                    }
                    $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                    $oBaseDatos->construir_Where(array('id=' . $iIdProceso));
                    $oBaseDatos->consulta();
                }
            }
        }
        if ($aSplit[2] == 'baja') {
            $sDocumentos = "{" . implode(",", $aDocumentos) . "}";
            $oBaseDatos->iniciar_Consulta('UPDATE');
            if ($aSplit[1] == 'documentoproceso') {
                $oBaseDatos->construir_Set(array('anejos'),
                    array($sDocumentos));
            } else {
                $oBaseDatos->construir_Set(array('indicadores'),
                    array($sDocumentos));
            }
            //Updatear la db
            $oBaseDatos->construir_Tablas(array('contenido_procesos'));
            $oBaseDatos->construir_Where(array('id=' . $iIdProceso));
            $oBaseDatos->consulta();
        }
        $oBaseDatos->termina_transaccion();
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }


    /**
     * @param integer $iFila
     * @param integer $iId
     * @return string
     */
    function mostrar_Documento($iFila = null, $iId = null)
    {
        if ($iFila != null) {
            $iDocumento = $_SESSION['pagina'][$iFila];
        } else {
            $iDocumento = $iId;
        }
        $oBoton = new boton(gettext('sPCOVolver'), "atras(-1)", "noafecta");

        /**
         *    tenemos que sacar por un lado codigo,revision,estado y revisado por de documentos y luego el contenido
         * de contenido_texto
         */

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('documentos.codigo,documentos.nombre,documentos.revision,estados_documento.nombre,' .
            'documentos.revisado_por', 'documentos.tipo_documento'));
        $oBaseDatos->construir_Tablas(array('documentos', 'estados_documento'));

        $oBaseDatos->construir_Where(array('(documentos.id=\'' . $iDocumento . '\')', 'documentos.estado=estados_documento.id'));
        if ($_SESSION['userid'] != 0) {
            $oBaseDatos->pon_Where('documentos.perfil_ver[' . $_SESSION['perfil'] . ']=true');
        }
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            //Ahora si hay un usuario que haya revisado el documento sacamos su nombre
            if ($aIterador[4] != null) {
                $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
                $oBaseDatos->construir_Tablas(array('usuarios'));
                $oBaseDatos->construir_Where(array('usuarios.id=\'' . $aIterador[4] . '\''));
                $oBaseDatos->consulta();
                $aUsuario = $oBaseDatos->coger_Fila();
            }

            //Ahora mostramos dependiendo si es un documento normal, un documento de un proceso o un documento externo
            if (($aIterador[5] < iIdExterno) || ($aIterador[5] == iIdAai) || ($aIterador[5] == iIdFichaMa)) {
                //Es que es un documento normal
                $aTableAttrs = array('class' => 'table table-responsive documento');
                $oTable = new Surface($aTableAttrs);
                $rows=array();
                $row = array();
                $row[]= "<span class=\"campo\">" . gettext('sDocumento') ."</span>" . $aIterador[1];
                $rows[]=$row;
                $row = array();
                $row[]= "<span class=\"campo\">" . gettext('sDocRevision') ."</span>" . $aIterador[2];
                $row[]= "<span class=\"campo\">" . gettext('sDocEstado') ."</span>" . $aIterador[3];
                $row[]= "<span class=\"campo\">" . gettext('sDocRevisado') ."</span>" . $aUsuario[0];
                $rows[]=$row;
                $sTabla = $oTable->addRows($rows)
                    ->render();
                $sHtml = $sTabla;

                //Ahora sacamos el contenido
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('contenido'));
                $oBaseDatos->construir_Tablas(array('contenido_texto'));
                $oBaseDatos->construir_Where(array('(id=\'' . $iDocumento . '\')'));
                $oBaseDatos->consulta();
                if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                    $oLimpiador = new htmlcleaner();
                    $sContenido = $oLimpiador->cleanup($aIteradorInterno[0]);
                    $sHtml .= "<div align='center'>";
                    $sHtml .= $sContenido;
                    $sHtml .= "</div>";
                }
                $sHtml .= "<br /><hr>" . $oBoton->to_Html();
                $sHtml = "contenedor|" . $sHtml;
            } else if (($aIterador[5] == iIdExterno) || ($aIterador[5] == iIdPolitica)) {
                //Si es un documento externo lo sacamos por pantalla
                $sHtml = "contenedor|<iframe id=\"docext\" src=\"muestrabinario.php?id=" . $iDocumento .
                    "&tipo=documento\" width=\"100%\" height=\"600px\" frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
            } else {
                //Primero vemos si el documento es en vigor, en caso contrario no podemos mostrar una ficha
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('estado'));
                $oBaseDatos->construir_Tablas(array('documentos'));
                $oBaseDatos->construir_Where(array('(documentos.id=\'' . $iDocumento . '\')'));
                $oBaseDatos->consulta();
                if ($aEstado = $oBaseDatos->coger_Fila()) {
                    if ($aEstado[0] != iVigor) {
                        $sHtml = "contenedor|" . gettext('sNoFichBorr') . "<br />" . $oBoton->to_Html();
                    } else {
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('contenido_procesos.proceso'));
                        $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos',));
                        $oBaseDatos->construir_Where(array('(documentos.id=\'' . $iDocumento . '\')', 
                            'contenido_procesos.documento=documentos.id',
                        ));
                        $oBaseDatos->consulta();
                        if ($aProceso = $oBaseDatos->coger_Fila()) {
                            $sHtml = "contenedor|" . $this->procesa_Ver_Ficha_Proceso(null, 
                                    array('numeroDeFila' => $aProceso[0]));
                        }
                    }
                }
            }
        } else {
            $sHtml = "contenedor|" . gettext('sNoPermisoDoc') . "<br />" . $oBoton->to_Html();
        }
        return $sHtml;
    }

    /**
     * Esta funcion crea el editor en caso de que no este creado y lo prepara para insertar el documento
     * @param $aParametros
     * @return string
     */

    function procesa_Nuevo_Documento($aParametros)
    {
        $_SESSION['tipodoc'] = $aParametros['tipo'];
        $_SESSION['idtipo'] = $aParametros['idtipo'];
        if ($_SESSION['idtipo'] == iIdManual ) {
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('tipo_documento=' . iIdManual));
            $oBaseDatos->consulta();
            if ($aEstado = $oBaseDatos->coger_Fila()) {
                $oBoton = new boton(gettext('sPCOVolver'), "atras(-1)", "noafecta");
                return $sHtml = "contenedor|" . gettext('sYaManual') . "<br />" . $oBoton->to_Html();
            }
        }
        if ($aParametros['editor'] == 0) {
            //Esto quiere decir que el editor aun no esta creado por lo que debemos crearlo
            $sHtml = "diveditor|<iframe id=\"FCKEDITOR\" src=\"fckeditor.php?codigo=&nombre=&datos=\" width=\"100%\" height=\"600px\"" .
                "frameborder=\"0\" scrolling=\"auto\" style=\"z-index: 0\"><\iframe>|";
        } else {

            $sHtml = "sacareditor|" . gettext('sDocNuevo') . $aParametros['tipo'] . separadorCadenas . separadorCadenas . separadorCadenas . "0";
        }
        return $sHtml;
    }


    /**
     * Damos de alta o de baja los criterios de los productos
     * @param array $aParametros
     * @param string $sAccion
     * @return String
     */
    function procesa_AltaBaja_Criterios($sAccion, $aParametros)
    {
        $oVolver = new boton(gettext('sMCVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $aSplit = explode(":", $sAccion);
        $aTroceo = explode(separadorCadenas, $aParametros['filas']);
        $aFilas = str_split($aTroceo[0]);
        $sHtml = "";
        //Sacamos los criterios que teniamos
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('criterios'));
        $oBaseDatos->construir_Tablas(array('productos'));
        $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sCriterios = $aIterador[0];
        }
        //Si es null debemos ponerlo a {}
        if (strlen($sCriterios) == 0) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('criterios'),
                array('{}'));
            $oBaseDatos->construir_Tablas(array('productos'));
            $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
            $oBaseDatos->consulta();
        } else {
            $aCriterios = str_split($sCriterios);
        }
        //En $aCriterios tenemos todos los criterios del producto actualmente
        //Comenzamos una transaccion
        $oBaseDatos->comienza_transaccion();
        for ($iContador = 0; $iContador < (count($aFilas)); $iContador++) {
            $iIdCriterio = $_SESSION['pagina'][$iContador];
            if ($aFilas[$iContador] == 1) {
                if ($aSplit[3] == 'baja') {
                    $sHtml .= gettext('sCritQuitar') . "<br />";
                    $iKey = array_search($iIdCriterio, $aCriterios);
                    unset($aCriterios[$iKey]);
                    if ($iKey != 1) {
                        unset ($aCriterios[$iKey - 1]);
                    } else if ($aCriterios[$iKey + 1] != "}") {
                        unset ($aCriterios[$iKey + 1]);
                    }
                    //Buscamos en el array y lo quitamos
                } else {
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_SetSin(array('criterios'),
                        array('array_append(criterios,' . $iIdCriterio . ')'));
                    $oBaseDatos->construir_Tablas(array('productos'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
                    //$aConsultas[]=$oBaseDatos->to_String_Consulta();
                    $oBaseDatos->consulta();
                    $sHtml .= gettext('sCritPoner') . "<br/><br/>";
                }
            }
        }
        if ($aSplit[3] == 'baja') {
            $sCriterios = implode("", $aCriterios);
            $oBaseDatos->iniciar_Consulta('UPDATE');
            //Si no queda nada en el array lo ponemos a null(necesario)
            if ($sCriterios == "{}") {
                $oBaseDatos->construir_SetSin(array('criterios'),
                    array('NULL'));
            } else {
                $oBaseDatos->construir_Set(array('criterios'),
                    array($sCriterios));
            }
            //Updatear la db
            $oBaseDatos->construir_Tablas(array('productos'));
            $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
            $oBaseDatos->consulta();
        }
        $oBaseDatos->termina_transaccion();
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }


    function procesa_Iframe_Documento($sAccion, $aParametros)
    {


        $oVolver = new boton("Volver", "parent.atras(-2)", "noafecta");
        $sPolitica = $aParametros['numeroDeFila'];
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $_SESSION['subirfichero'] = $aParametros['idtipo'];
        if (($aParametros['idtipo'] == iIdManual) OR ($aParametros['idtipo'] == iIdPolitica)) {
            //Comprobamos si no existia ya un documento por que si es asi no permitimos subir otro
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('tipo_documento=' . $aParametros['idtipo'], "activo='t'"));
            $oBaseDatos->consulta();

            if ($aDevolver = $oBaseDatos->coger_Fila()) {
                $bExiste = true;
            } else {
                $bExiste = false;
            }
        }

        if (!$bExiste) {
            switch ($aParametros['idtipo']) {
                case iIdPg:
                case iIdPe:
                case iIdArchivoProc:
                case iIdExterno:
                case iIdFichaMa:
                case iIdNormativa:
                case iIdPlanAmb:
                case iIdAai:
                    {
                        $bExtra = true;
                        $sFormularioExtra = "<br /><br />Nombre:&nbsp;&nbsp;" .
                            "<input type=\"text\" id=\"nombredoc\" name=\"nombredoc\">" .
                            "&nbsp;&nbsp;&nbsp;&nbsp;Codigo:&nbsp;&nbsp;" .
                            "<input type=\"text\" id=\"codigodoc\" name=\"codigodoc\">";
                        break;
                    }
            }

            $oPagina->addScript("javascript/checkeditor.js", "text/javascript");


            $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\" onsubmit=\"return checkform(this);\">" .
                "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
                "<input type=\"hidden\" name=\"documento\"";
            if (isset($iIdProc)) {
                $sHtml .= " value=\"" . $iIdProc . "\">";
            } else {
                $sHtml .= ">";
            }
            $sHtml .= "<input type=\"hidden\" name=\"areadoc\" id=\"areadoc\" value=\"0\">" .
                "<input name=\"userfile\" type=\"file\">";

            if ($aParametros['vigor'] != null) {
                $sHtml .= "<input type=\"hidden\" name=\"vigor\" id=\"vigor\" value=1>";
            }
            if ($bExtra) {
                $sHtml .= $sFormularioExtra;
            }


            $sHtml .= "<input class=\"b_activo\" type=\"submit\" value=\"" . gettext('sBotonEnviar') . "\"><br /></form>";


            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('extension'));
            $oBaseDatos->construir_Tablas(array('tipos_fichero'));
            $oBaseDatos->construir_Where(array('id<6'));
            $oBaseDatos->construir_Order(array('extension'));
            $oBaseDatos->consulta();
            $sHtml .= gettext('sFSoportados');
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $sHtml .= $aIterador[0] . " ";
            }
            $oPagina->addBodyContent("<br/><br/>");
            $oPagina->addBodyContent($sHtml . "<br/><br/>");
            $oPagina->addBodyContent($oVolver->to_Html());
        } else {
            if ($aParametros['idtipo'] == iIdManual) {
                $oPagina->addBodyContent("<div align='center'>" . gettext('sPFCExisteManual') . "<br />" . $oVolver->to_Html() . "</div>");
            } else if ($aParametros['idtipo'] == iIdPolitica) {
                $oPagina->addBodyContent("<div align='center'>" . gettext('sPFCExistePolitica') . "<br />" . $oVolver->to_Html() . "</div>");
            } else {
                $oPagina->addBodyContent("<div align='center'>" . gettext('sPFCExisteAAI') . "<br />" . $oVolver->to_Html() . "</div>");
            }
        }
        return $oPagina->toHTML();
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Adjunto($sAccion, $aParametros)
    {
        $iAdjunto = $aParametros['numeroDeFila'];
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $_SESSION['subirfichero'] = 'adjunto';

        $oPagina->addBodyContent("<form enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">");

        $oVolver = new boton("Volver", "parent.atras(-2)", "noafecta");


        $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">" .
            "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
            "<input type=\"hidden\" name=\"documento\" value=\"" . $iAdjunto . "\">" .
            "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" type=\"submit\" value=\"" . gettext('sBotonEnviar') . "\"></form>";

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('extension'));
        $oBaseDatos->construir_Tablas(array('tipos_fichero'));
        $oBaseDatos->construir_Where(array('id<6'));
        $oBaseDatos->construir_Order(array('extension'));
        $oBaseDatos->consulta();
        $sHtml .= gettext('sFSoportados');
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml .= $aIterador[0] . " ";
        }
        $oPagina->addBodyContent($sHtml . "<br />");
        $oPagina->addBodyContent($oVolver->to_Html());
        return $oPagina->toHTML();
    }


    /**
     * Esta funcion devuelve la fila seleccionada para ver
     * @param array $aParametros
     * @return String
     */

    function procesa_Ver($aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Tablas(array($_SESSION['tabla']));
        $oBaseDatos->construir_Where(array('(id=\'' . $_SESSION['pagina'][$aParametros['numeroDeFila']] . '\')'));

        switch ($aParametros['proviene']) {
            case 'inicio:mensajes':
                $oBaseDatos->construir_Campos(array('contenido'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    if ($aIterador[0] != null) {
                        $sHtml = $aIterador[0];
                    } else {
                        $sHtml = gettext('sVacio') . "<br />";
                    }
                }
                break;
            default:
                break;
        }
        return $sHtml . "<br />" . $oVolver->to_Html();
    }


    function procesa_Ver_Mensaje($aParametros)
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

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Ver_Tarea($aParametros)
    {

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton(gettext('sBotonVolver'), "atras(-1)", "noafecta");
        $oDocumento = new boton("Documento", "sndReq('inicio:documentoid:detalles:ver:fila','',1)", "noafecta");
        if ($aParametros['numeroDeFila'] == -1) {
            $iIdTarea = $_SESSION['tarea'];
        } else {
            $iIdTarea = $_SESSION['pagina'][$aParametros['numeroDeFila']];
            $_SESSION['tarea'] = $iIdTarea;
        }
        $aCampos = array('us.nombre as Usuario', 'tt.nombre as Accion', 'doc.codigo||\' \'||doc.nombre as nombre', 'ta.descripcion', 'doc.id');
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos($aCampos);
        $oBaseDatos->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us', 'documentos doc'));
        $oBaseDatos->construir_Where(array("(tt.id=ta.accion)", "(ta.usuario_origen=us.id)", "(ta.activo='t')",
            "(doc.id=ta.documento)", "(ta.id='" . $iIdTarea . "')"));
        $oBaseDatos->consulta();
        $aFila = $oBaseDatos->coger_Fila();
        $_SESSION['documentodetalles'] = $aFila[4];
        $aTableAttrs = array('class' => 'table table-responsive tareas');
        $oTable = new Surface($aTableAttrs);
        $rows =array();
        $rows[]=array(gettext('sTareaEnviada'),$aFila[0]);
        $rows[]=array(gettext('sTareaEjecutar'),$aFila[1]);
        $rows[]=array(gettext('sTareaDoc'),$aFila[2]);
        $rows[]=array(gettext('sTareaDesc'),$aFila[3]);
        $sTabla = $oTable->addRows($rows)
            ->render();
        return $sTabla . $oDocumento->to_Html() . $oVolver->to_Html();
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Flujograma($sAccion, $aParametros)
    {
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan', 'text/css');


        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $_SESSION['subirfichero'] = 'imagen';

        $oVolver = new boton(gettext('sPCVolver'), "parent.atras(-2)", "noafecta");


        $sNavegador = $_SESSION['navegador'];


        //Sacamos el id del contenido proceso que apunta a nuestro documento

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id'));
        $oBaseDatos->construir_Tablas(array('contenido_procesos'));
        $oBaseDatos->construir_Where(array('documento=' . $_SESSION['documentodetalles']));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $iIdProc = $aIterador[0];
        }

        if ($sNavegador == "Microsoft Internet Explorer") {
            $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">" .
                "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
                "<input type=\"hidden\" name=\"documento\" value=\"" . $iIdProc . "\">" .
                "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" onMouseOut=\"this.className='b_activo'\" onMouseOver=\"this.className='b_focus'\" type=\"submit\" value=\"" . gettext('sBotonEnviar') . "\"></form>";
        } else {
            $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">" .
                "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
                "<input type=\"hidden\" name=\"documento\" value=\"" . $iIdProc . "\">" .
                "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" type=\"submit\" value=\"" . gettext('sBotonEnviar') . "\"></form>";
        }

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('extension'));
        $oBaseDatos->construir_Tablas(array('tipos_fichero'));
        $oBaseDatos->construir_Where(array('id>5'));
        $oBaseDatos->construir_Order(array('extension'));
        $oBaseDatos->consulta();
        $sHtml .= gettext('sFSoportados');
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml .= $aIterador[0] . " ";
        }
        $oPagina->addBodyContent($sHtml . "<br /><br />");
        $oPagina->addBodyContent($oVolver->to_Html());
        return $oPagina->toHTML();
    }

    /**
     * @param $aParametros
     * @return string
     */
    function asignar_Area($aParametros)
    {

        $oVolver = new boton(gettext('sPCVolver'), "atras(-2)", "noafecta");
        if (isset ($_SESSION['documentodetalles'])) {
            $iId = $_SESSION['documentodetalles'];
            $iIdArea = $_SESSION['pagina'][$aParametros['numeroDeFila']];
            $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_SetSin(array('area'),
                array($iIdArea));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('id=' . $iId));
            $oBaseDatos->consulta();
        }
        $sHtml = gettext('sTopCorrecta') . "<br />" . $oVolver->to_Html();
        return "contenedor|" . $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function subir_Fichero($sAccion, $aParametros)
    {
        
        $iIdDoc = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=formulariosubir&sesion=&datos=" .
            $iIdDoc . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function subir_Fichero_Flujo($sAccion, $aParametros)
    {
        $iIdCurso = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=catalogo:flujograma:upload:iframe&sesion=&datos=  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Politica($sAccion)
    {
        $sPolitica = $sAccion;
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=politica:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Objetivo($sAccion)
    {
        $sPolitica = $sAccion;
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=objetivo:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Manual($sAccion)
    {
        $sPolitica = $sAccion;
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=manual:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Pg($sAccion)
    {
        $sPolitica = 'PG';
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=pg:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Pe($sAccion)
    {
        $sPolitica = 'PE';
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=pe:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Ma($sAccion)
    {
        $sPolitica = 'MA';
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=ma:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @return string
     */
    function subir_Fichero_Externo($sAccion)
    {
        
        $sPolitica = 'Externo';
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=externo:iframe&sesion=&datos=" .
            $sPolitica . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Subir_Fichero($sAccion, $aParametros)
    {

        $oVolver = new boton("Volver", "parent.atras(-2)", "noafecta");
        $_SESSION['subirfichero'] = 'documento';
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $sHtml = "<form class=\"fichero\" enctype=\"multipart/form-data\" accept=\"" . gettext('sTipos') . "\" action=\"/coger.php\" method=\"post\">" .
                "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
                "<input type=\"hidden\" name=\"documento\" value=\"" . $aParametros['iddoc'] . "\">" .
                "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" type=\"submit\" value=" . gettext('sBotonEnviar') . "></form>";
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('extension'));
        $oBaseDatos->construir_Tablas(array('tipos_fichero'));
        $oBaseDatos->construir_Where(array('id<6'));
        $oBaseDatos->construir_Order(array('extension'));
        $oBaseDatos->consulta();
        $sHtml .= gettext('sFSoportados');
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml .= $aIterador[0] . " ";
        }
        $oPagina->addBodyContent($sHtml . "<br />");
        $oPagina->addBodyContent($oVolver->to_Html());
        return $oPagina->toHTML();

    }


    /**
     * Procedimiento para ver la ficha del proceso
     * @param $sAccion
     * @param $aParametros
     * @param null $iId
     * @return string
     */
    function procesa_Ver_Ficha_Proceso($sAccion, $aParametros, $iId = null)
    {

        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        if (isset($iId)) {
            //Si entramos aqui es que tenemos en $_SESSION['documentodetalles'] la id del documento asociado
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id'));
            $oBaseDatos->construir_Tablas(array('contenido_procesos'));
            $oBaseDatos->construir_Where(array('contenido_procesos.documento=' . $_SESSION['documentodetalles']));
            $oBaseDatos->consulta();
            $aIterador = $oBaseDatos->coger_Fila();
            if ($aIterador) {
                $iIdContenido = $aIterador[0];
            }
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('proveedor', 'entradas', 'propietario', 'salidas', 'cliente',
                'instalaciones_ambiente', 'indicaciones', 'procesos.nombre', 'procesos.codigo'));
            $oBaseDatos->construir_Tablas(array('contenido_procesos', 'procesos'));
            $oBaseDatos->construir_Where(array('contenido_procesos.id=' . $iIdContenido, 'contenido_procesos.proceso=procesos.id'));
        } else {
            $iIdProceso = $aParametros['numeroDeFila'];
            if ($aParametros['accion'] == 'catalogo:verdocumentoprocesosinfilahistorial:listado:ver:fila') {
                $iId = $_SESSION['pagina'][$iIdProceso];
                //Necesitamos el id de contenido_procesos para mas adelante
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                $oBaseDatos->construir_Where(array('contenido_procesos.documento=' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $iIdContenido = $aIterador[0];
                }
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('proveedor', 'entradas', 'propietario', 'salidas', 'cliente',
                    'instalaciones_ambiente', 'indicaciones', 'procesos.nombre', 'procesos.codigo'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos', 'procesos'));
                //        $oBaseDatos->construir_Where(array('contenido_procesos.proceso='.$iIdProceso,'contenido_procesos.documento=documentos.id',
                //                                           'documentos.estado='.iVigor,'contenido_procesos.proceso=procesos.id'));
                $oBaseDatos->construir_Where(array('documentos.id =' . $iId, 'contenido_procesos.documento=documentos.id',
                    'contenido_procesos.proceso=procesos.id'));
            } else if ($aParametros['accion'] == 'catalogo:verdocumentoprocesosinfilaborrador:listado:ver:fila') {
                $iId = $_SESSION['documentodetalles'];

                //Necesitamos el id de contenido_procesos para mas adelante
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos'));
                $oBaseDatos->construir_Where(array('contenido_procesos.documento=' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    $iIdContenido = $aIterador[0];
                }

                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('id', 'nombre', 'codigo'));
                $oBaseDatos->construir_Tablas(array('documentos'));
                $oBaseDatos->construir_Where(array('documentos.id =' . $iId));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();

                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('proveedor', 'entradas', 'propietario', 'salidas', 'cliente',
                    'instalaciones_ambiente', 'indicaciones', 'procesos.nombre', 'procesos.codigo'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos', 'procesos'));
                $oBaseDatos->construir_Where(array('documentos.nombre =\'' . $aIterador[1] . '\'',
                    'documentos.codigo =\'' . $aIterador[2] . '\'', 'contenido_procesos.documento=documentos.id',
                    'documentos.estado=' . iBorrador, 'contenido_procesos.proceso=procesos.id'));

            } else {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('proveedor', 'entradas', 'propietario', 'salidas', 'cliente',
                    'instalaciones_ambiente', 'indicaciones', 'procesos.nombre', 'procesos.codigo'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos', 'procesos'));
                $oBaseDatos->construir_Where(array('contenido_procesos.proceso=' . $iIdProceso, 'contenido_procesos.documento=documentos.id',
                    'documentos.estado=' . iVigor, 'contenido_procesos.proceso=procesos.id'));
            }
        }
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sHtml = "<div id=\"proceso\">";
            $sHtml .= "<p class=\"t_proceso\">" . gettext('sProcFicha') . "&nbsp;&nbsp;" .
                $aIterador[8] . "&nbsp;&nbsp;" . $aIterador[7] . "</p>";
            $aTableAttrs = array('class' => 'table table-responsive tareas tabla_ficha');
            $oTable = new Surface($aTableAttrs);
            $rows =array();
            $rows[]=array("<b>".gettext('sProcProv')."</b>",$aIterador[0]);
            $rows[]=array("<b>".gettext('sProcEntradas')."</b>",$aIterador[1]);
            $rows[]=array("<b>".gettext('sProcEntradas')."</b>",$aIterador[2]);

            $subTable = new Surface($aTableAttrs);
            $subRows=array();

            if (isset($iId)) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('indicadores.nombre'));
                $oBaseDatos->construir_Tablas(array('indicadores', 'contenido_procesos'));
                $oBaseDatos->construir_Where(array('indicadores.id=any(contenido_procesos.indicadores)',
                    'contenido_procesos.id=' . $iIdContenido));
            } else {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('indicadores.nombre'));
                $oBaseDatos->construir_Tablas(array('indicadores', 'contenido_procesos', 'documentos'));
                $oBaseDatos->construir_Where(array('indicadores.id=any(contenido_procesos.indicadores)',
                    'contenido_procesos.proceso=' . $iIdProceso, 'contenido_procesos.documento=documentos.id',
                    'documentos.estado=' . iVigor));
            }
            $oBaseDatos->consulta();
            while ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                $subRows[]= $aIteradorInterno[0];
            }
            $subTabla = $subTable->addRows($subRows)
                ->render();
            $rows[]=array("<b>".gettext('sProcInd')."</b>",$subTabla);
            $subTable = new Surface($aTableAttrs);
            $subRows=array();

            //Sacamos los anexos

            if (isset($iId)) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
                $oBaseDatos->construir_Tablas(array('documentos', 'contenido_procesos'));
                $oBaseDatos->construir_Where(array('documentos.id=any(contenido_procesos.anejos)',
                    'contenido_procesos.id=' . $iIdContenido));
            } else {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('doc1.codigo||\' \'||doc1.nombre'));
                $oBaseDatos->construir_Tablas(array('contenido_procesos', 'documentos doc1', 'documentos doc2'));
                $oBaseDatos->construir_Where(array('doc1.id=any(contenido_procesos.anejos)',
                    'contenido_procesos.proceso=' . $iIdProceso, 'contenido_procesos.documento=doc2.id',
                    'doc2.estado=' . iVigor));
            }
            $oBaseDatos->consulta();

            while ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                $subRows[]= $aIteradorInterno[0];
            }
            $subTabla = $subTable->addRows($subRows)
                ->render();
            $rows[]=array("<b>".gettext('sBotonDocAx')."</b>",$subTabla);
            $rows[]=array("<b>".gettext('sProcSalidas')."</b>",$aIterador[3]);
            $rows[]=array("<b>".gettext('sProcCliente')."</b>",$aIterador[4]);
            $rows[]=array("<b>".gettext('sProcInstal')."</b>",$aIterador[5]);
            $rows[]=array("<b>".gettext('sProcIndicacion')."</b>",$aIterador[6]);

            if (isset($iId)) {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('flujogramas.id'));
                $oBaseDatos->construir_Tablas(array('flujogramas'));
                $oBaseDatos->construir_Where(array('flujogramas.proceso=' . $iIdContenido));
            } else {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('flujogramas.id'));
                $oBaseDatos->construir_Tablas(array('flujogramas', 'contenido_procesos', 'documentos'));
                $oBaseDatos->construir_Where(array('flujogramas.proceso=contenido_procesos.id', 'contenido_procesos.proceso=' . $iIdProceso,
                    'contenido_procesos.documento=documentos.id', 'documentos.estado=' . iVigor
                ));
            }
            $oBaseDatos->consulta();
            $iCont = 1;
            while ($aIterador = $oBaseDatos->coger_Fila()) {
                $image = "<img style=cursor:pointer src=\"muestrabinario.php?id=" .
                    $aIterador[0] . "&tipo=imagent\" onclick=\"window.open('paginaBinario.php?id=" .
                    $aIterador[0] . "&tipo=imagen','proceso','top=0,left=0,directories=no,height=1024,".
                    "location=no,menubar=no,resizable=no,scrollbars=yes,status=no,toolbar=no,width=1280')\";/>";
                $rows[]=array("<b>" . gettext('sBotonFlujog') . ": " . $iCont . "</b>",$image);
                $iCont++;
            }
            $sTabla = $oTable->addRows($rows)
                ->render();
            $sHtml = $sTabla."</div>";
        } else {
            $sHtml = "<table>" . gettext('sProcNoDef') . "</table>";
        }
            $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Matriz_Indicadores($sAccion, $aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $aTableAttrs = array('class' => 'table table-responsive indicadores');
        $oTable = new Surface($aTableAttrs);
        $headerContent =array(
            gettext('sMIProceso'),
            gettext('sMIIndicador'),
            gettext('sMIValorIn'),
            gettext('sMIValorTol'),
            gettext('sMIObj'),
            gettext('sMIMedicion'),
            gettext('sMIAnalisis'),
            gettext('sMIResponsable'),
        );
        $rows=array();
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id,nombre'));
        $oBaseDatos->construir_Tablas(array('procesos'));
        $oBaseDatos->construir_Order(array('id asc'));
        $oBaseDatos->consulta();
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('indicadores.nombre', 'indicadores.valor_inicial', 'indicadores.valor_tolerable',
                'case when indicadores.genera_objetivo then \'Tiene\' else \'No Tiene\' end', 'indicadores.tecnica', 'seg1.nombre', 'seg2.nombre',
                'indicadores.responsable_analisis', 'indicadores.responsable_seguimiento'
            ));
            $oDb->construir_Tablas(array('indicadores', 'contenido_procesos', 'documentos', 'tipo_frecuencia_seg seg1', 'tipo_frecuencia_seg seg2'));
            $oDb->construir_Where(array('contenido_procesos.proceso=' . $aIterador[0], 'contenido_procesos.documento=documentos.id',
                'documentos.estado=' . iVigor, 'indicadores.id=any(contenido_procesos.indicadores)',
                'indicadores.frecuencia_ana=seg1.id', 'indicadores.frecuencia_seg=seg2.id'
            ));
            $oDb->construir_Order(array('indicadores.id asc'));
            $oDb->consulta();
            while ($aIteradorInterno = $oDb->coger_Fila()) {
                $rows[]=array(
                    $aIterador[1],
                    $aIteradorInterno[0],
                    $aIteradorInterno[1],
                    $aIteradorInterno[2],
                    $aIteradorInterno[3],
                    $aIteradorInterno[4],
                    $aIteradorInterno[5] . "/" . $aIteradorInterno[6],
                    $aIteradorInterno[7] . "/" . $aIteradorInterno[8]
                );
            }
        }
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('indicadores.nombre', 'indicadores.valor_inicial', 'indicadores.valor_tolerable',
            'objetivos.nombre', 'indicadores.tecnica', 'seg1.nombre', 'seg2.nombre',
            'indicadores.responsable_analisis', 'indicadores.responsable_seguimiento'
        ));
        $oDb->construir_Tablas(array('indicadores', 'contenido_procesos', 'tipo_frecuencia_seg seg1', 'tipo_frecuencia_seg seg2', 'objetivos'));
        $oDb->construir_Where(array('contenido_procesos.proceso=0', 'objetivos.id=indicadores.objetivo',
            'indicadores.frecuencia_ana=seg1.id', 'indicadores.frecuencia_seg=seg2.id'
        ));
        $oDb->construir_Order(array('indicadores.id asc'));
        $oDb->consulta();
        while ($aIteradorInterno = $oDb->coger_Fila()) {
            $rows[]=array(
                $aIterador[1],
                $aIteradorInterno[0],
                $aIteradorInterno[1],
                $aIteradorInterno[2],
                $aIteradorInterno[3],
                $aIteradorInterno[4],
                $aIteradorInterno[5] . "/" . $aIteradorInterno[6],
                $aIteradorInterno[7] . "/" . $aIteradorInterno[8]
            );
        }
        $sTabla = $oTable->setHead($headerContent)
            ->addRows($rows)
            ->render();
        $sHtml = $sTabla . $oVolver->to_Html();
        return "contenedor|" . $sHtml;

    }


    /**
     * Esta funcion crea el editor en caso de que no este creado y lo prepara para insertar el documento
     * @param $aParametros
     * @return string
     */
    function procesa_NuevaVersion_Documento($aParametros)
    {
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $_SESSION['subirfichero'] = $aParametros[0];
        $oPagina->addBodyContent("<form enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">");
        $oVolver = new boton("Volver", "parent.atras(-2)", "noafecta");

        //Sacamos el id del contenido proceso que apunta a nuestro documento

        $iIdProc = $aParametros[1];

        $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">" .
            "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
            "<input type=\"hidden\" name=\"documento\" value=\"" . $iIdProc . "\">" .
            "<input type=\"hidden\" name=\"politica\" ";
        if (isset($sPolitica)) {
            $sHtml .= "value=\"" . $sPolitica . "\">";
        } else {
            $sHtml .= "\">";
        }
        $sHtml .= "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" type=\"submit\" value=\"" .
            gettext('sBotonEnviar') . "\"></form>";
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('extension'));
        $oBaseDatos->construir_Tablas(array('tipos_fichero'));
        $oBaseDatos->construir_Where(array('id<6'));
        $oBaseDatos->construir_Order(array('extension'));
        $oBaseDatos->consulta();
        $sHtml .= gettext('sFSoportados');
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml .= $aIterador[0] . " ";
        }
        $oPagina->addBodyContent($sHtml . "<br/><br/>");
        $oPagina->addBodyContent($oVolver->to_Html());

        return $oPagina->toHTML();
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_NuevaVersion_Iframe($aParametros)
    {
        
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('doc1.codigo'));
        $oBaseDatos->construir_Tablas(array('documentos doc1', 'documentos doc2'));
        $oBaseDatos->construir_Where(array('(doc1.codigo=doc2.codigo)',
            '(doc1.nombre=doc2.nombre)',
            '(doc2.id=' . $aParametros['id'] . ')',
            '(doc1.estado=' . iBorrador . ') OR (doc1.estado=' . iPendRevision .
            ') OR (doc1.estado=' . iPendAprobacion . ') OR (doc1.estado=' . iRevisado .
            ')'));
        $oBaseDatos->consulta();
        if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
            //Eso es que ya hay un borrador, abortamos
            return "alert|" . gettext('sBorraAlert');
        } else {
            return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=documentos:general:comun:iframe:nuevaversion&sesion=&datos="
                . $aParametros['idtipo'] . separador . $aParametros['id'] . "\"  width=\"100%\"" .
                " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
        }
    }


    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Aprobar_Documento($aParametros)
    {
        $Config=new Config();
        $css =& new encriptador();
        $clave = 'encriptame';
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        //Primero sacamos el estado del documento
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'estado', 'tipo_documento', 'revision'));
        $oBaseDatos->construir_Tablas(array('documentos'));
        $oBaseDatos->construir_Where(array('id=\'' . $aParametros['docid'] . '\''));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            //Comprobamos que su estado era o revisado o pendiente de aprobar
            if (($aIterador[2] == iRevisado) || ($aIterador[2] == iPendAprobacion)) {
                if (($_SESSION['empresa'] == 'ICS') && (($aIterador[3] == iIdPolitica) || ($aIterador[3] == iIdPg))) {
                    //Debemos de hacer las actualizaciones en TODAS las bases de datos
                    $sPassEmp = $css->decrypt($Config->sPassEtc, $clave);
                    $oDbEmpresas = new Manejador_Base_Datos($Config->sLoginEtc, $sPassEmp, $Config->sDbEtc);
                    $oDbEmpresas->iniciar_Consulta('SELECT');
                    $oDbEmpresas->construir_Campos(array('login_bbdd', 'pass_bbdd', 'nombre_bbdd'));
                    $oDbEmpresas->construir_Tablas(array('qnova_bbdd'));
                    $oDbEmpresas->consulta();
                    while ($aIteradorInterno = $oDbEmpresas->coger_Fila()) {
                        $sPassEmpInt = $css->decrypt($aIteradorInterno[1], $clave);
                        $oBaseDatos = new Manejador_Base_Datos($aIteradorInterno[0], $sPassEmpInt, $aIteradorInterno[2]);
                        $oBaseDatos->comienza_transaccion();
                        $oBaseDatos->iniciar_Consulta('UPDATE');
                        $oBaseDatos->construir_Set(array('activo', 'estado'),
                            array('false', iHistorico));
                        $oBaseDatos->construir_Tablas(array('documentos'));
                        $oBaseDatos->construir_Where(array('codigo=\'' . $aIterador[0] . '\'',
                            'nombre=\'' . $aIterador[1] . '\'',
                            'estado=\'' . iVigor . '\''));
                        $oBaseDatos->consulta();
                        $oBaseDatos->iniciar_Consulta('UPDATE');
                        $oBaseDatos->construir_SetSin(array('aprobado_por', 'estado', 'fecha_aprobacion'),
                            array($aParametros['userid'], iVigor, 'now()'));
                        $oBaseDatos->construir_Tablas(array('documentos'));
                        $oBaseDatos->construir_Where(array('codigo=\'' . $aIterador[0] . '\'',
                            'nombre=\'' . $aIterador[1] . '\'',
                            'estado=\'' . iRevisado . '\''));
                        $oBaseDatos->consulta();
                        $oBaseDatos->iniciar_Consulta('INSERT');
                        $oBaseDatos->construir_Campos(array('titulo', 'contenido', 'destinatario', 'activo', 'origen'));
                        $oBaseDatos->construir_Value(array('Nueva version del documento: ' . $aIterador[0] . " " .
                            $aIterador[1], 'Nueva version del documento: ' . $aIterador[0] . $aIterador[1], 0, 't', 0));
                        $oBaseDatos->construir_Tablas(array('mensajes'));
                        $oBaseDatos->consulta();
                        $oBaseDatos->termina_transaccion();
                    }
                    $sHtml = "contenedor|" . gettext('sDocAprobado') . "<br />" . $oVolver->to_Html();
                } else {
                    //Comprobamos que no hay uno en vigor actualmente, si lo hay lo damos de baja antes de poner en vigor el borrador
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_Set(array('activo', 'estado'),
                        array('false', iHistorico));
                    $oBaseDatos->construir_Tablas(array('documentos'));
                    $oBaseDatos->construir_Where(array('codigo=\'' . $aIterador[0] . '\'',
                        'nombre=\'' . $aIterador[1] . '\'',
                        'estado=\'' . iVigor . '\''));
                    $oBaseDatos->consulta();


                    //Una vez puesto a inactivo el documento vigor si lo hubiera pasamos a poner como vigor nuestro borrador

                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_SetSin(array('aprobado_por', 'estado', 'fecha_aprobacion'),
                        array($aParametros['userid'], iVigor, 'now()'));
                    $oBaseDatos->construir_Tablas(array('documentos'));
                    $oBaseDatos->construir_Where(array('id=\'' . $aParametros['docid'] . '\''));
                    $oBaseDatos->consulta();
                    //Si el documento es de un proceso ponemos la version al proceso
                    if ($aIterador[3] == iIdProceso) {
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('procesos.id'));
                        $oBaseDatos->construir_Tablas(array('procesos', 'contenido_procesos'));
                        $oBaseDatos->construir_Where(array('contenido_procesos.documento=' . $aParametros['docid'],
                            'contenido_procesos.proceso=procesos.id'
                        ));
                        $oBaseDatos->consulta();
                        if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                            $oBaseDatos->iniciar_Consulta('UPDATE');
                            $oBaseDatos->construir_Set(array('revision'),
                                array($aIterador[4]));
                            $oBaseDatos->construir_Tablas(array('procesos'));
                            $oBaseDatos->construir_Where(array('id=' . $aIteradorInterno[0]));
                            $oBaseDatos->consulta();
                        }
                    }
                    //Mandamos un mensaje automatico a todos los usuarios
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('titulo', 'contenido', 'destinatario', 'activo', 'origen'));
                    $oBaseDatos->construir_Value(array('Nueva version del documento: ' . $aIterador[0] .
                        " " . $aIterador[1], 'Nueva version del documento: ' . $aIterador[0] . $aIterador[1], 0, 't', 0));
                    $oBaseDatos->construir_Tablas(array('mensajes'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->termina_transaccion();
                    $sHtml = "contenedor|" . gettext('sDocAprobado') . "<br />" . $oVolver->to_Html();
                }
            } else {
                $oBaseDatos->termina_transaccion();
                $sHtml = "alert|" . gettext('sNecesitaAprobacion');
            }
        }
        $oBaseDatos->desconexion();
        return $sHtml;
    }


    /**
     * Esta funcion revisa un documento
     * @param $aParametros
     * @return string
     */
    function procesa_Revisar_Documento($aParametros)
    {
        $Config=new Config();
        $css =& new encriptador();
        $clave = 'encriptame';
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        if ($_SESSION['empresa'] == 'ICS') {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('tipo_documento'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('id=' . $aParametros['docid']));
            $oBaseDatos->consulta();
            if ($aDevolver = $oBaseDatos->coger_Fila()) {
                if (($aDevolver[0] == iIdPg) || ($aDevolver[0] == iIdPolitica)) {
                    $bICS = true;
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'estado'));
                    $oBaseDatos->construir_Tablas(array('documentos'));
                    $oBaseDatos->construir_Where(array('id=' . $aParametros['docid']));
                    $oBaseDatos->consulta();
                    $aDatosDocumento = $oBaseDatos->coger_Fila();
                } else {
                    $bICS = false;
                }
            } else {
                $bICS = false;
            }
        }
        if ($bICS) {
            $sPassEmp = $css->decrypt(Config::$sPassEtc, $clave);
            $oDbEmpresas = new Manejador_Base_Datos(Config::$sLoginEtc, $sPassEmp, Config::$sDbEtc);
            $oDbEmpresas->iniciar_Consulta('SELECT');
            $oDbEmpresas->construir_Campos(array('login_bbdd', 'pass_bbdd', 'nombre_bbdd'));
            $oDbEmpresas->construir_Tablas(array('qnova_bbdd'));
            $oDbEmpresas->consulta();
            while ($aIteradorInterno = $oDbEmpresas->coger_Fila()) {
                $sPassEmpInt = $css->decrypt($aIteradorInterno[1], $clave);
                $oDbInterno = new Manejador_Base_Datos($aIteradorInterno[0], $sPassEmpInt, $aIteradorInterno[2]);
                $oDbInterno->iniciar_Consulta('UPDATE');
                $oDbInterno->construir_Set(array('revisado_por', 'estado'),
                    array($aParametros['userid'], iRevisado));
                $oDbInterno->pon_SetSin('fecha_revision', 'now()');
                $oDbInterno->construir_Tablas(array('documentos'));
                $oDbInterno->construir_Where(array('codigo=\'' . $aDatosDocumento[0] . '\'', 'nombre=\'' . $aDatosDocumento[1] . '\'',
                    'estado=\'' . $aDatosDocumento[2] . '\''));
                $oDbInterno->consulta();
            }
            $sHtml = gettext('sRevision') . "<br />";
            $sHtml .= $oVolver->to_Html();
        } else {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('revisado_por', 'estado'),
                array($aParametros['userid'], iRevisado));
            $oBaseDatos->pon_SetSin('fecha_revision', 'now()');
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('id=\'' . $aParametros['docid'] . '\''));
            $oBaseDatos->consulta();
            $sHtml = gettext('sRevision') . "<br />";
            $sHtml .= $oVolver->to_Html();
        }
        return $sHtml;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_EditarVersion_Documento($aParametros)
    {
        $oPagina = new FakePage();
        $oPagina->addStyleDeclaration('/css/tuqan.css', 'text/css');
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $_SESSION['subirfichero'] = $aParametros[0];
        $oPagina->addBodyContent("<form enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">");
        $oVolver = new boton("Volver", "parent.atras(-2)", "noafecta");

        //Sacamos el id del contenido proceso que apunta a nuestro documento

        $iIdProc = $aParametros[1];

        $sHtml = "<form  enctype=\"multipart/form-data\" action=\"/coger.php\" method=\"post\">" .
            "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"100000000\">" . gettext('sEnviarFichero') . " " .
            "<input type=\"hidden\" name=\"documento\" value=\"" . $iIdProc . "\">" .
            "<input type=\"hidden\" name=\"politica\"";
        if (isset($sPolitica)) {
            $sHtml .= "value=\"" . $sPolitica . "\">";
        } else {
            $sHtml .= "\">";
        }

        $sHtml .= "<input type=\"hidden\" name=\"editar\" value=\"si\">" .
            $sHtml .= "<input name=\"userfile\" type=\"file\"><input class=\"b_activo\" type=\"submit\" value=\"" . gettext('sBotonEnviar') . "\"></form>";

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('extension'));
        $oBaseDatos->construir_Tablas(array('tipos_fichero'));
        $oBaseDatos->construir_Where(array('id<6'));
        $oBaseDatos->construir_Order(array('extension'));
        $oBaseDatos->consulta();
        $sHtml .= gettext('sFSoportados');
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $sHtml .= $aIterador[0] . " ";
        }
        $oPagina->addBodyContent($sHtml . "<br/><br/>");
        $oPagina->addBodyContent($oVolver->to_Html());

        return $oPagina->toHTML();
    }



    /**
     * Esta funcion crea el editor en caso de que no este creado y lo prepara para editar el documento
     * @param $aParametros
     * @return string
     */
    function procesa_Editar_Documento($aParametros)
    {
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=documentacion:general:comun:editarversion&sesion=&datos=" . $aParametros['idtipo'] . separador . $aParametros['id'] . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }


    /**
     * Esta es la funcion con la que revisamos un producto
     * @param $aParametros
     * @return string
     */
    function procesa_Revisa_Producto($aParametros)
    {

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton("Volver", "atras(-2)", "noafecta");
        $aTrocear = explode(separadorCadenas, $aParametros['filas']);
        $aFilas = str_split($aTrocear[0]);
        $aElegidos = array();
        //Construimos el array a insertar
        if (is_array($aFilas)) {
            $aCriterios = array('{');
            $iSize = 0;
            foreach ($aFilas as $sKey => $sValor) {
                if ($sValor == 1) {
                    $aCriterios[] = $_SESSION['pagina'][$sKey];
                    $aElegidos[] = $_SESSION['pagina'][$sKey];
                    if ($sKey != $iSize - 1) {
                        $aCriterios[] = ',';
                    }
                }
            }
            $iSize = count($aCriterios);
            //Quitamos la ultima , si procede
            if ($aCriterios[$iSize - 1] == ',') {
                array_pop($aCriterios);
            }
            $aCriterios[] = '}';
        }
        $sCriterios = implode("", $aCriterios);
        //En aCriterios ya tenemos el array de criterios, updateamos en la tabla productos y añadimos una entrada al historico

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('valor'));
        $oBaseDatos->construir_Tablas(array('productos'));
        $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $iValue = $aIterador[0];
        }
        //Ahora debemos sacar la valoracion total de los criterios elegidos
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('SUM(criterios_homologacion.valor)'));
        $oBaseDatos->construir_Tablas(array('criterios_homologacion'));
        if (is_array($aElegidos) && (count($aElegidos) > 0)) {
            $sWhere = "";
            foreach ($aElegidos as $sKey => $iValor) {
                if ($sKey == 0) {
                    $sWhere .= "(id='" . $iValor . "')";
                } else {
                    $sWhere .= " OR (id='" . $iValor . "')";
                }
            }
            $oBaseDatos->construir_Where(array($sWhere));
            $oBaseDatos->consulta();
        }
        if (count($aElegidos) <= 0) {
            $iSuma = 0;
        } else {
            $aIterador = $oBaseDatos->coger_Fila();
            if ($aIterador) {
                $iSuma = $aIterador[0];
            }
        }
        $aConsultas = array();
        //Comenzamos la transaccion
        $bHomologado = 'f';
        if ($iSuma >= $iValue) {
            $bHomologado = 't';
        }
        $oBaseDatos->iniciar_Consulta('UPDATE');
        $oBaseDatos->construir_Set(array('criterios', 'homologado'),
            array($sCriterios, $bHomologado));
        $oBaseDatos->pon_SetSin('fecha_revision',
            'now()');
        $oBaseDatos->construir_Tablas(array('productos'));
        $oBaseDatos->construir_Where(array('id=' . $_SESSION['producto']));
        $aConsultas[] = $oBaseDatos->to_String_Consulta();

        $oBaseDatos->iniciar_Consulta('INSERT');
        $oBaseDatos->construir_Campos(array('fecha', 'usuario', 'producto', 'valoracion_obtenida', 'valoracion_homologacion'));
        $oBaseDatos->construir_Tablas(array('historico_productos'));
        $oBaseDatos->pon_ValueSin('now()');
        $oBaseDatos->construir_Value(array($_SESSION['userid'], $_SESSION['producto'], $iSuma, $iValue));
        $aConsultas[] = $oBaseDatos->to_String_Consulta();

        $oBaseDatos->iniciar_Consulta('BEGIN');
        $oBaseDatos->construir_Begin($aConsultas);

        $oBaseDatos->consulta();

        $sHtml = gettext('sProdRevisado') . "<br /><br />" . $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Revision_Producto($sAccion, $aParametros)
    {

        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oRevisar = new boton("Revisar", "sndReq('proveedores:producto:comun:revisa:general','',1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        if (($aParametros['numeroDeFila'] != -1) && ($aParametros['numeroDeFila'] != null)) {
            $_SESSION['producto'] = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        }
        $iIdProducto = $_SESSION['producto'];
        //Sacamos todos los criterios
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('id', 'nombre', 'valor'));
        $oBaseDatos->construir_Tablas(array('criterios_homologacion'));
        $oBaseDatos->construir_Where(array('activo=\'t\''));
        $oBaseDatos->consulta();

        //Ahora sacamos todos los criterios que tiene el producto
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('criterios', 'valor'));
        $oDb->construir_Tablas(array('productos'));
        $oDb->construir_Where(array('id=' . $iIdProducto));
        $oDb->consulta();
        $aIterador = $oDb->coger_Fila();
        if ($aIterador) {
            $aCriterios = str_split($aIterador[0]);
            $iValorActual = $aIterador[1];
        }
        $iSuma = 0;
        $aTableAttrs = array('class' => 'table table-responsive');
        $oTable = new Surface($aTableAttrs);
        $headerContent =array('',gettext('sCritnombre'), gettext('sCritValoracion'));
        $rows=array();
        unset ($_SESSION['pagina']);
        $_SESSION['tabla'] = 'criterios_homologacion';
        while ($aIterador = $oBaseDatos->coger_Fila()) {
            $_SESSION['pagina'][] = $aIterador[0];
            if (in_array($aIterador[0], $aCriterios)) {
                $aplica = "<input TYPE=CHECKBOX id=\"aplicable\" name=\"ch:" .
                    $aIterador[0] . "\" onclick=\"puntuacion()\" CHECKED=true VALUE=\"" . $aIterador[2] . "\">";
                $suma = $aIterador[2];
                $iSuma += $aIterador[2];
            } else {
                $aplica = "<input TYPE=CHECKBOX id=\"aplicable\" name=\"ch:" .
                    $aIterador[0] . "\" onclick=\"puntuacion()\" VALUE=\"" . $aIterador[2] . "\">";
                $suma = 0;
            }
            $rows[]=array($aplica, $aIterador[1], $suma);
        }
        $sTabla = $oTable->setHead($headerContent)
            ->addRows($rows)
            ->render();

        $sHtml = $sTabla."<div class=\"puntuacion\"><b>" . gettext('sCritPuntuacion') . " &nbsp;&nbsp;&nbsp;</b>";
        $sHtml .= "<input id=\"camposuma\" Value=\"" . $iSuma . "\" disabled=\"disabled\"></div>";

        if ($iSuma < $iValorActual) {
            $sHtml .= "<div class=\"nohomologado\"><b>" . gettext('sCritNoHomol') .
                "</b>" . $iSuma . "/" . $iValorActual . "</div><br />";
        } else {
            $sHtml .= "<div class=\"homologado\"><b>" . gettext('sCritHomol') .
                "</b>" . $iSuma . "/" . $iValorActual . "</div>";
        }
        $sHtml .= "<div>" . $oVolver->to_Html() . $oRevisar->to_Html()."</div>";
        return $sHtml;
    }


    /**
     * Esta funcion nos aumenta un punto la version del documento
     * @param $sAntigua
     * @return string
     */
    function siguiente_Version($sAntigua)
    {
        $aTrocearAntigua = explode('\.', $sAntigua);
        $iVersion = (int)$aTrocearAntigua[0];
        $iRevisionMayor = (int)$aTrocearAntigua[1];
        $iRevisionMenor = (int)$aTrocearAntigua[2];
        if ($iRevisionMenor < 9) {
            $iRevisionMenor++;
        } else if ($iRevisionMayor < 9) {
            $iRevisionMayor++;
            $iRevisionMenor = 0;
        }
        return ($iVersion . "." . $iRevisionMayor . "." . $iRevisionMenor);
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesar_NuevaVersionProceso($sAccion, $aParametros)
    {

        $oBoton = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        //Lo primero es ver si ya habia un borrador
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('doc2.id'));
        $oBaseDatos->construir_Tablas(array('documentos doc1, documentos doc2'));
        $oBaseDatos->construir_Where(array('(doc1.codigo=doc2.codigo)',
            '(doc1.nombre=doc2.nombre)',
            '(doc1.id=' . $aParametros['documento'] . ')',
            '(doc2.estado=' . iBorrador . ') OR (doc2.estado=' . iPendRevision .
            ') OR (doc2.estado=' . iPendAprobacion . ') OR (doc2.estado=' . iRevisado .
            ')'));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            //Eso es que ya hay un borrador, abortamos
            $sHtml = "alert|" . gettext('sBorraAlert');
        } else {
            /**
             *      En este caso no tenemos un borrador, lo que haremos sera crear una ficha nueva, un documento nuevo,
             *    y mandarnos a editar
             */
            $oBaseDatos->comienza_transaccion();

            //Sacamos la version del proceso

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('revision', 'perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico',
                'perfil_tareas'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('id=' . $aParametros['documento']));
            $oBaseDatos->consulta();

            $aIterador = $oBaseDatos->coger_Fila();
            if ($aIterador) {
                $sVersion = siguiente_Version($aIterador[0]);

                //Ahora creamos un documento nuevo

                $oBaseDatos->iniciar_Consulta('INSERT');
                $oBaseDatos->pon_Tabla('documentos');
                $oBaseDatos->construir_Campos(array(
                        'estado',
                        'activo',
                        'revision',
                        'nombre',
                        'codigo',
                        'tipo_documento',
                        'calidad',
                        'medioambiente',
                        'perfil_ver',
                        'perfil_nueva',
                        'perfil_modificar',
                        'perfil_revisar',
                        'perfil_aprobar',
                        'perfil_historico',
                        'perfil_tareas')
                );
                $oBaseDatos->pon_Select(
                    'select ' . iBorrador . ',\'t\',\'' . $sVersion . '\',
                nombre,
                codigo,
                tipo_documento,
                calidad,
                medioambiente,
                perfil_ver,
                perfil_nueva,
                perfil_modificar,
                perfil_revisar,
                perfil_aprobar,
                perfil_historico,
                perfil_tareas from documentos where id=' . $aParametros['documento']
                );
                $oBaseDatos->consulta();

                //Ahora la ficha

                $oBaseDatos->iniciar_Consulta('INSERT');
                $oBaseDatos->pon_Tabla('contenido_procesos');
                $oBaseDatos->construir_Campos(array(
                        'documento',
                        'proveedor',
                        'entradas',
                        'propietario',
                        'indicadores',
                        'salidas',
                        'cliente',
                        'doc_asociada',
                        'registros',
                        'indicaciones',
                        'anejos',
                        'flujograma',
                        'instalaciones_ambiente',
                        'proceso')
                );
                $oBaseDatos->pon_Select('SELECT last_value,proveedor,entradas,propietario,' .
                    'indicadores,salidas,cliente,doc_asociada,registros,indicaciones,anejos,flujograma,' .
                    'instalaciones_ambiente,proceso FROM contenido_procesos,documentos_id_seq WHERE id=' . $aParametros['ficha']);
                $oBaseDatos->consulta();
                $oBaseDatos->termina_transaccion();
                $sHtml = "diviframe|<iframe id=\"form\" src=\"/ajax/form?action=inicio:nuevo:formulario:general&sesion=&datos=catalogo:contenidoproc:formulario:editar" . separador . $aParametros['ficha'] . "\"  width=\"100%\"" .
                    " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
            }
        }
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_SacarEditor($sAccion, $aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Tablas(array($_SESSION['tabla']));
        $oBaseDatos->construir_Where(array('(id=\'' . $_SESSION['pagina'][$aParametros['numeroDeFila']] . '\')'));
        switch ($sAccion) {
            case 'sacareditor:mensaje':
                $oBaseDatos->construir_Campos(array('contenido'));
                $oBaseDatos->consulta();
                $aIterador = $oBaseDatos->coger_Fila();
                if ($aIterador) {
                    if ($aIterador[0] != null) {
                        $sHtml = $aIterador[0];
                    } else {
                        $sHtml = gettext('sVacio');
                    }
                }
                break;
            default:
                break;
        }
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function nueva_Revision_Programa($sAccion, $aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdProg = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('revision'));
        $oBaseDatos->construir_Tablas(array('programa_auditoria'));
        $oBaseDatos->construir_Where(array('id=' . $iIdProg));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sSiguiente = siguiente_Version($aIterador[0]);
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('revision'),
                array($sSiguiente));
            $oBaseDatos->construir_Tablas(array('programa_auditoria'));
            $oBaseDatos->construir_Where(array('id=\'' . $iIdProg . '\''));
            $oBaseDatos->consulta();
            $sHtml = gettext('sRevAument') . "<br />";
        }
        $sHtml .= $oVolver->to_Html();
        return $sHtml;
    }


    /**
     * Esta funcion hace vigente alguno de los planes de formacion disponibles
     * @param array $aParametros
     * @return String
     */
    function hacerVigente($sAccion, $aParametros)
    {
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->comienza_transaccion();
        //Ponemos a true la fila seleccionada

        $oBaseDatos->iniciar_Consulta('UPDATE');
        if ($sAccion == "hacerVigente") {
            $oBaseDatos->construir_Tablas(array('plan_formacion'));
            $oBaseDatos->construir_Set(array('vigente'), array('t'));
            $oBaseDatos->construir_Where(array('(id=\'' . $iId . '\')'));
        } else {
            $oBaseDatos->construir_Tablas(array('programa_auditoria'));
            $oBaseDatos->construir_Set(array('vigente'), array('t'));
            $oBaseDatos->construir_Where(array('(id=\'' . $iId . '\')'));
        }
        $oBaseDatos->consulta();
        //Ponemos a false las demas
        $oBaseDatos->iniciar_Consulta('UPDATE');
        if ($sAccion == "hacerVigente") {
            $oBaseDatos->construir_Tablas(array('plan_formacion'));
            $oBaseDatos->construir_Set(array('vigente'), array('f'));
            $oBaseDatos->construir_Where(array('(id<>\'' . $iId . '\')'));
        } else {
            $oBaseDatos->construir_Tablas(array('programa_auditoria'));
            $oBaseDatos->construir_Set(array('vigente'), array('f'));
            $oBaseDatos->construir_Where(array('(id<>\'' . $iId . '\')'));
        }
        $oBaseDatos->consulta();

        $oBaseDatos->termina_transaccion();

        $oBoton = new boton('Volver', 'atras(-1)', 'sincheck');
        $sHtml = gettext('sDocOpCorrecta') . "<br /><br />";
        $sHtml .= $oBoton->to_Html();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_AltaBaja_Areas_Auditorias($sAccion, $aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdAuditoria = $_SESSION['auditoria'];
        $aSplit = explode(":", $sAccion);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('areas'));
        $oBaseDatos->construir_Tablas(array('auditorias'));
        $oBaseDatos->construir_Where(array('id=' . $iIdAuditoria));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            $sDocumentos = $aIterador[0];
        }
        $sDocumentos = trim($sDocumentos, "{}");
        $aDocumentos = explode(",", $sDocumentos);
        $aTrocear = explode(separadorCadenas, $aParametros['filas']);
        $aFilas = str_split($aTrocear[0]);

        //Comenzamos una transaccion
        $oBaseDatos->comienza_transaccion();
        for ($iContador = 0; $iContador < (count($aFilas)); $iContador++) {
            $iIdDocumento = $_SESSION['pagina'][$iContador];
            if ($aFilas[$iContador] == 1) {
                if ($aSplit[2] == 'baja') {
                    //$sHtml.=$sAudQuitaArea."<br />";
                    $sHtml = gettext('sQuitaArea') . "<br /><br />";
                    $iKey = array_search($iIdDocumento, $aDocumentos);
                    unset($aDocumentos[$iKey]);
                    //Buscamos en el array y lo quitamos
                } else {
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $sHtml = gettext('sAudPonArea') . "<br /><br />";
                    $oBaseDatos->construir_SetSin(array('areas'),
                        array('array_append(areas,' . $iIdDocumento . ')'));
                    $oBaseDatos->construir_Tablas(array('auditorias'));
                    $oBaseDatos->construir_Where(array('id=' . $iIdAuditoria));
                    $oBaseDatos->consulta();
                }
            }
        }
        if ($aSplit[2] == 'baja') {
            $sDocumentos = "{" . implode(",", $aDocumentos) . "}";
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('areas'),
                array($sDocumentos));
            //Updatear la db
            $oBaseDatos->construir_Tablas(array('auditorias'));
            $oBaseDatos->construir_Where(array('id=' . $iIdAuditoria));
            $oBaseDatos->consulta();
        }
        $oBaseDatos->termina_transaccion();
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Copiar_Auditorias($sAccion, $aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdAuditoria = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        //Iniciamos la transaccion
        $oBaseDatos->comienza_transaccion();
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('nombre', 'revision'));
        $oBaseDatos->construir_Tablas(array('programa_auditoria'));
        $oBaseDatos->construir_Where(array('id=' . $iIdAuditoria));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            //Primero insertamos el programa
            $oBaseDatos->iniciar_Consulta('INSERT');
            $oBaseDatos->construir_Campos(array('nombre', 'revision', 'activo', 'vigente'));
            $oBaseDatos->construir_Value(array('Copia de ' . $aIterador[0], $aIterador[1], 'true', 'false'));
            $oBaseDatos->construir_Tablas(array('programa_auditoria'));
            //echo $oBaseDatos->to_String_Consulta()."<br />";
            $oBaseDatos->consulta();

            //Ahora copiamos las auditorias
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('id', '(select last_value from programa_auditoria_id_seq)'));
            $oBaseDatos->construir_Tablas(array('auditorias'));
            $oBaseDatos->construir_Where(array('programa=' . $iIdAuditoria));
            $oBaseDatos->consulta();
            $aIdAuditorias = array();
            //Sacamos todos los id de las auditorias pertenecientes al plan
            while ($aIteradorAuditorias = $oBaseDatos->coger_Fila()) {
                $iIdProg = $aIteradorAuditorias[1];
                $aIdAuditorias[] = $aIteradorAuditorias[0];
            }
            if (is_array($aIdAuditorias)) {
                //Las copiamos
                foreach ($aIdAuditorias as $iIdAud) {
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('programa', 'fecha', 'estado', 'descripcion', 'observaciones', 'activo',
                        'requisitos', 'alcance', 'interna', 'areas', 'fecha_realiza', 'lugar_informe',
                        'fecha_informe', 'recomendaciones_informe', 'conclusiones_informe'));
                    $oBaseDatos->pon_Select('select ' . $iIdProg . ',fecha,estado,descripcion,observaciones,activo,' .
                        'requisitos,alcance,interna,areas,fecha_realiza,lugar_informe,fecha_informe,' .
                        'recomendaciones_informe,conclusiones_informe from auditorias where id=' . $iIdAud);
                    $oBaseDatos->construir_Tablas(array('auditorias'));
                    $oBaseDatos->consulta();
                    //Y copiamos su equipo auditor
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('auditorias_id_seq'));
                    $oBaseDatos->consulta();
                    $aIterador = $oBaseDatos->coger_Fila();
                    if ($aIterador) {
                        $oBaseDatos->iniciar_Consulta('INSERT');
                        $oBaseDatos->construir_Campos(array('auditoria', 'usuario_interno', 'nombre', 'tipo', 'documento', 'activo'));
                        $oBaseDatos->pon_Select('select ' . $aIterador[0] . ',usuario_interno,nombre,tipo,documento,activo from auditores where auditoria=' . $iIdAud);
                        $oBaseDatos->construir_Tablas(array('auditores'));
                        //echo $oBaseDatos->to_String_Consulta()."<br />";
                        $oBaseDatos->consulta();
                    }
                }
            }
        }
        $oBaseDatos->termina_transaccion();
        return "Auditoria Copiada<br />" . $oVolver->to_Html();
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function confirma_Alta_Baja($sAccion, $aParametros)
    {

        $iUsuario = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBoton = new boton("Volver", "atras(-1)", "noafecta");
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb->iniciar_Consulta('SELECT');
        $aCampos = array('alumnos.inscrito', 'alumnos.verificado');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('alumnos'));
        $oDb->construir_Where(array('alumnos.curso=' . $_SESSION['curso'], 'alumnos.id=' . $iUsuario));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $sInscrito = $aIterador[0];
            $sVerificado = $aIterador[1];
        } else {
            $sInscrito = null;
            $sVerificado = null;
        }

        //comprobamos en que caso estaba el usuario
        //Este booleano lo usamos para poner el boton o no dependiendo si devolvemos un alert o no
        $bContenedor = false;
        switch ($sAccion) {
            case 'formacion:confirmaralumno:alta:fila':
                switch ($sInscrito) {
                    case 't':
                        switch ($sVerificado) {
                            case 't':
                                $sHtml = "alert|" . gettext('sAltay');
                                break;
                            case 'f':
                                //Verificar alta
                                $oDb->iniciar_Consulta('UPDATE');
                                $oDb->pon_Tabla('alumnos');
                                $oDb->construir_Set(array('inscrito', 'verificado'), array('t', 't'));
                                $oDb->construir_Where(array('id=' . $iUsuario, 'curso=' . $_SESSION['curso']));
                                $oDb->consulta();
                                $bContenedor = true;
                                $sHtml = "contenedor|" . gettext('sAltav');
                                break;
                            default:
                        }
                        break;
                    case 'f':
                        $sHtml = "alert|" . gettext('sCursoNoPeticion');
                        break;
                    default:
                }
                break;

            case 'formacion:confirmaralumno:baja:fila':
                switch ($sInscrito) {
                    case 't':
                        $sHtml = "alert|" . gettext('sCursoNoBaja');
                        break;
                    case 'f':
                        switch ($sVerificado) {
                            case 't':
                                $sHtml = "alert|" . gettext('sBajay');
                                break;
                            case 'f':
                                //Verificar baja
                                $oDb->iniciar_Consulta('UPDATE');
                                $oDb->pon_Tabla('alumnos');
                                $oDb->construir_Set(array('inscrito', 'verificado'), array('f', 't'));
                                $oDb->construir_Where(array('id=' . $iUsuario, 'curso=' . $_SESSION['curso']));
                                $oDb->consulta();
                                $bContenedor = true;
                                $sHtml = "contenedor|" . gettext('sBajav');
                                break;
                            default:
                        }
                    default:
                }
                break;
            default:
        }
        if ($bContenedor) {
            $sHtml .= "<br /><br />" . $oBoton->to_Html();
        }
        $oDb->desconexion();
        return $sHtml;
    }


    /**
     * Funcion para eliminar alumnos de los cursos.
     * @param $sCodigo
     * @param $aDatos
     * @return string
     */
    function altabaja_Alumnos($sCodigo, $aDatos)
    {
        $oVolver = new boton(gettext('sMCVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $aOpciones = explode(":", $sCodigo, 4);
        if ($aOpciones[2] == 'alta') {
            $sTipo = 'alta';
        }
        if ($aOpciones[2] == 'baja') {
            $sTipo = 'baja';
        }
        $aTrocear = explode(separadorCadenas, $aDatos['filas']);
        $aFilas = str_split($aTrocear[0]);
        $aElegidos = array();
        //Comenzamos una transaccion
        $oBaseDatos->comienza_transaccion();
        $i = 0;
        for ($iContador = 0; $iContador < (count($aFilas)); $iContador++) {
            if ($aFilas[$iContador]) {
                if ($sTipo == 'alta') {
                    //Comprobamos que no este el usuario en la base de datos
                    $iIdUsuario = $_SESSION['pagina'][$iContador];
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('usuarios.id'));
                    $oBaseDatos->construir_Tablas(array('alumnos', 'usuarios'));
                    $oBaseDatos->construir_Where(array('alumnos.curso=' . $_SESSION['curso'],
                        'alumnos.usuario=usuarios.id', 'usuarios.id=' . $iIdUsuario));
                    $oBaseDatos->consulta();
                    if (!($aDevolver = $oBaseDatos->coger_Fila())) {
                        $oBaseDatos->iniciar_Consulta('INSERT');
                        $oBaseDatos->construir_Campos(array('usuario', 'curso', 'inscrito', 'verificado'));
                        $oBaseDatos->construir_Tablas(array('alumnos'));
                        $oBaseDatos->construir_Value(array($iIdUsuario, $_SESSION['curso'], 't', 't'));
                        $oBaseDatos->consulta();
                        $i++;
                    }
                } else {
                    $iIdAlumno = $_SESSION['pagina'][$iContador];
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('usuario'));
                    $oBaseDatos->construir_Tablas(array('alumnos'));
                    $oBaseDatos->construir_Where(array('alumnos.id=' . $iIdAlumno));
                    $oBaseDatos->consulta();
                    $aAlumno = $oBaseDatos->coger_Fila();
                    $iIdUsuario = $aAlumno[0];
                    $sSql = "DELETE FROM alumnos WHERE curso=" . $_SESSION['curso'] . " AND usuario=" . $iIdUsuario;
                    $oBaseDatos->consulta($sSql);
                    $i++;
                }
            }
        }
        $oBaseDatos->termina_transaccion();
        if ($sTipo == 'alta') {
            $sHtml = "Añadidos " . $i . " Alumnos<br />";
        } else {
            $sHtml = "Eliminados " . $i . " Alumnos<br />";
        }
        return "contenedor|" . $sHtml . $oVolver->to_Html();
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Grafica_Indicadores($sAccion, $aParametros)
    {
        if (($aParametros['numeroDeFila'] != -1) && ($aParametros['numeroDeFila'] != null)) {
            $_SESSION['indicadorgrafica'] = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        }
        if ($aParametros['tipo'] == null) {
            $sTipo = 1;
        } else {
            $sTipo = $aParametros['tipo'];
        }
        if ($aParametros['modo'] == null) {
            $sModo = 'bar';
        } else {
            $sModo = $aParametros['modo'];
        }
        //ICS
        $_SESSION['contenido_proceso'] = 0;
        //ICS
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $oTipos = new desplegable(gettext('sPCTipoGrafica'), null, array(1 => '12 ultimos meses', 2 => 'Año actual', 3 => 'Historico', 4 => 'Comparativa 2 ultimos a�os', 5 => 'Comparativa todos los a�os'), null, 'desgrafica');
        $oModos = new desplegable(gettext('sPCModoVis'), null, array('bar' => 'Barras', 'line' => 'Lineas'), null, 'modgrafica');
        $oVer = new boton(gettext('sPCVerGrafica'), "sndReq('indicadores:graficaindicador:comun:ver:grafica','',1)", "noafecta");
        $oDb->iniciar_consulta('SELECT');
        $oDb->construir_Campos(array('valor'));
        $oDb->construir_Tablas(array('valores'));
        $oDb->construir_Where(array('indicador=' . $_SESSION['indicadorgrafica'],
            'proceso=' . $_SESSION['contenido_proceso']));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $sImg = "<div align='center'><img src=\"graficaIndicadores.php?indicador=" . $_SESSION['indicadorgrafica'] .
                "&proceso=" . $_SESSION['contenido_proceso'] . "&tipo=" . $sTipo . "&modo=" . $sModo . "\">";
            $sHtml = "contenedor|" . $sImg . "<br /><br />";
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('nombre'));
            $oDb->construir_Tablas(array('indicadores'));
            $oDb->construir_Where(array('id=' . $_SESSION['indicadorgrafica']));
            $oDb->consulta();
            if ($aIterador = $oDb->coger_Fila()) {
                $sNombreIndicador = $aIterador[0];
            }
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('procesos.nombre'));
            $oDb->construir_Tablas(array('procesos', 'contenido_procesos'));
            $oDb->construir_Where(array('contenido_procesos.id=' .
                $_SESSION['contenido_proceso'], 'procesos.id=contenido_procesos.proceso'));
            $oDb->consulta();
            if ($aIterador = $oDb->coger_Fila()) {
                $sNombreProceso = $aIterador[0];
            }
            $sHtml .= gettext('sGrafID') . ': <b>' .
                $sNombreIndicador . '</b> ' . gettext('sOfProc') . ' <b>' . $sNombreProceso . "</b><br /><br />";
            switch ($sTipo) {
                case '1':
                    {
                        $sHtml .= gettext('sGrafYear') . "<br /><br />";
                        break;
                    }
                case '2':
                    {
                        $sHtml .= gettext('sGrafYearAc') . "<br /><br />";
                        break;
                    }
                case '3':
                    {
                        $sHtml .= gettext('sGrafHist') . "<br /><br />";
                        break;
                    }
            }
            $sHtml .= $oTipos->to_Html() . "<br /><br />";

            $sHtml .= $oModos->to_Html() . "<br /><br />";

            $sHtml .= $oVolver->to_Html() . $oVer->to_Html() . "<br /><br />" . "</div>";
        } else {
            $sHtml = "contenedor|" . gettext('sNoPuedeGraf') . "<br /><br />" . $oVolver->to_Html();
        }
        return $sHtml;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_AltaBaja_Filas_Arbol($aParametros)
    {
        $oVolver = new boton(gettext('sPCOVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

        $iId = $aParametros['filas'];
        $oBaseDatos->iniciar_Consulta('UPDATE');
        $oBaseDatos->construir_Set(array('activo'),
            array('f'));
        $oBaseDatos->construir_Tablas(array('procesos'));
        $oBaseDatos->construir_Where(array('(id=\'' . $iId . '\')'));
        $oBaseDatos->consulta();
        $sHtml = "<br />" . gettext('sProcesoBaja') . "<br />";
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * Damos de alta o de baja en diversas tablas segun la opcion
     * @param array $aParametros
     * @param string $sAccion
     * @return String
     */

    function procesa_AltaBaja_Filas($sAccion, $aParametros)
    {
        $oVolver = new boton(gettext('sPCOVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $aSplit = explode(":", $sAccion);
        $aTroceo = explode(separadorCadenas, $aParametros['filas']);

        $aFilas = str_split($aTroceo[0]);
        $sHtml = "";
        $oBaseDatos->comienza_transaccion();

        //     Este trozo de codigo esta hecho para dar de baja una sola fila por si mas adelante es necesario
        if ($aSplit[3] == 'fila') {

            $oBaseDatos->iniciar_Consulta('UPDATE');
            if ($aSplit[2] == 'baja') {
                $oBaseDatos->construir_Set(array('activo'),
                    array('f'));
            } else {
                $oBaseDatos->construir_Set(array('activo'),
                    array('t'));
            }
            $oBaseDatos->construir_Tablas(array($_SESSION['tabla']));
            $oBaseDatos->construir_Where(array('(id=\'' . $_SESSION['pagina'][$aFilas[0]] . '\')'));
            $oBaseDatos->consulta();
            $sHtml .= "<br />" . gettext('sFilaActual') . "<br />";
        } else {

            for ($iContador = 0; $iContador < (count($aFilas)); $iContador++) {
                if ($aFilas[$iContador] == 1) {
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    if ($aSplit[2] == 'baja') {
                        $oBaseDatos->construir_Set(array('activo'),
                            array('f'));

                    } else {
                        $oBaseDatos->construir_Set(array('activo'),
                            array('t'));
                    }
                    $oBaseDatos->construir_Tablas(array($_SESSION['tabla']));
                    $oBaseDatos->construir_Where(array('(id=\'' . $_SESSION['pagina'][$iContador] . '\')'));
                    //    $aConsultas[]=$oBaseDatos->to_String_Consulta();
                    $oBaseDatos->consulta();


                    $sHtml .= "<br />" . gettext('sFilaActual') . "<br />";
                }
            }
        }
        $oBaseDatos->termina_transaccion();
        $sHtml .= "<br />" . $oVolver->to_Html();
        return $sHtml;
    }


    /**
     * Esta funcion devuelve el calendario para el mes pedido
     * @param array $aParametros
     * @return String
     */
    function procesa_Calendario_Mes($aParametros)
    {
        $iMes = $aParametros['mes'];
        $newCal = new BaseCalendar($aParametros['procesa']);
        $sCal = "<table class=\"mensual\" bgcolor=\"#ffffff\" border =0>";
        $sCal .= "<tr><td><table class=\"hoy\" width=\"100%\"><tr><td>";
        $sCal .= "<td align='center'><b onmouseover=\"this.classname='encima_casilla'\"";
        $sCal .= " onMouseout=\"this.className='fuera-casilla'\" onclick=\"parent.sndReq('calendario','0','1','" .
            ($iMes - 1) . separador . $aParametros['procesa'] . "')\"></b></td>";
        $sCal .= "<td align='center'><b onclick=\"parent.sndReq('calendario','0','1','0" .
            separador . $aParametros['procesa'] . "')\">" . gettext('sCalDia') . "</b></td>";
        $sCal .= "<td align='center'><b onmouseover=\"this.classname='encima_casilla'\"";
        $sCal .= " onMouseout=\"this.className='fuera-casilla'\" onclick=\"parent.sndReq('calendario','0','1','" .
            ($iMes + 1) . separador . $aParametros['procesa'] . "')\"></b></td>";
        $sCal .= "</tr></table></td></tr><tr><td><br />";
        $sCal .= $newCal->displayMonth($iMes);
        $sCal .= "</td></tr></table></table>";
        return ($sCal);
    }


    /**
     * Esta funcion devuelve el valor a poner en el campo del formulario
     * @param array $aParametros
     * @param string $sAccion
     * @return String
     */
    function procesa_FilaAForm($sAccion, $aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['fila']];
        $sCampo = $aParametros['campo'];
        unset($_SESSION['campoform']);
        if ($_SESSION['listadoaform'] == "usuarios") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido'));
            $oBaseDatos->construir_Tablas(array('usuarios'));
            $oBaseDatos->construir_Where(array('usuarios.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "indicadores") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('indicadores.nombre'));
            $oBaseDatos->construir_Tablas(array('indicadores'));
            $oBaseDatos->construir_Where(array('indicadores.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "documentosext") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('documentos.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "ficha") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('ficha_personal.codigo||\' \'||ficha_personal.nombre'));
            $oBaseDatos->construir_Tablas(array('ficha_personal'));
            $oBaseDatos->construir_Where(array('ficha_personal.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "fichaleg") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('documentos.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "ley") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('documentos.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "requisitos") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('requisitos_puesto.codigo||\' \'||requisitos_puesto.nombre'));
            $oBaseDatos->construir_Tablas(array('requisitos_puesto'));
            $oBaseDatos->construir_Where(array('requisitos_puesto.id=\'' . $iId . '\''));
        } else if ($_SESSION['listadoaform'] == "id_ficha") {
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo||\' \'||documentos.nombre'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->construir_Where(array('documentos.id=\'' . $iId . '\''));
        }
        $oBaseDatos->consulta();
        $aFila = $oBaseDatos->coger_Fila();
        $sDato = $aFila[0];
        $sHtml = $iId . separador . $sCampo . separador . $sDato;
        return $sHtml;
    }


    /**
     * Funcion que procesa las altas y bajas de los cursos
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Alta_Baja($sAccion, $aParametros)
    {
        $iCurso = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBoton = new boton("Volver", "atras(-1)", "noafecta");

        //Primero vemos si el curso esta disponible para inscribirse o darse de baja

        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDb->iniciar_Consulta('SELECT');
        $aCampos = array('tipo_estados_curso.nombre');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('cursos', 'tipo_estados_curso'));
        $oDb->construir_Where(array('cursos.id=' . $iCurso, 'cursos.estado=tipo_estados_curso.id'));
        $oDb->consulta();

        if ($aIterador = $oDb->coger_Fila()) {
            $sEstadoCurso = $aIterador[0];
        }
        if ($sEstadoCurso == gettext('sEstadoAbierto')) {

            /**
             *    Una vez hemos sacado que el curso esta abierto para la inscripcion vemos en que estado estaba el usuario
             *    para insertar su peticion o gestionarla
             */

            $oDb->iniciar_Consulta('SELECT');
            $aCampos = array('alumnos.inscrito', 'alumnos.verificado');
            $oDb->construir_Campos($aCampos);
            $oDb->construir_Tablas(array('alumnos'));
            $oDb->construir_Where(array('alumnos.curso=' . $iCurso, 'alumnos.usuario=' . $_SESSION['userid']));
            $oDb->consulta();
            if ($aIterador = $oDb->coger_Fila()) {
                $sInscrito = $aIterador[0];
                $sVerificado = $aIterador[1];
            } else {
                $sInscrito = null;
                $sVerificado = null;
            }
            switch ($sAccion) {
                case 'formacion:cursos:comun:curso:alta':
                case 'formacion:inscripcion:alta:fila':
                    switch ($sInscrito) {
                        case 't':
                            switch ($sVerificado) {
                                case 't':
                                    $sHtml = gettext('sCursoInscrito');
                                    break;
                                case 'f':
                                    $sHtml = gettext('sCursoSolicitado');
                                    break;
                                default:
                            }
                            break;
                        case 'f':
                            switch ($sVerificado) {
                                case 't':
                                    //Esto es que nos han dado de baja del curso anteriormente
                                    $oDb->iniciar_Consulta('UPDATE');
                                    $oDb->pon_Tabla('alumnos');
                                    $oDb->construir_Set(array('inscrito', 'verificado'), array('t', 'f'));
                                    $oDb->construir_Where(array('usuario=' . $_SESSION['userid'], 'curso=' . $iCurso));
                                    $oDb->consulta();
                                    $sHtml = gettext('sCursoPedirAlta');
                                    break;
                                case 'f':
                                    /**
                                     * Esto es que hemos pedido la baja del curso pero no la han confirmado,
                                     * actuamos quitando la peticion de baja
                                     */
                                    $oDb->iniciar_Consulta('UPDATE');
                                    $oDb->pon_Tabla('alumnos');
                                    $oDb->construir_Set(array('inscrito', 'verificado'), array('t', 't'));
                                    $oDb->construir_Where(array('usuario=' . $_SESSION['userid'], 'curso=' . $iCurso));
                                    $oDb->consulta();
                                    $sHtml = gettext('sCursoNoBaja');
                                    break;
                                default:
                            }
                            break;
                        default:
                            //Si es null es decir no existia la fila a insertar
                            $oDb->iniciar_Consulta('INSERT');
                            $oDb->pon_Tabla('alumnos');
                            $oDb->construir_Campos(array('usuario', 'curso', 'inscrito', 'verificado'));
                            $oDb->construir_Value(array($_SESSION['userid'], $iCurso, 't', 'f'));
                            $oDb->consulta();
                            $sHtml = gettext('sCursoInscripcion');
                    }
                    break;

                case 'formacion:inscripcion:baja:fila':
                case 'formacion:cursos:comun:curso:baja':
                    switch ($sInscrito) {
                        case 't':
                            switch ($sVerificado) {
                                case 't':
                                    //Esto es que pedimos la baja del curso
                                    $oDb->iniciar_Consulta('UPDATE');
                                    $oDb->pon_Tabla('alumnos');
                                    $oDb->construir_Set(array('inscrito', 'verificado'), array('f', 'f'));
                                    $oDb->construir_Where(array('usuario=' . $_SESSION['userid'], 'curso=' . $iCurso));
                                    $oDb->consulta();
                                    $sHtml = gettext('sCursoPedirBaja');
                                    break;
                                case 'f':
                                    $oDb->iniciar_Consulta('UPDATE');
                                    $oDb->pon_Tabla('alumnos');
                                    $oDb->construir_Set(array('inscrito', 'verificado'), array('f', 't'));
                                    $oDb->construir_Where(array('usuario=' . $_SESSION['userid'], 'curso=' . $iCurso));
                                    $oDb->consulta();
                                    $sHtml = gettext('sCursoCancel');
                                    break;
                                default:
                            }
                            break;
                        case 'f':
                            switch ($sVerificado) {
                                case 't':
                                    $sHtml = gettext('sCursoBaja');
                                    break;
                                case 'f':
                                    $sHtml = gettext('sCursoSolBaja');
                                    break;
                                default:
                            }
                            break;
                        default:
                            //Si es null es que nunca ha estado inscrito en el curso
                            $sHtml = gettext('sCursoNoInscrito');
                    }
                    break;
            }
        } else {
            $sHtml = gettext('sCursoNoDisp');
        }
        $sHtml .= "<br /><br />" . $oBoton->to_Html();
        $oDb->desconexion();
        return $sHtml;
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function subir_Fichero_Adjunto($sAccion, $aParametros)
    {
        return "contenedor|<iframe id=\"formsubir\" src=\"/ajax/form?action=auditorias:iframe:upload:nuevo:adjunto&sesion=&datos=" .
            $_SESSION['pagina'][$aParametros['numeroDeFila']] . "\"  width=\"100%\"" .
            " frameborder=\"0\"  style=\"z-index: 0\"><\iframe>";
    }


    /**
     *     Esta es nuestra funcion recursiva, le pasamos el arbol donde iran todos los nodos, el nodo padre, es decir al
     *  que tenemos que enganchar los nodos que saquemos, el nivel maximo sobre el que permitiremos recursividad
     *
     * @param $oArbol object
     * @param $oPadre object
     * @param $iNivel integer
     * @param integer $iIdPadre
     */
    function sacar_hijos(&$oArbol, &$oPadre, $iNivel, $iIdPadre = null)
    {

        if (!(($iNivel > 6) || ($iIdPadre == null))) {
            //Creamos el nodo y lo enganchamos
            $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id', 'nombre'));
            $oDb->construir_Tablas(array('procesos'));
            $oDb->construir_Where(array('padre=' . $iIdPadre));
            $oDb->construir_Order(array('nombre'));
            $oDb->consulta();
            //Cogemos todos los hijos
            $aHijos = array();
            while ($aIterador = $oDb->coger_Fila()) {
                $oArbol->valor_check(0, $aIterador[0]);
                $nodo = $oArbol->Nuevo_Nodo($aIterador[1], 'a3', true);
                $this->sacar_hijos($oArbol, $nodo, $iNivel + 1, $aIterador[0]);
                $aHijos[] = $aIterador[0];
                $oArbol->Situa_Nodo($nodo, $oPadre);
            }
        }
    }

    /**
     * @param $sAccion
     * @param $aParametros
     * @return string
     */
    function procesa_Copiar_Perfil($sAccion, $aParametros)
    {

        $iIdPerfil = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('last_value'));
        $oBaseDatos->construir_Tablas(array('perfiles_id_seq'));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] < iNumeroPerfiles) {
            //Copiamos el perfil
            $oBaseDatos->comienza_transaccion();
            //Creamos la copia del perfil
            $oBaseDatos->iniciar_Consulta('INSERT');
            $oBaseDatos->pon_Tabla('perfiles');
            $oBaseDatos->construir_Campos(array('nombre', 'activo'));
            $oBaseDatos->pon_Select('SELECT \'copia de \'||nombre, \'t\' FROM perfiles WHERE id=' . $iIdPerfil);
            $oBaseDatos->consulta();
            //Ahora ponemos los permisos de forma adecuada

            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('last_value'));
            $oBaseDatos->construir_Tablas(array('perfiles_id_seq'));
            $oBaseDatos->consulta();
            $aIterador = $oBaseDatos->coger_Fila();
            $iIdUltimoPerfil = $aIterador[0];
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_SetSin(array('perfil_ver[' . $iIdUltimoPerfil . ']', 'perfil_nueva[' . $iIdUltimoPerfil . ']',
                'perfil_modificar[' . $iIdUltimoPerfil . ']', 'perfil_revisar[' . $iIdUltimoPerfil . ']',
                'perfil_aprobar[' . $iIdUltimoPerfil . ']', 'perfil_historico[' . $iIdUltimoPerfil . ']',
                'perfil_tareas[' . $iIdUltimoPerfil . ']'
            ),
                array('perfil_ver[' . $iIdPerfil . ']', 'perfil_nueva[' . $iIdPerfil . ']',
                    'perfil_modificar[' . $iIdPerfil . ']', 'perfil_revisar[' . $iIdPerfil . ']',
                    'perfil_aprobar[' . $iIdPerfil . ']', 'perfil_historico[' . $iIdPerfil . ']',
                    'perfil_tareas[' . $iIdPerfil . ']'));
            $oBaseDatos->construir_Tablas(array('documentos'));
            $oBaseDatos->consulta();

            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_SetSin(array('permisos[' . $iIdUltimoPerfil . ']'),
                array('permisos[' . $iIdPerfil . ']'));
            $oBaseDatos->construir_Tablas(array('menu_nuevo'));
            $oBaseDatos->consulta();

            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_SetSin(array('permisos[' . $iIdUltimoPerfil . ']'),
                array('permisos[' . $iIdPerfil . ']'));
            $oBaseDatos->construir_Tablas(array('botones'));
            $oBaseDatos->consulta();

            $oBaseDatos->termina_transaccion();
            $sHtml = gettext('sCreadoPerfil');
        } else {
            $sHtml = "<br />" . gettext('sNoCreadoPerfil');
        }
        return $sHtml . "<br />" . $oVolver->to_Html();
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_nuevaversion_Objetivos($aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];

        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('version', 'estado', 'nombre'));
        $oBaseDatos->construir_Tablas(array('objetivos_globales'));
        $oBaseDatos->construir_Where(array('id=' . $iId));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador) {
            if ($aIterador[1] == 1) {
                //Comprobamos que no haya ya un borrador
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('version', 'estado', 'nombre'));
                $oBaseDatos->construir_Tablas(array('objetivos_globales'));
                $oBaseDatos->construir_Where(array('nombre=\'' . $aIterador[2] . '\'', 'estado=4 OR estado=2'));
                $oBaseDatos->consulta();
                if ($aIteradorInterno = $oBaseDatos->coger_Fila()) {
                    $sHtml = "Ya existe un borrador";
                } else {
                    //Esta en vigor por lo que creamos una nueva version
                    $sNuevaVersion = siguiente_Version($aIterador[0]);
                    $sNombre = $aIterador[2];
                    $sEstado = 2;
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->pon_Tabla('objetivos_globales');
                    $oBaseDatos->construir_Campos(array('nombre', 'estado', 'version'));
                    $oBaseDatos->construir_Value(array($sNombre, $sEstado, $sNuevaVersion));
                    $oBaseDatos->consulta();
                    $sHtml = "Creada la nueva version";
                }
            } else {
                $sHtml = "Las nuevas versiones se hacen a partir de una version en vigor";
            }
        } else {
            $sHtml = "Se ha producido un error.";
        }
        $sHtml .= "<br />" . $oVolver->to_Html();
        $sDevolver = "contenedor|" . $sHtml;
        return $sDevolver;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Aprobar_Objetivos($aParametros)
    {

        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('estado', 'nombre'));
        $oBaseDatos->construir_Tablas(array('objetivos_globales'));
        $oBaseDatos->construir_Where(array('id=' . $iId));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        if ($aIterador[0] == 4) {
            //Pasamos el otro objetivo a historico
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->pon_Set('estado', 6);
            $oBaseDatos->pon_Set('activo', 'f');
            $oBaseDatos->construir_Tablas(array('objetivos_globales'));
            $oBaseDatos->construir_Where(array('nombre=\'' . $aIterador[1] . '\'', 'estado=1'));
            $oBaseDatos->consulta();

            //Ponemos a vigor el nuevo

            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('estado', 'aprobadopor', 'fecha_aprobacion'),
                array(1, $_SESSION['userid'], 'now()'));
            $oBaseDatos->construir_Tablas(array('objetivos_globales'));
            $oBaseDatos->construir_Where(array('id=' . $iId));
            $oBaseDatos->consulta();
            $sDevolver = "contenedor|" . gettext('sDocAprobado') . "<br />" . $oVolver->to_Html();
        } else {
            $sDevolver = "alert|El documento debe estar en estado revisado para poder ser aprobado";
        }
        return $sDevolver;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Aprobar_Objetivos_Indicadores($aParametros)
    {
        $oVolver = new boton("Volver", "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('estado'));
        $oBaseDatos->construir_Tablas(array('objetivos'));
        $oBaseDatos->construir_Where(array('id=' . $iId));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();
        if ($aIterador[0] == 4) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('estado', 'aprobadopor', 'fecha_aprobacion'),
                array(1, $_SESSION['userid'], 'now()'));
            $oBaseDatos->construir_Tablas(array('objetivos'));
            $oBaseDatos->construir_Where(array('id=' . $iId));
            $oBaseDatos->consulta();
            $sDevolver = "contenedor|Documento aprobado correctamente<br />" . $oVolver->to_Html();
        } else {
            $sDevolver = "alert|El documento debe estar en estado revisado para poder ser aprobado";
        }
        return $sDevolver;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Revisar_Objetivos($aParametros)
    {

        $oVolver = new boton(gettext('sPNVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('estado'));
        $oBaseDatos->construir_Tablas(array('objetivos_globales'));
        $oBaseDatos->construir_Where(array('id=' . $iId));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        if ($aIterador[0] == 2) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('estado', 'revisadopor', 'fecha_revision'),
                array(4, $_SESSION['userid'], 'now()'));
            $oBaseDatos->construir_Tablas(array('objetivos_globales'));
            $oBaseDatos->construir_Where(array('id=' . $iId));
            $oBaseDatos->consulta();
            $sDevolver = "contenedor|Revision correcta<br />" . $oVolver->to_Html();
        } else {
            $sDevolver = "alert|El documento ya esta revisado";
        }
        return $sDevolver;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Revisar_Objetivos_Indicadores($aParametros)
    {

        $oVolver = new boton(gettext('sPNVolver'), "atras(-1)", "noafecta");
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iId = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oBaseDatos->iniciar_Consulta('SELECT');
        $oBaseDatos->construir_Campos(array('estado'));
        $oBaseDatos->construir_Tablas(array('objetivos'));
        $oBaseDatos->construir_Where(array('id=' . $iId));
        $oBaseDatos->consulta();
        $aIterador = $oBaseDatos->coger_Fila();

        if ($aIterador[0] == 2) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('estado', 'revisadopor', 'fecha_revision'),
                array(4, $_SESSION['userid'], 'now()'));
            $oBaseDatos->construir_Tablas(array('objetivos'));
            $oBaseDatos->construir_Where(array('id=' . $iId));
            $oBaseDatos->consulta();
            $sDevolver = "contenedor|Revision correcta<br />" . $oVolver->to_Html();
        } else {
            $sDevolver = "alert|El documento ya esta revisado";
        }
        return $sDevolver;
    }

    /**
     * @param $sMenu
     * @param $aParamtros
     * @return string
     */
    function procesa_Grafica_Mensajes($sMenu, $aParamtros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oVolver = new boton(gettext('sPCVolver'), "atras(-1)", "noafecta");
        $oDb->iniciar_consulta('SELECT');
        $oDb->construir_Campos(array('count(id)'));
        $oDb->construir_Tablas(array('mensajes'));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $iNumeroMensajes = $aIterador[0];
        }
        $oDb->iniciar_consulta('SELECT');
        $oDb->construir_Campos(array('count(id)'));
        $oDb->construir_Tablas(array('mensajes'));
        $oDb->construir_Where(array('destinatario=0'));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $iNumeroMensajesGlobales = $aIterador[0];
        }
        $sImg = "<img src=\"/graficamensajes.php\">";
        $sHtml = $sImg . "<br /><br />";
        $sHtml .= "Mensajes totales en el sistema: " . $iNumeroMensajes . "<br />";
        $sHtml .= "Mensajes globales: " . $iNumeroMensajesGlobales . "<br />";
        $sHtml .= $oVolver->to_Html();
        return $sHtml;
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Permisos_Botones($aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbBotones = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbCount = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdBoton = $aParametros['id'];
        $_SESSION['idBot'] = $aParametros['id'];

        $iAux = str_replace("contenedor_derecha", "", $iIdBoton);

        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('botones_idiomas.valor as valor', 'menu_idiomas_nuevo.valor as menu_valor'));
        $oDb->construir_Tablas(array('botones_idiomas', 'idiomas', 'botones', 'menu_idiomas_nuevo', 'menu_nuevo'));
        $oDb->construir_Where(array('botones_idiomas.boton = ' . $iIdBoton,
            'botones_idiomas.idioma_id = ' . $_SESSION['idiomaid'],
            'botones.menu=menu_nuevo.id', 'botones.id=' . $iIdBoton,
            'menu_idiomas_nuevo.idioma_id=' . $_SESSION['idiomaid'],
            'menu_idiomas_nuevo.menu=menu_nuevo.id'));
        $oDb->consulta();

        if ($aIterador = $oDb->coger_Fila()) {
            $sTitulo = $aIterador[1] . "->" . $aIterador[0];
        } else $sTitulo = '';


        $sHtml = "<table>";
        $sHtml .= "<tr>";
        $sHtml .= "<th>Permisos: " . $sTitulo . "</th>";
        $sHtml .= "</tr>";

        // selecciona los permisos para el boton
        $aCampos = array('id', "nombre as \"" . gettext('sPNNombre') . "\"");
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('perfiles'));
        $oDb->construir_Order(array('id'));
        $oDb->construir_Where(array("activo='t'"));
        $oDb->consulta();

        while ($aIterador = $oDb->coger_Fila()) {
            if ($aIterador[0] > 0) {
                $sHtml .= "<tr>";
                $sHtml .= "<td>" . $aIterador[1] . "<td>";
                $aCampos = array('id', "permisos[$aIterador[0]] as permisos");
                $oDbBotones->iniciar_Consulta('SELECT');
                $oDbBotones->construir_Campos($aCampos);
                $oDbBotones->construir_Tablas(array('botones'));
                $oDbBotones->construir_Where(array('id=' . $iIdBoton));
                $oDbBotones->consulta();
                $aPermisos = $oDbBotones->coger_Fila();
                if ($aPermisos[1] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"permisos_" . $aIterador[0] . "\" NAME=\"permisos" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"permisos_" . $aIterador[0] . "\" NAME=\"permisos" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
        }

        $sHtml .= "</table>";

        $aCampos = array("count(id)");
        $oDbCount->iniciar_Consulta('SELECT');
        $oDbCount->construir_Campos($aCampos);
        $oDbCount->construir_Tablas(array('perfiles'));
        $oDbCount->construir_Where(array("activo='t'", "id <> 0"));
        $oDbCount->consulta();
        $aNumPerfiles = $oDbCount->coger_Fila();
        $oPermisos = new boton(
            gettext('sPNActualizar'),
            "parent.sndReq('administracion:permisobotones:comun:cambiar:permisos','',1)",
            "noafecta"
        );
        $sHtml .= "<br />" . $oPermisos->to_Html();
        return ($sHtml);
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesa_Permisos_Menu($aParametros)
    {
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbMenu = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbCount = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdMenu = $aParametros['id'];
        $_SESSION['idMenu'] = $aParametros['id'];

        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('menu_idiomas_nuevo.valor as valor'));
        $oDb->construir_Tablas(array('menu_idiomas_nuevo', 'idiomas'));
        $oDb->construir_Where(array('menu_idiomas_nuevo.menu = ' . $_SESSION['idMenu'],
            'menu_idiomas_nuevo.idioma_id = idiomas.id',
            'idiomas.nombre = \'' . $_SESSION['idioma'] . '\''));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $sTitulo = $aIterador[0];
        } else $sTitulo = '';

        $sHtml = "<table>";
        $sHtml .= "<tr>";
        $sHtml .= "<th>Permisos: " . $sTitulo . "</th>";
        $sHtml .= "</tr>";

        // selecciona los permisos para el boton
        $aCampos = array('id', "nombre as \"" . gettext('sPNNombre') . "\"");
        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos($aCampos);
        $oDb->construir_Tablas(array('perfiles'));
        $oDb->construir_Order(array('id'));
        $oDb->construir_Where(array("activo='t'"));
        $oDb->consulta();

        while ($aIterador = $oDb->coger_Fila()) {
            if ($aIterador[0] > 0) {
                $sHtml .= "<tr>";
                $sHtml .= "<td>" . $aIterador[1] . "</td><td>";
                $aCampos = array('id', "permisos[$aIterador[0]] as permisos");
                $oDbMenu->iniciar_Consulta('SELECT');
                $oDbMenu->construir_Campos($aCampos);
                $oDbMenu->construir_Tablas(array('menu_nuevo'));
                $oDbMenu->construir_Where(array('id=' . $iIdMenu));
                $oDbMenu->consulta();
                $aPermisos = $oDbMenu->coger_Fila();
                if ($aPermisos[1] == 'f') {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"permisos_" .
                        $aIterador[0] . "\" NAME=\"permisos" . $aIterador[0] . "\">";
                } else {
                    $sHtml .= "<INPUT TYPE=CHECKBOX ID=\"permisos_" .
                        $aIterador[0] . "\" NAME=\"permisos" . $aIterador[0] . "\" checked>";
                }
                $sHtml .= "</td>";
                $sHtml .= "</tr>";
            }
        }
        $sHtml .= "</table>";
        $aCampos = array("count(id)");
        $oDbCount->iniciar_Consulta('SELECT');
        $oDbCount->construir_Campos($aCampos);
        $oDbCount->construir_Tablas(array('perfiles'));
        $oDbCount->construir_Where(array("activo='t'", "id <> 0"));
        $oDbCount->consulta();
        $aNumPerfiles = $oDbCount->coger_Fila();
        $oPermisos = new boton(
            gettext('sPNActualizar'),
            "parent.sndReq('administracion:permisomenu:comun:cambiar:permisos','',1)",
            "noafecta"
        );
        $sHtml .= "<br />" . $oPermisos->to_Html();
        return ($sHtml);
    }

    /**
     * @param $aParametros
     * @return string
     */
    function procesar_Permisos_Botones($aParametros)
    {
        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdBot = $_SESSION['idBot'];
        // Arrays de valores
        $aValores = array(0 => 'f',
            1 => 't');
        $oBaseDatos->comienza_transaccion();

        foreach ($aParametros['id'] as $key => $value) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('permisos' . '[' . $key . ']'),
                array($aValores[$value]));
            $oBaseDatos->construir_Tablas(array('botones'));
            $oBaseDatos->construir_Where(array('id=' . $iIdBot));
            $oBaseDatos->consulta();
        }
        $oBaseDatos->termina_transaccion();
        $oVolver = new boton(gettext('sPNVolver'), "atras(-2)", "noafecta");
        return "contenedor_derecha|" . gettext('sPNPermisosAct') . "<br />" . $oVolver->to_Html();
    }

    function procesar_Permisos_Menu($aParametros)
    {

        $oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $iIdMenu = $_SESSION['idMenu'];
        // Arrays de valores
        $iNumPermisos = 21;
        $aValores = array(0 => 'f',
            1 => 't');
        $oBaseDatos->comienza_transaccion();
        foreach ($aParametros['id'] as $key => $value) {
            $oBaseDatos->iniciar_Consulta('UPDATE');
            $oBaseDatos->construir_Set(array('permisos' . '[' . $key . ']'),
                array($aValores[$value]));
            $oBaseDatos->construir_Tablas(array('menu_nuevo'));
            $oBaseDatos->construir_Where(array('id=' . $iIdMenu));
            $oBaseDatos->consulta();
        }
        $oBaseDatos->termina_transaccion();
        $oVolver = new boton(gettext('sPNVolver'), "atras(-2)", "noafecta");
        return "contenedor_derecha|" . gettext('sPNPermisosAct') . "<br />" . $oVolver->to_Html();
    }

    /**
     * @param $aParametros
     * @return string
     */
    function generar_Base_Datos($aParametros)
    {
        $Config=new Config();
        $sTemplate = "qnova_lacandelarianew";
        $css =& new encriptador();
        $clave = 'encriptame';
        $sPassEmp = $css->decrypt(trim($Config->sPassEtc), $clave);
        // Modificacion para seleccion idiomas en index
        $iIdHospital = $_SESSION['pagina'][$aParametros['numeroDeFila']];
        $oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
        $oDbEmpresas = new Manejador_Base_Datos($Config->sLoginEtc, $sPassEmp, $Config->sDbEtc);
        $oVolver = new boton(gettext('sPNVolver'), "atras(-2)", "noafecta");

        $oDb->iniciar_Consulta('SELECT');
        $oDb->construir_Campos(array('nombre', 'password'));
        $oDb->construir_Tablas(array('hospitales'));
        $oDb->construir_Where(array("id=" . $iIdHospital));
        $oDb->consulta();
        if ($aIterador = $oDb->coger_Fila()) {
            $sNombreDb = $aIterador[0];
            $sDb = preg_replace(" ", "_", $aIterador[0]);
            $sPassword = $aIterador[1];
            //Comprobamos que no existe ya la base de datos
            $oDbEmpresas->iniciar_Consulta('SELECT');
            $oDbEmpresas->construir_Campos(array('id'));
            $oDbEmpresas->construir_Tablas(array('qnova_bbdd'));
            $oDbEmpresas->construir_Where(array("empresa='" . $sNombreDb . "'"));
            $oDbEmpresas->consulta();
            if ($aIterador = $oDbEmpresas->coger_Fila()) {
                //Ya existia la base de datos
                $sHtml = gettext('sDbExiste');
            } else {
                //Creamos la base de datos y añadimos la entrada a qnovaempresas
                $sSql = "create database qnova_" . strtolower($sDb) . " with owner=qnova template=" . $sTemplate . " encoding='LATIN1'";
                $oDb->consulta($sSql);
                $oDbEmpresas->iniciar_Consulta('SELECT');
                $oDbEmpresas->construir_Campos(array('login_bbdd', 'pass_bbdd'));
                $oDbEmpresas->construir_Tablas(array('qnova_bbdd'));
                $oDbEmpresas->construir_Where(array("id=1"));
                $oDbEmpresas->consulta();
                $aEmpresas = $oDbEmpresas->coger_Fila();

                $oDbEmpresas->iniciar_Consulta('INSERT');
                $oDbEmpresas->pon_Tabla('qnova_bbdd');
                $oDbEmpresas->construir_Campos(array('nombre_bbdd', 'login_bbdd', 'pass_bbdd', 'empresa'));
                $oDbEmpresas->construir_Value(array('qnova_' . strtolower($sDb), $aEmpresas[0], $aEmpresas[1], $sNombreDb));
                $oDbEmpresas->consulta();

                $oDbEmpresas->iniciar_Consulta('INSERT');
                $oDbEmpresas->pon_Tabla('qnova_acl');
                $oDbEmpresas->construir_Campos(array('login_name', 'login_pass'));
                $oDbEmpresas->construir_Value(array($sNombreDb, $sPassword));
                $oDbEmpresas->consulta();

                $sHtml = gettext('sDbCreada');
            }
        } else {
            $sHtml = gettext('sDbErrorHospital');
        }
        return "contenedor|" . $sHtml . "<br />" . $oVolver->to_Html();
    }
}
