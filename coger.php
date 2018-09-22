<?php
/**
 * Created on 30-jun-2006
 *
 * Last edited on 19-feb-2018
 */

require_once "Upload.php";
require_once "boton.php";
require_once 'HTML/Page.php';
require_once 'estilo.php';
require_once 'constantes.inc.php';
require 'etc/qnova.conf.php';
require_once 'Manejador_Base_Datos.class.php';
require_once 'encriptador.php';

$css =& new encriptador();
$clave = 'encriptame';

/**
 * Funcion para obtener la siguiente revision de un documento.
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

if (!isset($_SESSION)) {
    session_start();
}
$sMemoriaInicial = ini_get('memory_limit');
$sTiempoLimiteInicial = ini_get('max_execution_time');
ini_set('memory_limit', $sMemoriaHtml2Pdf);
ini_set('max_execution_time', $sMaxTiempoHtml2Pdf);

$oEstilo = new Estilo_Pagina($_SESSION['ancho'], $_SESSION['alto'], $_SESSION['navegador']);
$oPagina = new HTML_Page();
$oPagina->addStyleDeclaration($oEstilo, 'text/css');

$oBaseDatos = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
$oBD = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);
$oVolver = new boton(gettext('sPCVolver'), "parent.atras(-3)", "noafecta");
$upload = new HTTP_Upload("es");
$file = $upload->getFiles("userfile");
$sEditar = $_POST['editar'];

//Miramos que el fichero sea valido y exista
if ($file->isValid()) {
    //Obtenemos los datos del tipo de fichero y sus atributos (No el contenido)
    $sNombre = $HTTP_POST_FILES['userfile']['name'];
    $sExtension = $file->getProp('ext');
    $sMime = $file->getProp('type');
    $iSize = $file->getProp('size');
    $iId = $_POST['documento'];
    if (($sMime == "application/octet-stream") && ($sExtension == 'doc')) {
        $sMime = "application/msword";
    }
    if (($sMime == "application/force-download") && ($sExtension == 'pdf')) {
        $sMime = "application/pdf";
    }
    //Comprobacion de que el fichero es del tipo adecuado
    $oBaseDatos->iniciar_Consulta('SELECT');
    $oBaseDatos->construir_Campos(array('id'));
    $oBaseDatos->construir_Tablas(array('tipos_fichero'));
    $oBaseDatos->construir_Where(array('mime=\'' . $sMime . '\''));
    if ($_SESSION['subirfichero'] == 'imagen') {
        $oBaseDatos->pon_Where('id>5');
    } else {
        $oBaseDatos->pon_Where('id<6');
    }

    $oBaseDatos->consulta();
    if ($aIterador = $oBaseDatos->coger_Fila()) {
        //Dependiendo del tipo de fichero preparamos los datos.
        if (($_SESSION['empresa'] == 'ICS') && (($_SESSION['subirfichero'] == iIdPg) || ($_SESSION['subirfichero'] == iIdPolitica))) {
            //Si entramos aqui es por que vamos a insertar o modificar el documento en todas las bases de datos.
            //Primero sacamos el nombre y codigo apra luego poder sacar el id
            if ($sEditar == 'si') {
                $oBaseDatos->iniciar_Consulta('SELECT');
                $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'estado'));
                $oBaseDatos->construir_Tablas(array('documentos'));
                $oBaseDatos->construir_Where(array('id=' . $iId));
                $oBaseDatos->consulta();
                $aDatosDoc = $oBaseDatos->coger_Fila();
            }
            $oBaseDatos->comienza_transaccion();
            $sPassEmp = $css->decrypt($sPassEtc, $clave);
            $oDb = new Manejador_Base_Datos($sLoginEtc, $sPassEmp, $sDbEtc);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('login_bbdd', 'pass_bbdd', 'nombre_bbdd'));
            $oDb->construir_Tablas(array('qnova_bbdd'));
            //$oDb->construir_Order(array('id desc'));
            $oDb->consulta();
            while ($aIteradorInterno = $oDb->coger_Fila()) {
                //Cada fila es una base de datos distinta donde debemos actualizar.
                $sPassEmp = $css->decrypt($aIteradorInterno[1], $clave);
                $oDbInterno = new Manejador_Base_Datos($aIteradorInterno[0], $sPassEmp, $aIteradorInterno[2]);
                $oDbInterno->comienza_transaccion();
                //Obtenemos el id del documento
                if ($sEditar == 'si') {
                    $oDbInterno->iniciar_Consulta('SELECT');
                    $oDbInterno->construir_Campos(array('id'));
                    $oDbInterno->construir_Tablas(array('documentos'));
                    $oDbInterno->construir_Where(array('codigo=\'' . $aDatosDoc[0] . '\' AND nombre=\'' . $aDatosDoc[1] . '\' AND estado=' . $aDatosDoc[2]));
                    $oDbInterno->consulta();
                    $aIdDoc = $oDbInterno->coger_Fila();
                    $iId = $aIdDoc[0];
                    //Si debemos editar borramos primero el lob y la entrada en contenido binario
                    $oDbInterno->iniciar_Consulta('SELECT');
                    $oDbInterno->construir_Campos(array('archivo_oid'));
                    $oDbInterno->construir_Tablas(array('contenido_binario'));
                    $oDbInterno->construir_Where(array('id=' . $iId));

                    $oDbInterno->consulta();

                    $aOid = $oDbInterno->coger_Fila();
                    $iOid = $aOid[0];
                    $sSql = "DELETE FROM pg_largeobject WHERE loid=" . $iOid;
                    $oDbInterno->consulta($sSql);
                    $sSql2 = "DELETE FROM contenido_binario WHERE id=" . $iId;
                    $oDbInterno->consulta($sSql2);
                    $sTabla = "contenido_binario";
                } else {
                    $_SESSION['area'] = 0;
                    $iTipo = $_SESSION['subirfichero'];
                    switch ($_SESSION['subirfichero']) {
                        case iIdPg:
                            {
                                $_SESSION['codigo'] = $_POST['codigodoc'];
                                $_SESSION['nombre'] = $_POST['nombredoc'];
                                $_SESSION['area'] = $_POST['areadoc'];
                                break;
                            }
                        case iIdPolitica:
                            {
                                $_SESSION['codigo'] = "POL-INT";
                                $_SESSION['nombre'] = "Politica Integral";
                                break;
                            }
                    }
                    if ($iId > 0) {

                        $oDbInterno->iniciar_Consulta('SELECT');
                        $oDbInterno->construir_Campos(array('nombre', 'codigo', 'area', 'tipo_documento', 'revision'));
                        $oDbInterno->construir_Tablas(array('documentos'));
                        $oDbInterno->construir_Where(array('id=' . $iId));

                        $oDbInterno->consulta();

                        $aRevision = $oDbInterno->coger_Fila();

                        $sCodigo = $aRevision[1];
                        $sNombre = $aRevision[0];
                        $sArea = $aRevision[2];
                        $iTipo = $aRevision[3];
                        $sSiguienteRev = siguiente_Version($aRevision[4]);
                    } else {
                        $sSiguienteRev = '1.0.0';
                    }
                }
                $iOid = $oDbInterno->crear_LOB();
                if ($sEditar != 'si') {
                    $oBD->iniciar_Consulta('SELECT');
                    $oBD->construir_Campos(array('perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico', 'perfil_tareas'));
                    $oBD->construir_Tablas(array('tipo_documento'));
                    $oBD->construir_Where(array('id=' . $_SESSION['subirfichero']));
                    $oBD->consulta();
                    $aPermisos = $oBD->coger_Fila();
                    $oDbInterno->iniciar_Consulta('INSERT');
                    $oDbInterno->construir_Campos(array('codigo', 'nombre', 'activo', 'estado', 'revision', 'tipo_documento', 'area', 'perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico', 'perfil_tareas'));
                    $oDbInterno->construir_ValueSinSlash(array($_SESSION['codigo'], $_SESSION['nombre'], 't', '2', $sSiguienteRev, $_SESSION['subirfichero'], $_SESSION['area'], $aPermisos[0], $aPermisos[1], $aPermisos[2], $aPermisos[3], $aPermisos[4], $aPermisos[5], $aPermisos[6]));
                    $oDbInterno->construir_Tablas(array('documentos'));
                    $oDbInterno->consulta();
                    //Sacamos el id
                    $oDbInterno->iniciar_Consulta('SELECT');
                    $oDbInterno->construir_Campos(array('last_value'));
                    $oDbInterno->construir_Tablas(array('documentos_id_seq'));
                    $oDbInterno->consulta();
                    $aRevision = $oDbInterno->coger_Fila();
                    $iId = $aRevision[0];
                    $sTabla = "contenido_binario";

                    $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                    //Ahora debemos introducir los datos en la tabla correspondiente, lo haremos de forma escalonada para no
                    //agotar la memoria
                    //Creo la entrada en la tabla
                    $oDbInterno->iniciar_Consulta('INSERT');
                    $oDbInterno->construir_Campos(array('id', 'tipo_fichero', 'size', 'archivo_oid'));
                    $oDbInterno->construir_ValueSinSlash(array($iId, $aIterador[0], $iSize, $iOid));
                    $oDbInterno->construir_Tablas(array($sTabla));
                    $oDbInterno->consulta();
                } else {
                    $sTabla = "contenido_binario";
                    $oDbInterno->iniciar_Consulta('SELECT');
                    $oDbInterno->construir_Campos(array('id', 'tipo_fichero', 'size', 'archivo_oid'));
                    $oDbInterno->construir_ValueSinSlash(array($iId, $aIterador[0], $iSize, $iOid));
                    $oDbInterno->construir_Tablas(array($sTabla));
                    $oDbInterno->consulta();
                    $oPagina->addBodyContent("<b>Archivo Editado</b><br /><br />");
                }
                $iBlob = $oDbInterno->abrir_LOB($iOid, "w");
                # Contenido del archivo
                $rFp = fopen($file->getProp('tmp_name'), "rb");
                while ($sContenido = fread($rFp, iSizeUpload)) {
                    # Escribe el contenido del archivo
                    $oDbInterno->escribir_LOB($iBlob, $sContenido);
                }
                # Cierra el objeto
                $oDbInterno->cerrar_LOB($iBlob);
                $oDbInterno->termina_transaccion();
            }
            unset ($_SESSION['area']);
            unset ($_SESSION['codigo']);
            unset ($_SESSION['nombre']);
            $oBaseDatos->termina_transaccion();
        } /*******************************************************************************************/
        else if ($_SESSION['subirfichero'] == 'imagen') {
            $oBD->comienza_transaccion();

            //Extension de fichero de la tabla tipos_fichero
            $oBD->iniciar_Consulta('SELECT');
            $oBD->construir_Campos(array('id', 'extension'));
            $oBD->construir_Tablas(array('tipos_fichero'));
            $oBD->construir_Where(array('extension=\'' . $sExtension . '\''));
            $oBD->consulta();
            $aRevision = $oBD->coger_Fila();
            $iExtension = $aRevision['id'];

            //Tamaño
            $iTipo = $aRevision[0];
            $iIdProceso = $_POST['documento'];

            $iOid = $oBD->crear_LOB();
            //Creamos registro de la tabla flujogramas
            $oBD->iniciar_Consulta('INSERT');
            $oBD->construir_Campos(array('tipo_fichero', 'proceso', 'size', 'archivo_oid'));
            $oBD->construir_ValueSinSlash(array($iTipo, $iIdProceso, $iSize, $iOid));
            $oBD->construir_Tablas(array('flujogramas'));
            $oBD->consulta();
            $oPagina->addBodyContent("<b>Flujograma Subido</b><br /><br />");

            // Contenido del archivo
            $iBlob = $oBD->abrir_LOB($iOid, "w");
            $rFp = fopen($file->getProp('tmp_name'), "rb");
            while ($sContenido = fread($rFp, iSizeUpload)) {
                // Escribe el contenido del archivo
                $oBD->escribir_LOB($iBlob, $sContenido);
            }

            // Cierra el objeto
            $oBD->cerrar_LOB($iBlob);
            $oBD->termina_transaccion();
        } /*******************************************************************************************/
        else {
            $oBaseDatos->comienza_transaccion();
            if ($_SESSION['subirfichero'] != iIdAdjunto) {
                if ($sEditar == 'si') {
                    //Si debemos editar borramos primero el lob y la entrada en contenido binario
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('archivo_oid'));
                    $oBaseDatos->construir_Tablas(array('contenido_binario'));
                    $oBaseDatos->construir_Where(array('id=' . $iId));

                    $oBaseDatos->consulta();

                    $aOid = $oBaseDatos->coger_Fila();
                    $iOid = $aOid[0];
                    $sSql = "DELETE FROM pg_largeobject "
                        . "WHERE loid=" . $iOid;
                    $oBaseDatos->consulta($sSql);
                    $sSql = "DELETE FROM contenido_binario "
                        . "WHERE id=" . $iId;
                    $oBaseDatos->consulta($sSql);
                    $sTabla = "contenido_binario";
                } else {
                    $sArea = 0;
                    $iTipo = $_SESSION['subirfichero'];
                    switch ($_SESSION['subirfichero']) {
                        case iIdPe:
                        case iIdPg:
                        case iIdArchivoProc:
                        case iIdExterno:
                        case iIdFichaMa:
                        case iIdNormativa:
                        case iIdPlanAmb:
                        case iIdAai:
                            {
                                $sCodigo = $_POST['codigodoc'];
                                $sNombre = $_POST['nombredoc'];
                                $sArea = $_POST['areadoc'];
                                break;
                            }
                        case iIdManual:
                            {
                                $sCodigo = "MAN";
                                $sNombre = "Manual";
                                break;
                            }
                        case iIdPolitica:
                            {
                                $sCodigo = "POL-INT";
                                $sNombre = "Politica Integral";
                                break;
                            }
                        case iIdObjetivos:
                            {
                                $sCodigo = "OBJ";
                                $sNombre = "OBJETIVOS";
                                break;
                            }
                    }
                    if ($iId > 0) {

                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('nombre', 'codigo', 'area', 'tipo_documento', 'revision'));
                        $oBaseDatos->construir_Tablas(array('documentos'));
                        $oBaseDatos->construir_Where(array('id=' . $iId));

                        $oBaseDatos->consulta();

                        $aRevision = $oBaseDatos->coger_Fila();

                        $sCodigo = $aRevision[1];
                        $sNombre = $aRevision[0];
                        $sArea = $aRevision[2];
                        $iTipo = $aRevision[3];
                        $sSiguienteRev = siguiente_Version($aRevision[4]);
                    } else {
                        $sSiguienteRev = '1.0.0';
                    }
                }
                $iOid = $oBaseDatos->crear_LOB();
                if ($sEditar != 'si') {
                    $oBD->iniciar_Consulta('SELECT');
                    $oBD->construir_Campos(array('perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico', 'perfil_tareas'));
                    $oBD->construir_Tablas(array('tipo_documento'));
                    $oBD->construir_Where(array('id=' . $_SESSION['subirfichero']));
                    $oBD->consulta();
                    $aPermisos = $oBD->coger_Fila();

                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('codigo', 'nombre', 'activo', 'estado', 'revision', 'tipo_documento', 'area', 'perfil_ver', 'perfil_nueva', 'perfil_modificar', 'perfil_revisar', 'perfil_aprobar', 'perfil_historico', 'perfil_tareas'));
                    $oBaseDatos->construir_ValueSinSlash(array($sCodigo, $sNombre, 't', '2', $sSiguienteRev, $iTipo, $sArea, $aPermisos[0], $aPermisos[1], $aPermisos[2], $aPermisos[3], $aPermisos[4], $aPermisos[5], $aPermisos[6]));
                    $oBaseDatos->construir_Tablas(array('documentos'));
                    $oBaseDatos->consulta();
                    //Sacamos el id
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('documentos_id_seq'));
                    $oBaseDatos->consulta();
                    $aRevision = $oBaseDatos->coger_Fila();
                    $iId = $aRevision[0];
                    $sTabla = "contenido_binario";
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('id', 'tipo_fichero', 'size', 'archivo_oid'));
                    $oBaseDatos->construir_ValueSinSlash(array($iId, $aIterador[0], $iSize, $iOid));
                    $oBaseDatos->construir_Tablas(array($sTabla));
                    $oBaseDatos->consulta();

                    /**
                     *
                     *   Si le hemos dicho que lo ponga a vigor directamente (para cuando suben un archivo para
                     *      legislacion aplicable) lo ponemos a vigor en el momento
                     */
                    if ($_POST['vigor'] == 1) {
                        $oBaseDatos->iniciar_Consulta('UPDATE');
                        $oBaseDatos->construir_Set(array('estado', 'revisado_por', 'aprobado_por', 'fecha_revision', 'fecha_aprobacion'),
                            array(iVigor, $_SESSION['userid'], $_SESSION['userid'], 'now()', 'now()'));
                        $oBaseDatos->construir_Tablas(array('documentos'));
                        $oBaseDatos->construir_Where(array('id=' . $iId));
                        $oBaseDatos->consulta();
                        $oVolverForm = new boton(gettext('sPCVolverForm'), "parent.sndReq('inicio:general:comun:selecciona:cerrar','',1,'" . $iId . separador . $_SESSION['campoform'] . separador . $sCodigo . " " . $sNombre . "')", "noafecta");
                        $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                        $oPagina->addBodyContent($oVolverForm->to_Html());
                    } else {
                        $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                    }
                    //Ahora debemos introducir los datos en la tabla correspondiente, lo haremos de forma escalonada para no
                    //agotar la memoriaç
                } else {
                    $sTabla = "contenido_binario";
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('id', 'tipo_fichero', 'size', 'archivo_oid'));
                    $oBaseDatos->construir_ValueSinSlash(array($iId, $aIterador[0], $iSize, $iOid));
                    $oBaseDatos->construir_Tablas(array($sTabla));
                    $oBaseDatos->consulta();
                    $oPagina->addBodyContent("<b>Archivo Editado</b><br /><br />");
                }

                //Creo la entrada en la tabla


                $iBlob = $oBaseDatos->abrir_LOB($iOid, "w");
                # Contenido del archivo
                $rFp = fopen($file->getProp('tmp_name'), "rb");
                while ($sContenido = fread($rFp, iSizeUpload)) {
                    # Escribe el contenido del archivo
                    $oBaseDatos->escribir_LOB($iBlob, $sContenido);
                }
                # Cierra el objeto
                $oBaseDatos->cerrar_LOB($iBlob);

                $oBaseDatos->termina_transaccion();
            } else {
                if (isset($_SESSION['auditor'])) {
                    $oBaseDatos->comienza_transaccion();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('documento'));
                    $oBaseDatos->construir_Tablas(array('auditores'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['auditor']));
                    $oBaseDatos->consulta();
                    $aContenidoAdjunto = $oBaseDatos->coger_Fila();
                    if (strlen($aContenidoAdjunto[0]) > 0) {
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('archivo_oid'));
                        $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                        $oBaseDatos->construir_Where(array('id=' . $aContenidoAdjunto[0]));
                        $oBaseDatos->consulta();
                        //Eliminamos el lob asociado
                        $aOid = $oBaseDatos->coger_Fila();
                        $iOid = $aOid[0];
                        $sSql = "DELETE FROM pg_largeobject "
                            . "WHERE loid=" . $iOid;
                        $oBaseDatos->consulta($sSql);
                        $sSql = "DELETE FROM contenido_adjunto "
                            . "WHERE id=" . $aContenidoAdjunto[0];
                        $oBaseDatos->consulta($sSql);
                        $sTabla = "contenido_binario";
                    }
                    $iOid = $oBaseDatos->crear_LOB();
                    //Creo la entrada en la tabla
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('size', 'archivo_oid', 'tipo_fichero'));
                    $oBaseDatos->construir_ValueSinSlash(array($iSize, $iOid, $aIterador[0]));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto_id_seq'));
                    $oBaseDatos->consulta();
                    $aRevision = $oBaseDatos->coger_Fila();
                    $iIdAdjunto = $aRevision[0];
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_Set(array('documento'),
                        array($iIdAdjunto));
                    $oBaseDatos->construir_Tablas(array('auditores'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['auditor']));
                    $oBaseDatos->consulta();

                    $iBlob = $oBaseDatos->abrir_LOB($iOid, "w");

                    # Contenido del archivo
                    $rFp = fopen($file->getProp('tmp_name'), "rb");
                    while ($sContenido = fread($rFp, iSizeUpload)) {
                        # Escribe el contenido del archivo
                        $oBaseDatos->escribir_LOB($iBlob, $sContenido);
                    }
                    # Cierra el objeto
                    $oBaseDatos->cerrar_LOB($iBlob);

                    $oBaseDatos->termina_transaccion();
                    unset($_SESSION['auditor']);
                    $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                } else if (isset($_SESSION['cursofirmas'])) {
                    $oBaseDatos->comienza_transaccion();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('hoja_firmas'));
                    $oBaseDatos->construir_Tablas(array('cursos'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['cursofirmas']));
                    $oBaseDatos->consulta();
                    $aContenidoAdjunto = $oBaseDatos->coger_Fila();
                    if (strlen($aContenidoAdjunto[0]) > 0) {
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('archivo_oid'));
                        $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                        $oBaseDatos->construir_Where(array('id=' . $aContenidoAdjunto[0]));
                        $oBaseDatos->consulta();
                        //Eliminamos el lob asociado
                        $aOid = $oBaseDatos->coger_Fila();
                        $iOid = $aOid[0];
                        $sSql = "DELETE FROM pg_largeobject "
                            . "WHERE loid=" . $iOid;
                        $oBaseDatos->consulta($sSql);
                        $sSql = "DELETE FROM contenido_adjunto "
                            . "WHERE id=" . $aContenidoAdjunto[0];
                        $oBaseDatos->consulta($sSql);
                        $sTabla = "contenido_binario";
                    }
                    $iOid = $oBaseDatos->crear_LOB();
                    //Creo la entrada en la tabla
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('size', 'archivo_oid', 'tipo_fichero'));
                    $oBaseDatos->construir_ValueSinSlash(array($iSize, $iOid, $aIterador[0]));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto_id_seq'));
                    $oBaseDatos->consulta();
                    $aRevision = $oBaseDatos->coger_Fila();
                    $iIdAdjunto = $aRevision[0];
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_Set(array('hoja_firmas'),
                        array($iIdAdjunto));
                    $oBaseDatos->construir_Tablas(array('cursos'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['cursofirmas']));
                    $oBaseDatos->consulta();

                    $iBlob = $oBaseDatos->abrir_LOB($iOid, "w");

                    // Contenido del archivo
                    $rFp = fopen($file->getProp('tmp_name'), "rb");
                    while ($sContenido = fread($rFp, iSizeUpload)) {
                        // Escribe el contenido del archivo
                        $oBaseDatos->escribir_LOB($iBlob, $sContenido);
                    }
                    // Cierra el objeto
                    $oBaseDatos->cerrar_LOB($iBlob);

                    $oBaseDatos->termina_transaccion();
                    unset($_SESSION['cursofirmas']);
                    $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                } else if (isset($_SESSION['seguimiento'])) {
                    $oBaseDatos->comienza_transaccion();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('documento'));
                    $oBaseDatos->construir_Tablas(array('seguimientos'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['seguimiento']));
                    $oBaseDatos->consulta();
                    $aContenidoAdjunto = $oBaseDatos->coger_Fila();
                    if (strlen($aContenidoAdjunto[0]) > 0) {
                        $oBaseDatos->iniciar_Consulta('SELECT');
                        $oBaseDatos->construir_Campos(array('archivo_oid'));
                        $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                        $oBaseDatos->construir_Where(array('id=' . $aContenidoAdjunto[0]));
                        $oBaseDatos->consulta();
                        //Eliminamos el lob asociado
                        $aOid = $oBaseDatos->coger_Fila();
                        $iOid = $aOid[0];
                        $sSql = "DELETE FROM pg_largeobject "
                            . "WHERE loid=" . $iOid;
                        $oBaseDatos->consulta($sSql);
                        $sSql = "DELETE FROM contenido_adjunto "
                            . "WHERE id=" . $aContenidoAdjunto[0];
                        $oBaseDatos->consulta($sSql);
                        $sTabla = "contenido_binario";
                    }
                    $iOid = $oBaseDatos->crear_LOB();
                    //Creo la entrada en la tabla
                    $oBaseDatos->iniciar_Consulta('INSERT');
                    $oBaseDatos->construir_Campos(array('size', 'archivo_oid', 'tipo_fichero'));
                    $oBaseDatos->construir_ValueSinSlash(array($iSize, $iOid, $aIterador[0]));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto'));
                    $oBaseDatos->consulta();
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('last_value'));
                    $oBaseDatos->construir_Tablas(array('contenido_adjunto_id_seq'));
                    $oBaseDatos->consulta();
                    $aRevision = $oBaseDatos->coger_Fila();
                    $iIdAdjunto = $aRevision[0];
                    $oBaseDatos->iniciar_Consulta('UPDATE');
                    $oBaseDatos->construir_Set(array('documento'),
                        array($iIdAdjunto));
                    $oBaseDatos->construir_Tablas(array('seguimientos'));
                    $oBaseDatos->construir_Where(array('id=' . $_SESSION['seguimiento']));
                    $oBaseDatos->consulta();

                    $iBlob = $oBaseDatos->abrir_LOB($iOid, "w");

                    # Contenido del archivo
                    $rFp = fopen($file->getProp('tmp_name'), "rb");
                    while ($sContenido = fread($rFp, iSizeUpload)) {
                        # Escribe el contenido del archivo
                        $oBaseDatos->escribir_LOB($iBlob, $sContenido);
                    }
                    # Cierra el objeto
                    $oBaseDatos->cerrar_LOB($iBlob);

                    $oBaseDatos->termina_transaccion();
                    unset($_SESSION['cursofirmas']);
                    $oPagina->addBodyContent("<b>".gettext('File Uploaded')."</b><br /><br />");
                }
            }
        }
    } else {
        $oPagina->addBodyContent("<b>" .gettext('sFNoSoportado'). "</b><br /><br />");
    }
    //Fin fichero de tipo valido
} else {
    $oPagina->addBodyContent("<b>" .gettext('sFichError'). "</b><br /><br />");
}
//Fin si es existe el fichero
if (!($_POST['vigor'] == 1)) {
    $oPagina->addBodyContent("<br />" . $oVolver->to_Html());
}
unset ($_SESSION['subirfichero']);
ini_set('memory_limit', $sMemoriaInicial);
ini_set('max_execution_time', $sTiempoLimiteInicial);
ini_set('max_input_time', $sTiempoLimiteInicial);
$oPagina->display();
