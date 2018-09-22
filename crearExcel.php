<?php
/*
 * Created on 13-nov-2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

if (!isset($_SESSION)) {
    session_start();
}

require 'Manejador_Base_Datos.class.php';
require 'lib/excel.php';
require 'constantes.inc.php';
require 'etc/qnova.conf.php';

$oDb = new Manejador_Base_Datos($_SESSION['login'], $_SESSION['pass'], $_SESSION['db']);

$aAccion = explode(":", $_GET['accion']);
$sAccion = $aAccion[1];
switch ($sAccion) {
    case 'documentossg':
        {
            $oDb->iniciar_Consulta('SELECT');
            $aCampos = array($sPACodigo, $sPATitulo, $sPARevision, $sPAAlta, $sPATipo);
            $oDb->construir_Campos(array("codigo as \"" . $sPACodigo . "\"", "documentos.nombre as \"" . $sPATitulo . "\"", "revision as \"" . $sPARevision . "\"", "CASE WHEN documentos.activo=true THEN '\"" . $sPAAlta . "\"' ELSE '\"" . $sPABaja . "\"' END AS \"" . $sPAAlta . "\"",
                "tipo_documento_idiomas.valor as \"" . $sPATipo . "\""));
            $oDb->construir_Tablas(array('documentos', 'tipo_documento', 'tipo_documento_idiomas'));
            $oDb->construir_Where(array('tipo_documento.id=documentos.tipo_documento', 'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'mensajes':
        {
            $aCampos = array($sPCTitulo, $sPCEnviado, $sPCHora, $sPCRemitente);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("mensajes.titulo as \"" . $sPCTitulo . "\"", "to_char(mensajes.fecha, 'DD/MM/YYYY') as \"" . $sPCEnviado . "\"",
                "to_char(mensajes.fecha, 'hh24:mi') as \"" . $sPCHora . "\"", "usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . $sPCRemitente . "\""));
            $oDb->construir_Tablas(array('mensajes', 'usuarios'));
            if ($_SESSION['perfil'] != 0) {
                $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ") OR (destinatario=0)", "(mensajes.activo='t')",
                    "usuarios.id=mensajes.origen"
                ));
            } else {
                $oDb->construir_Where(array("usuarios.id=mensajes.origen AND mensajes.activo='t'"));
            }
            $oDb->construir_Order(array('fecha'));
            break;
        }

    case 'historicomensajes':
        {
            $aCampos = array('Titulo', 'Fecha', 'Hora', 'Remitente');
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('titulo', 'to_char(fecha, \'DD/MM/YYYY\') as enviado',
                'to_char(fecha, \'hh24:mi\') as hora', 'usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido as remitente'));
            $oDb->construir_Tablas(array('mensajes', 'usuarios'));
            if ($_SESSION['userid'] != 0) {
                $oDb->construir_Where(array("(destinatario=" . $_SESSION['userid'] . ")", "(mensajes.activo='f')"));
            }
            $oDb->construir_Order(array('fecha'));
            break;
        }

    case 'tareas':
        {
            if ($_SESSION['perfil'] == 0) {
                $aCampos = array($sPCUsuOrigen, $sPCUsuDestino, $sPCAccion, $sPCDocumento);
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array("us1.nombre||' '||us1.primer_apellido||' '||us1.segundo_apellido as \"" . $sPCUsuOrigen . "\"",
                    "us2.nombre||' '||us2.primer_apellido||' '||us2.segundo_apellido as \"" . $sPCUsuDestino . "\"", "tt.nombre as \"" . $sPCAccion . "\"", "doc.codigo||' '||doc.nombre as \"" . $sPCDocumento . "\""));
                $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us1', 'usuarios us2', 'documentos doc'));
                $oDb->construir_Where(array("(tt.id=ta.accion)", "(ta.usuario_origen=us1.id)", "ta.usuario_destino=us2.id", "(ta.activo='t')", "(doc.id=ta.documento)"));
            } else {
                $aCampos = array($sPCUsuOrigen, $sPCAccion, $sPCDocumento);
                $oDb->iniciar_Consulta('SELECT');
                $oDb->construir_Campos(array("us.nombre||' '||us.primer_apellido||' '||us.segundo_apellido as \"" . $sPCUsuOrigen . "\"", "tt.nombre as \"" . $sPCAccion . "\"", "doc.codigo||' '||doc.nombre as \"" . $sPCDocumento . "\""));
                $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us', 'documentos doc'));
                $oDb->construir_Where(array("(tt.id=ta.accion)", "(ta.usuario_origen=us.id)", "(ta.activo='t')", "(doc.id=ta.documento)"));
            }
            if ($_SESSION['perfil'] != 0) {
                $oDb->construir_Where(array("(ta.usuario_destino=" . $_SESSION['userid'] . ")"));
            }
            $oDb->construir_Order(array('accion'));
            break;
        }

    case 'manual':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdManual, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdManual, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;

        }

    case 'objetivos':
        {
            $aCampos = array($sPANombre, $sPABorrador, $sPARevisado, $sPAVigor, $sPAEstado, $sPARevisadoPor, $sPAFechaRevision,
                $sPAAprobadoPor, $sPAFechaAprov, $sPAVersion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("og.nombre as \"" . $sPANombre . "\"",
                "case when og.estado=2 then '\"" . $sPABorrador . "\"' when og.estado=4 then '\"" . $sPARevisado . "\"' else '\"" . $sPAVigor . "\"' end as \"" . $sPAEstado . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . $sPARevisadoPor . "\"",
                "og.fecha_revision::date as \"" . $sPAFechaRevision . "\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPAAprobadoPor . "\"",
                "og.fecha_aprobacion::date as \"" . $sPAFechaAprov . "\"", "og.version as \"$sPAVersion\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join objetivos_globales og1 on u1.id=og1.revisadopor',
                'usuarios u2 right outer join objetivos_globales og on u2.id=og.aprobadopor'));
            $oDb->construir_Where(array('og.activo=\'t\'', 'og1.id=og.id'));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'meta':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['objetivosglobales'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCPlanAccion, $sPCFechaConsecucion, $sPCResponsable, $sPCRecursos);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("plan_accion as \"" . $sPCPlanAccion . "\"", "fecha_consecucion as \"" . $sPCFechaConsecucion . "\"", "responsable as \"" . $sPCResponsable . "\"", "recursos as \"" . $sPCRecursos . "\""
            ));
            $oDb->construir_Tablas(array('metas_objetivos'));
            $oDb->construir_Where(array('objetivo_id=' . $_SESSION['objetivosglobales'], 'metas_objetivos.activo=\'t\''));
            break;
        }

    case 'seguimiento':
        {

            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['objetivo'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCFecha, $sFCObservaciones);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("fecha as \"" . $sPCFecha . "\"", "observaciones as \"" . $sFCObservaciones . "\"", 'objetivos'));
            $oDb->construir_Tablas(array('seguimientos'));
            $oDb->construir_Where(array('objetivos=' . $_SESSION['objetivo']));
            $oDb->construir_Order(array('fecha'));
            break;
        }

    case 'pg':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor,
                $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPg, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdPg, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'archivoproceso':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor,
                $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdArchivoProc, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdArchivoProc, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }
    case 'pe':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('doc1.id', "doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPe, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdPe, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'docvigor':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCTipo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "tipo_documento_idiomas.valor as \"" . $sPCTipo . "\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'tipo_documento', 'tipo_documento_idiomas'));

            $oDb->construir_Where(array('doc1.estado=' . iVigor, 'doc1.activo=true',
                'doc1.tipo_documento<>' . iIdPolitica, 'doc1.tipo_documento<>' . iIdManual,
                'doc2.estado=' . iVigor, 'doc2.activo=true',
                'doc2.tipo_documento<>' . iIdPolitica, 'doc2.tipo_documento<>' . iIdManual,
                'doc1.id=doc2.id', 'tipo_documento.id=doc1.tipo_documento', 'tipo_documento.id=doc2.tipo_documento',
                'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'docborrador':
        {

            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor, $sPAFechaAprov, $sPCTipo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "tipo_documento_idiomas.valor as \"" . $sPCTipo . "\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'tipo_documento', 'tipo_documento_idiomas'));

            $oDb->construir_Where(array('doc1.estado=' . iBorrador, 'doc1.activo=true',
                'doc1.tipo_documento<>' . iIdPolitica, 'doc1.tipo_documento<>' . iIdManual,
                'doc2.estado=' . iBorrador, 'doc2.activo=true',
                'doc2.tipo_documento<>' . iIdPolitica, 'doc2.tipo_documento<>' . iIdManual,
                'doc1.id=doc2.id', 'tipo_documento.id=doc1.tipo_documento', 'tipo_documento.id=doc2.tipo_documento',
                'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'aai':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor,
                $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdAai, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdAai, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'planamb':
        {

            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor,
                $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdPlanAmb, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdPlanAmb, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'frl':
        {
            $aBuscar = array('nombres' => array($sPLCodigo, $sPLNombre),
                'campos' => array('doc1.codigo', 'doc1.nombre'));
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor,
                $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('doc1.id', "doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdFichaMa, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdFichaMa, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'legislacion':
        {
            $aCampos = array($sPMTitulo, $sPMAreaIncidencia, $sPMAmbitoApli, $sPMCumple, $sPMNoCumple, $sPMNuncaComprobado,
                $sPMVerifica);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("legislacion_aplicable.nombre as \"" . $sPMTitulo . "\"",
                "tipo_area_aplicacion.nombre as \"" . $sPMAreaIncidencia . "\"", "tipo_ambito_aplicacion_idiomas.valor as \"" . $sPMAmbitoApli . "\"",
                "case when legislacion_aplicable.verifica='t' then '\"" . $sPMCumple . "\"' when legislacion_aplicable.verifica='f' then '\"" . $sPMNoCumple . "\"' ELSE '\"" . $sPMNuncaComprobado . "\"' END as \"" . $sPMVerifica . "\""));
            $oDb->construir_Tablas(array('legislacion_aplicable', 'tipo_area_aplicacion', 'tipo_ambito_aplicacion', 'tipo_ambito_aplicacion_idiomas'));
            $oDb->construir_Where(array('legislacion_aplicable.tipo_area=tipo_area_aplicacion.id',
                'legislacion_aplicable.tipo_ambito=tipo_ambito_aplicacion.id',
                'legislacion_aplicable.activo=\'t\'',
                'tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb', 'tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));

            break;
        }

    case 'normativa':
        {
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor,
                $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('doc1.id', "doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdNormativa, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdNormativa, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'registros':
        {
            $aCampos = array($sPCNombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPCNombre . "\""));
            $oDb->construir_Tablas(array('registros'));

            break;
        }

    case 'listadoregistros':
        {
            if ($_SESSION['directo'] == 1) {
                if ($aParametros['fila'] == "1") {
                    $_SESSION['tiporegistro'] = 'ficha_personal';
                } else if ($aParametros['fila'] == "2") {
                    $_SESSION['tiporegistro'] = 'requisitos_puesto';
                }
            } else if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                if ($_SESSION['pagina'][$aParametros['fila']] == "1") {
                    $_SESSION['tiporegistro'] = 'ficha_personal';
                } else if ($_SESSION['pagina'][$aParametros['fila']] == "2") {
                    $_SESSION['tiporegistro'] = 'requisitos_puesto';
                }
            }
            $sTabla = $_SESSION['tiporegistro'];
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCFecha, $sPCRevision);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("codigo as \"" . $sPCCodigo . "\"", "nombre as \"" . $sPCNombre . "\"", "to_char(fecha,'dd/mm/yyyy') AS \"" . $sPCFecha . "\"", "revision as \"" . $sPCRevision . "\""));
            $oDb->construir_Tablas(array($_SESSION['tiporegistro']));
            $oDb->construir_Where(array($sTabla . '.activo=true'));
            break;
        }

    case 'docformatos':
        {

            $aCampos = array($sPCCodigo, $sPCNombre, $sPCRevision, $sPCRevisadoPor, $sPAFechaRevision, $sPCAprobadoPor,
                $sPAFechaAprov, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("doc1.codigo as \"" . $sPCCodigo . "\"", "doc1.nombre as \"" . $sPCNombre . "\"", "doc1.revision as \"" . $sPCRevision . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"$sPCRevisadoPor\"", "to_char(doc1.fecha_revision, 'DD/MM/YYYY') as \"$sPAFechaRevision\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"", "to_char(doc1.fecha_aprobacion, 'DD/MM/YYYY') as \"$sPAFechaAprov\"",
                "estados_documento.nombre as \"$sPCEstado\""
            ));
            $oDb->construir_Tablas(array('usuarios u1 right outer join documentos doc1 on u1.id=doc1.revisado_por',
                'usuarios u2 right outer join documentos doc2 on u2.id=doc2.aprobado_por',
                'estados_documento'));

            $oDb->construir_Where(array('doc1.tipo_documento=' . iIdExterno, 'doc1.estado<>' . iHistorico, 'doc1.activo=true',
                'doc2.tipo_documento=' . iIdExterno, 'doc2.estado<>' . iHistorico, 'doc2.activo=true',
                'doc1.id=doc2.id', 'doc1.estado=estados_documento.id', 'doc2.estado=estados_documento.id'));
            $oDb->construir_Order(array('codigo'));
            break;
        }

    case 'listado':
        {

            $aCampos = array($sPCNinguno, $sPCCliente, $sPCFecha, $sPCTipo, $sPCCerrada, $sPCAbierta, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("case when acciones_mejora.cliente is null then '" . $sPCNinguno . "' else " .
                "(select clientes.nombre from clientes where id=acciones_mejora.cliente) end as \"" . $sPCCliente . "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . $sPCFecha . "\"",
                "tipo_acciones_idiomas.valor as \"" . $sPCTipo . "\"", "case when cerrada then '" . $sPCCerrada . "' else '" . $sPCAbierta . "' end as \"" . $sPCEstado . "\""));
            $oDb->construir_Tablas(array('acciones_mejora', 'tipo_acciones', 'tipo_acciones_idiomas'));
            $oDb->construir_Where(array('acciones_mejora.tipo=tipo_acciones.id',
                'tipo_acciones.id=tipo_acciones_idiomas.mejora', 'tipo_acciones_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
            break;
        }

    case 'reqpuesto':
        {
            if ($_SESSION['directo'] == 1) {
                if ($aParametros['fila'] == "1") {
                    $_SESSION['tiporegistro'] = 'ficha_personal';
                } else if ($aParametros['fila'] == "2") {
                    $_SESSION['tiporegistro'] = 'requisitos_puesto';
                }
            } else if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                if ($_SESSION['pagina'][$aParametros['fila']] == "1") {
                    $_SESSION['tiporegistro'] = 'ficha_personal';
                } else if ($_SESSION['pagina'][$aParametros['fila']] == "2") {
                    $_SESSION['tiporegistro'] = 'requisitos_puesto';
                }
            }
            $sTabla = $_SESSION['tiporegistro'];
            $aCampos = array($sPCCodigo, $sPCNombre, $sPCFecha, $sPCRevision);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("codigo as \"" . $sPCCodigo . "\"", "nombre as \"" . $sPCNombre . "\"", "to_char(fecha,'dd/mm/yyyy') AS \"" . $sPCFecha . "\"", "revision as \"" . $sPCRevision . "\""));
            $oDb->construir_Tablas(array($_SESSION['tiporegistro']));
            $oDb->construir_Where(array($sTabla . '.activo=true'));
            break;
        }

    case 'cursos':
        {

            $aCampos = array($sPCNombre, $sPCNinguno, $sPCTipo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPCNombre . "\"", "case when tipo is null then '\"" . $sPCNinguno . "\"' else (select nombre from tipos_cursos where tipos_cursos.id=plantilla_curso.tipo) end as \"" . $sPCTipo . "\""));
            $oDb->construir_Tablas(array('plantilla_curso'));
            $oDb->construir_Where(array('plantilla_curso.activo=\'t\''));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'inscripcion':
        {
            $aCampos = array($sPCNombre, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("cursos.nombre as \"" . $sPCNombre . "\"", "tipo_estados_curso.nombre as \"" . $sPCEstado . "\""));
            $oDb->construir_Tablas(array('cursos', 'tipo_estados_curso'));
            $oDb->construir_Where(array('(cursos.estado=tipo_estados_curso.id)', 'cursos.activo=\'t\''));
            break;
        }

    case 'asistentes':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCUsuario, $sPCAlta, $sPCSolicitadaAlta, $sPCBaja, $sPCSolicitadaBaja, $sPCEstado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . $sPCUsuario . "\"",
                "case when alumnos.inscrito=true then (case when alumnos.verificado=true then '\"" . $sPCAlta . "\"' 
                            else '\"" . $sPCSolicitadaAlta . "\"' end) else (case when alumnos.verificado=true then '\"" . $sPCBaja . "\"' else '\"" . $sPCSolicitadaBaja . "\"' end) end as \"" . $sPCEstado . "\""
            ));
            $oDb->construir_Tablas(array('alumnos', 'usuarios'));
            $oDb->construir_Where(array('alumnos.curso=' . $_SESSION['curso'], 'alumnos.usuario=usuarios.id'));

            break;
        }

    case 'planes':
        {

            $aCampos = array($sPCNombre, $sPCVigente);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPCNombre . "\"", "CASE WHEN vigente=true THEN 'Si' ELSE 'No' END AS \"" . $sPCVigente . "\""));
            $oDb->construir_Tablas(array('plan_formacion'));
            $oDb->construir_Where(array('plan_formacion.activo=\'t\''));
            break;
        }


    case 'cursosplan':
        {

            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['planId'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCNombre, $sPCEstado, $sPCPrevisto, $sPCDiaRealiz, $sPCHoraRealiz);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("cursos.nombre as \"" . $sPCNombre . "\"", "tipo_estados_curso.nombre as \"" . $sPCEstado . "\"",
                "to_char(cursos.fecha_prevista, 'DD/MM/YYYY') as \"" . $sPCPrevisto . "\"",
                "to_char(cursos.fecha_realizacion, 'DD/MM/YYYY') as \"" . $sPCDiaRealiz . "\"",
                "to_char(cursos.fecha_realizacion, 'hh24:mi') as \"" . $sPCHoraRealiz . "\""
            ));
            $oDb->construir_Tablas(array('cursos', 'tipo_estados_curso'));
            $oDb->construir_Where(array('cursos.plan=' . $_SESSION['planId']
            , 'cursos.estado=tipo_estados_curso.id',
                'cursos.activo=\'t\''));
            break;
        }

    case 'verProfesores':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['curso'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCUsuario, $sPCProfesor, $sPCInterno, $sPCExterno, $sPCInterno);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("case when interno then (select usuarios.nombre||' '||usuarios.primer_apellido||' '||usuarios.segundo_apellido as \"" . $sPCUsuario . "\" from usuarios where (usuarios.id=usuario_interno)) else empresa end as \"" . $sPCProfesor . "\"",
                "case when interno then '\"" . $sPCInterno . "\"' else '\"" . $sPCExterno . "\"' end as \"" . $sPCInterno . "\""
            ));
            $oDb->construir_Tablas(array('profesores'));
            $oDb->construir_Where(array('curso=' . $_SESSION['curso'], 'profesores.activo=true'));

            break;
        }


    case 'programa':
        {
            $aCampos = array($sPCNombre, $sPCVigente, $sPCRevision);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPCNombre . "\"", "CASE WHEN vigente=true THEN 'Si' ELSE 'No' END AS \"" . $sPCVigente . "\"", "revision as \"" . $sPCRevision . "\""));
            $oDb->construir_Tablas(array('programa_auditoria'));
            $oDb->construir_Where(array('programa_auditoria.activo=\'t\''));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'progauditoria':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                $_SESSION['progauditoria'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCDescripcion, $sPCEstado, $sPCFecha, $sPCRealizacion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("descripcion as \"" . $sPCDescripcion . "\"", "tipo_estado_auditoria.nombre as \"" . $sPCEstado . "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . $sPCFecha . "\"",
                "case when (fecha_realiza=null) then '-' else to_char(fecha_realiza, 'DD/MM/YYYY') end as \"" . $sPCRealizacion . "\""));
            $oDb->construir_Tablas(array('auditorias', 'tipo_estado_auditoria'));
            $oDb->construir_Where(array('auditorias.programa=' . $_SESSION['progauditoria'], 'auditorias.estado=tipo_estado_auditoria.id',
                'auditorias.activo=\'t\''));
            break;
        }

    case 'equipoauditor':
        {

            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                $_SESSION['auditoria'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCAuditor, $sPCInterno);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("case when usuario_interno is null then nombre else (select us.nombre||' '||us.primer_apellido||' '||us.segundo_apellido from usuarios us where (us.id=usuario_interno)) end as \"" . $sPCAuditor . "\"",
                "case when usuario_interno is null then 'Externo' else 'Interno' end as \"" . $sPCInterno . "\""
            ));
            $oDb->construir_Tablas(array('auditores'));
            $oDb->construir_Where(array('auditores.auditoria=' . $_SESSION['auditoria'], 'auditores.activo=\'t\''));
            break;
        }

    case 'horarioauditoria':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['boton'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPNHorainicio, $sPNHorafin, $sPNRequisitos, $sPNArea);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("horainicio as \"" . $sPNHorainicio . "\"", "horafin as \"" . $sPNHorafin . "\"", "requisito as \"" . $sPNRequisitos . "\"", "area as \"" . $sPNArea . "\"", "auditor as \"" . auditor . "\""));
            $oDb->construir_Tablas(array('horario_auditoria'));
            break;
        }


    case 'plan':
        {
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('id'));
            $oDb->construir_Tablas(array('programa_auditoria'));
            $oDb->construir_Where(array('programa_auditoria.vigente=\'t\''));
            $oDb->consulta();
            if ($aIterador = $oDb->coger_Fila()) {
                $_SESSION['progauditoria'] = $aIterador[0];
            }
            $aCampos = array($sPCDescripcion, $sPCEstado, $sPCFecha, $sPCRealizacion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("descripcion as \"" . $sPCDescripcion . "\"", "tipo_estado_auditoria.nombre as \"" . $sPCEstado . "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . $sPCFecha . "\"",
                "case when (fecha_realiza=null) then '-' else to_char(fecha_realiza, 'DD/MM/YYYY') end as \"" . $sPCRealizacion . "\""));
            $oDb->construir_Tablas(array('auditorias', 'tipo_estado_auditoria', 'programa_auditoria'));
            $oDb->construir_Where(array('auditorias.programa=programa_auditoria.id', 'auditorias.estado=tipo_estado_auditoria.id',
                'programa_auditoria.vigente=\'t\'', 'auditorias.activo=\'t\''));
            break;
        }

    case 'indicadores':
        {
            $aCampos = array($sPCNombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPCNombre . "\""));
            $oDb->construir_Tablas(array('indicadores'));
            $oDb->construir_Where(array('indicadores.activo=\'t\''));
            break;
        }

    case 'valoresindicador':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null) && ($aParametros['fila'] != 'undefined')) {
                $_SESSION['indicador'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            //ICS
            $_SESSION['contenido_proceso'] = 0;
            //ICS
            $aCampos = array($sPCValor, $sPCFecha);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("valores.valor as \"" . $sPCValor . "\"", "to_char(valores.fecha, 'DD/MM/YYYY') as \"" . $sPCFecha . "\""));
            $oDb->construir_Tablas(array('valores'));
            $oDb->construir_Where(array('valores.indicador=' . $_SESSION['indicador'], 'valores.proceso=' . $_SESSION['contenido_proceso'],
                'valores.activo=true'
            ));
            $oDb->construir_Order(array('fecha'));
            break;
        }

    case 'indobjetivos':
        {
            $aCampos = array($sPANombre, $sPCRevisadoPor, $sPABorrador, $sPARevisado, $sPAVigor, $sPAEstado, $sPCFecha_revision,
                $sPCAprobadoPor, $sPCFecha_aprovacion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array(
                "og.nombre as \"" . $sPANombre . "\"",
                "u1.nombre||' '||u1.primer_apellido||' '||u1.segundo_apellido as \"" . $sPCRevisadoPor . "\"",
                "case when og.estado=2 then '\"" . $sPABorrador . "\"' when og.estado=3 then '\"" . $sPARevisado . "\"' else '\"" . $sPAVigor . "\"' end as \"" . $sPAEstado . "\"",
                "og.fecha_revision::date as \"" . $sPCFecha_revision . "\"",
                "u2.nombre||' '||u2.primer_apellido||' '||u2.segundo_apellido as \"" . $sPCAprobadoPor . "\"",
                "og.fecha_aprobacion::date as \"" . $sPCFecha_aprovacion . "\""
            ));

            $oDb->construir_Tablas(array('usuarios u1 right outer join objetivos og1 on u1.id=og1.revisadopor',
                'usuarios u2 right outer join objetivos og on u2.id=og.aprobadopor'));
            $oDb->construir_Where(array('og.activo=\'t\'', 'og1.id=og.id'));
            $oDb->construir_Order(array('nombre'));

            break;
        }

    case 'vermetas':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['objetivosindicadores'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPCPlanAccion, $sPCFechaConsecucion . "\"", "responsable as \"" . $sPCResponsable . "\"", "recursos as \"" . $sPCRecursos . "\""
            );
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("plan_accion as \"" . $sPCPlanAccion . "\"", "fecha_consecucion as \"" . $sPCFechaConsecucion . "\"", "responsable as \"" . $sPCResponsable . "\"", "recursos as \"" . $sPCRecursos . "\""
            ));
            $oDb->construir_Tablas(array('metas_indicadores'));
            $oDb->construir_Where(array('objetivo_id=' . $_SESSION['objetivosindicadores'], 'metas_indicadores.activo=\'t\''));
            break;
        }


    case 'revision':
        {
            //Sacamos la formula
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('formula'));
            $oDb->construir_Tablas(array('formula_aspectos'));
            $oDb->construir_Where(array('id=1'));
            $oDb->consulta();
            $aDevolver = $oDb->coger_Fila();
            $sFormulaMatrizAmbientales = $aDevolver[0];
            $aCampos = array($sPMTipo, $sPMAspecto, $sPMImpacto, $sPMArea, $sFMagnitud, $sFGravedad, $sFFrecuencia,
                $sPMNoSignificativo, $sPMSignificativo, $sPMValoracion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_aspectos.nombre as \"" . $sPMTipo . "\"", "aspectos.nombre as \"" . $sPMAspecto . "\"", "tipo_impactos_idiomas.valor as \"" . $sPMImpacto . "\"", "area as \"" . $sPMArea . "\"",
                "tipo_magnitud_idiomas.valor as \"" . $sFMagnitud . "\"", "tipo_gravedad_idiomas.valor as \"" . $sFGravedad . "\"", "tipo_frecuencia_idiomas.valor as \"" . $sFFrecuencia . "\"", 'pp.significancia',
                "case when pp.significancia<15 then '" . $sPMNoSignificativo . "' else '" . $sPMSignificativo . "' end as \"" . $sPMValoracion . "\""
            ));
            $oDb->construir_Tablas(array('aspectos', 'tipo_impactos', 'tipo_aspectos',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_frecuencia, tipo_magnitud, tipo_gravedad ' .
                'where aspectos.frecuencia=tipo_frecuencia.id AND aspectos.magnitud=tipo_magnitud.id AND aspectos.gravedad=tipo_gravedad.id)as pp',
                'tipo_frecuencia', 'tipo_magnitud', 'tipo_gravedad', 'tipo_impactos_idiomas', 'tipo_frecuencia_idiomas', 'tipo_gravedad_idiomas', 'tipo_magnitud_idiomas'));
            $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id',
                'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                'aspectos.tipo_aspecto<>3',
                'aspectos.magnitud=tipo_magnitud.id', 'aspectos.frecuencia=tipo_frecuencia.id',
                'aspectos.gravedad=tipo_gravedad.id',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia', 'tipo_frecuencia_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_gravedad.id=tipo_gravedad_idiomas.gravedad', 'tipo_gravedad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_magnitud.id=tipo_magnitud_idiomas.magnitud', 'tipo_magnitud_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
            $oDb->construir_Order(array('tipo_aspectos.nombre'));
            break;
        }

    case 'aspectoemergencia':
        {
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('formula'));
            $oDb->construir_Tablas(array('formula_aspectos'));
            $oDb->construir_Where(array('id=2'));
            $oDb->consulta();
            $aDevolver = $oDb->coger_Fila();
            $sFormulaMatrizAmbientales = $aDevolver[0];
            $aCampos = array($sPMAspecto, $sPMImpacto, $sPMArea, $sPMNoSignificativo, $sPMRiesgoBajo, $sPMRiesgoMedio, $sPMRiesgoAlto, $sPMValoracion);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("aspectos.nombre as \"" . $sPMAspecto . "\"", "tipo_impactos_idiomas.valor as \"" . $sPMImpacto . "\"", "area as \"" . $sPMArea . "\"",
                "tipo_probabilidad_idiomas.valor as \"probabilidad\"", "tipo_severidad_idiomas.valor as \"severidad\"",
                'pp.significancia',
                "case when pp.significancia<$iValorRiesgoBajo then '" . $sPMNoSignificativo . "' when pp.significancia<$iValorRiesgoMedio and pp.significancia >=$iValorRiesgoBajo then '" . $sPMRiesgoBajo . "' when pp.significancia<$iValorRiesgoAlto and pp.significancia >=$iValorRiesgoMedio then '" . $sPMRiesgoMedio . "' else '" . $sPMRiesgoAlto . "' end as \"" . $sPMValoracion . "\""
            ));
            $oDb->construir_Tablas(array('aspectos', 'tipo_impactos', 'tipo_severidad', 'tipo_probabilidad', 'tipo_aspectos',
                '(select aspectos.id, ' . $sFormulaMatrizAmbientales . ' as significancia from aspectos,tipo_probabilidad,tipo_severidad where tipo_severidad.id=aspectos.severidad AND tipo_probabilidad.id=aspectos.probabilidad)as pp',
                'tipo_impactos_idiomas', 'tipo_severidad_idiomas', 'tipo_probabilidad_idiomas'));
            $oDb->construir_Where(array('aspectos.activo=\'t\'', 'aspectos.tipo_aspecto=tipo_aspectos.id', 'aspectos.impacto=tipo_impactos.id', 'aspectos.id=pp.id',
                'aspectos.tipo_aspecto=3', 'tipo_probabilidad.id=aspectos.probabilidad', 'tipo_severidad.id=aspectos.severidad',
                'tipo_impactos.id=tipo_impactos_idiomas.impactos', 'tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_severidad.id=tipo_severidad_idiomas.severidad', 'tipo_severidad_idiomas.idioma_id=' . $_SESSION['idiomaid'],
                'tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad', 'tipo_probabilidad_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
            $oDb->construir_Order(array('aspectos.nombre'));
            break;
        }


    case 'usuario':
        {
            $aCampos = array($sPAPrimerApe, $sPASegundoApe, $sPANombre, 'ultimo_acceso', 'numero_accesos');
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("primer_apellido as \"" . $sPAPrimerApe . "\"", "segundo_apellido as \"" . $sPASegundoApe . "\"", "nombre as \"" . $sPANombre . "\"",
                'ultimo_acceso', 'numero_accesos'));
            $oDb->construir_Tablas(array('usuarios'));
            $oDb->construir_where(array('id<>0'));
            $oDb->construir_Order(array('primer_apellido'));
            break;
        }

    case 'perfiles':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('perfiles'));
            $oDb->construir_where(array('id<>0', 'perfiles.activo=\'t\''));
            break;
        }

    case 'adminmensajes':
        {
            $aCampos = array($sPATitulo, $sPAEnviado, $sPAHora);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("titulo as \"" . $sPATitulo . "\"", "to_char(fecha, 'DD/MM/YYYY') as \"" . $sPAEnviado . "\"",
                "to_char(fecha, 'hh24:mi') as \"" . $sPAHora . "\""));
            $oDb->construir_Tablas(array('mensajes'));
            $oDb->construir_Where(array('(destinatario=0)', '(mensajes.activo=\'t\')'));
            $oDb->construir_Order(array('fecha'));
            break;
        }

    case 'admintareas':
        {
            $aCampos = array($sPAOrigen, $sPADestino, $sPAAccion, $sPADocumento, $sPABaja, $sPAAlta);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("us1.nombre as \"" . $sPAOrigen . "\"", "us2.nombre as \"" . $sPADestino . "\"",
                "tt.nombre as \"" . $sPAAccion . "\"", "doc.codigo||' '||doc.nombre as \"" . $sPADocumento . "\"",
                "CASE WHEN ta.activo=true THEN '\"" . $sPAAlta . "\"' ELSE '\"" . $sPABaja . "\"' END as \"" . $sPAAlta . "\""));
            $oDb->construir_Tablas(array('tareas ta', 'tipo_tarea tt', 'usuarios us1', 'usuarios us2', 'documentos doc'));
            $oDb->construir_Where(array("(ta.usuario_origen=us1.id)", "ta.usuario_destino=us2.id",
                "(tt.id=ta.accion)", "ta.documento=doc.id"));
            $oDb->construir_Order(array('us1.nombre'));
            break;
        }

    case 'adminmenus':
        {
            $aCampos = array($sPAValor, $sPAAccion, $sPAPadre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("menu_idiomas_nuevo.valor as \"" . $sPAValor . "\"", "accion", "padre as \"" . $sPAPadre . "\""));
            $oDb->construir_Tablas(array("menu_nuevo left outer join (menu_idiomas_nuevo inner join idiomas on menu_idiomas_nuevo.idioma_id=idiomas.id and idiomas.nombre='" . $_SESSION['idioma'] . "') on menu_nuevo.id=menu_idiomas_nuevo.menu"));
            break;
        }


    case 'hospitales':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('nombre as "' . $sPANombre . '"'));
            $oDb->construir_Tablas(array('hospitales'));
            $oDb->construir_where(array('id<>0', 'hospitales.activo=\'t\''));
            break;
        }

    case 'adminregistros':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                if ($_SESSION['pagina'][$aParametros['fila']] == "1") {
                    $_SESSION['tiporegistro'] = 'ficha_personal';
                } else {
                    $_SESSION['tiporegistro'] = 'requisitos_puesto';
                }

            }
            $sTabla = $_SESSION['tiporegistro'];
            $aCampos = array($sPCCodigo, $sPANombre, $sPAFecha, $sPARevision, $sPAActivo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("codigo as \"" . $sPCCodigo . "\"", "nombre as \"" . $sPANombre . "\"",
                "to_char(fecha,'dd/mm/yyyy') AS \"" . $sPAFecha . "\"", "revision as \"" . $sPARevision . "\"",
                "case when " . $sTabla . ".activo then 'Si' else 'No' end as \"" . $sPAActivo . "\""));
            $oDb->construir_Tablas(array($_SESSION['tiporegistro']));
            break;
        }

    case 'adminregistros2':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('registros'));
            break;
        }

    case 'adminnormativa':
        {
            $aCampos = array($sPLCodigo, $sPLNombre, $sPARevision, $sPAAlta, $sPABaja, $sPAAlta);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("documentos.codigo as \"" . $sPLCodigo . "\"", "documentos.nombre as \"" . $sPLNombre . "\"", "documentos.revision as \"" . $sPARevision . "\"", "CASE WHEN documentos.activo=true THEN '\"" . $sPAAlta . "\"' ELSE '\"" . $sPABaja . "\"' END AS \"" . $sPAAlta . "\""));
            $oDb->construir_Tablas(array('documentos'));
            $oDb->construir_Where(array('documentos.tipo_documento=' . iIdExterno));
            $oDb->construir_Order(array('documentos.codigo'));
            break;
        }

    case 'adminmejora':
        {
            $aCampos = array($sPANombre, $sPAAlta, $sPABaja, $sPAAlta);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_acciones_idiomas.valor as \"" . $sPANombre . "\"", "CASE WHEN tipo_acciones.activo=true THEN '\"" . $sPAAlta . "\"' ELSE '\"" . $sPABaja . "\"' END AS \"" . $sPAAlta . "\""));
            $oDb->construir_Tablas(array('tipo_acciones left outer join tipo_acciones_idiomas on tipo_acciones.id=tipo_acciones_idiomas.mejora'));
            $oDb->construir_Where(array("tipo_acciones_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_acciones.nombre is NULL"));
            $oDb->construir_Order(array('tipo_acciones_idiomas.valor'));
            break;
        }

    case 'admintipoarea':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_area_aplicacion.nombre as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('tipo_area_aplicacion'));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'admintipoamb':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_ambito_aplicacion_idiomas.valor as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('tipo_ambito_aplicacion left outer join tipo_ambito_aplicacion_idiomas on tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb'));
            $oDb->construir_Where(array('tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid'] . " OR tipo_ambito_aplicacion.nombre is NULL"));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'adminlegaplicable':
        {
            $aCampos = array($sPMTitulo, $sPMAreaIncidencia, $sPMAmbitoApli, $sPMCumple, $sPMNoCumple, $sPMNuncaComprobado, $sPMVerifica);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("legislacion_aplicable.nombre as \"" . $sPMTitulo . "\"",
                "tipo_area_aplicacion.nombre as \"" . $sPMAreaIncidencia . "\"", "tipo_ambito_aplicacion_idiomas.valor as \"" . $sPMAmbitoApli . "\"",
                "case when legislacion_aplicable.verifica='t' then '\"" . $sPMCumple . "\"' when legislacion_aplicable.verifica='f' then '\"" . $sPMNoCumple . "\"' ELSE '\"" . $sPMNuncaComprobado . "\"' END as \"" . $sPMVerifica . "\""));
            $oDb->construir_Tablas(array('legislacion_aplicable', 'tipo_area_aplicacion', 'tipo_ambito_aplicacion', 'tipo_ambito_aplicacion_idiomas'));
            $oDb->construir_Where(array('legislacion_aplicable.tipo_area=tipo_area_aplicacion.id',
                'legislacion_aplicable.tipo_ambito=tipo_ambito_aplicacion.id',
                'legislacion_aplicable.activo=\'t\'',
                'tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb', 'tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid']
            ));
            $oDb->construir_Order(array('legislacion_aplicable.nombre'));
            break;
        }

    case 'preguntasleg':
        {
            if (($aParametros['fila'] != -1) && ($aParametros['fila'] != null)) {
                $_SESSION['admlegislacion'] = $_SESSION['pagina'][$aParametros['fila']];
            }
            $aCampos = array($sPAPregunta, $sPAValorDeseado);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("pregunta as \"" . $sPAPregunta . "\"", "case when valor_deseado then 'Si' else 'No' end as \"" . $sPAValorDeseado . "\""));
            $oDb->construir_Tablas(array('preguntas_legislacion_aplicable'));
            $oDb->construir_Where(array('legislacion_aplicable=' . $_SESSION['admlegislacion'], 'preguntas_legislacion_aplicable.activo=true'));
            $oDb->construir_Order(array('pregunta'));
            break;
        }


    case 'admintipoimpacto':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_impactos_idiomas.valor as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('tipo_impactos left outer join tipo_impactos_idiomas on tipo_impactos.id=tipo_impactos_idiomas.impactos'));
            $oDb->construir_Where(array('tipo_impactos_idiomas.idioma_id=' . $_SESSION['idiomaid'] . " OR tipo_impactos.nombre is NULL", 'tipo_impactos.activo=\'t\''));
            $oDb->construir_Order(array('tipo_impactos_idiomas.valor'));
            break;
        }

    case 'admintipo_cursos':
        {
            $aCampos = array($sPANombre);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("nombre as \"" . $sPANombre . "\""));
            $oDb->construir_Tablas(array('tipos_cursos'));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'admintipo_doc':
        {
            $aCampos = array($sPANombre, $sPATipo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_documento_idiomas.valor as \"" . $sPANombre . "\"", "tipo as \"" . $sPATipo . "\""));
            $oDb->construir_Tablas(array('tipo_documento left outer join tipo_documento_idiomas on tipo_documento.id=tipo_documento_idiomas.tipodoc'));
            $oDb->construir_Where(array("tipo_documento_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_documento.nombre is NULL"));
            $oDb->construir_Order(array('nombre'));
            break;
        }

    case 'magnitud':
        {
            $aCampos = array($sPANombre, $sPAValor);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_magnitud_idiomas.valor as \"" . $sPANombre . "\"", "tipo_magnitud.valor as \"" . $sPAValor . "\""));
            $oDb->construir_Tablas(array('tipo_magnitud left outer join tipo_magnitud_idiomas on tipo_magnitud.id=tipo_magnitud_idiomas.magnitud'));
            $oDb->construir_Where(array("tipo_magnitud_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_magnitud.nombre is NULL"));
            $oDb->construir_Order(array('tipo_magnitud_idiomas.valor'));
            break;
        }

    case 'gravedad':
        {
            $aCampos = array($sPANombre, $sPAValor);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_gravedad_idiomas.valor as \"" . $sPANombre . "\"", "tipo_gravedad.valor as \"" . $sPAValor . "\""));
            $oDb->construir_Tablas(array('tipo_gravedad left outer join tipo_gravedad_idiomas on tipo_gravedad.id=tipo_gravedad_idiomas.gravedad'));
            $oDb->construir_Where(array("tipo_gravedad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_gravedad.nombre is NULL"));
            $oDb->construir_Order(array('tipo_gravedad_idiomas.valor'));
            break;
        }

    case 'frecuencia':
        {
            $aCampos = array($sPANombre, $sPAValor);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_frecuencia_idiomas.valor as \"" . $sPANombre . "\"", "tipo_frecuencia.valor as \"" . $sPAValor . "\""));
            $oDb->construir_Tablas(array('tipo_frecuencia left outer join tipo_frecuencia_idiomas on tipo_frecuencia.id=tipo_frecuencia_idiomas.frecuencia'));
            $oDb->construir_Where(array("tipo_frecuencia_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_frecuencia.nombre is NULL"));
            $oDb->construir_Order(array('tipo_frecuencia_idiomas.valor'));
            break;
        }


    case 'formula':
        {
            $aCampos = array($sMCFormula, 'tipo');
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("formula as $sMCFormula", "case when id=1 then 'Aspecto Normal' else 'Aspecto Emergencia' end as \"tipo\""));
            $oDb->construir_Tablas(array('formula_aspectos'));
            break;
        }

    case 'probabilidad':
        {
            $aCampos = array($sPANombre, $sPAValor);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_probabilidad_idiomas.valor as \"" . $sPANombre . "\"", "tipo_probabilidad.valor as \"" . $sPAValor . "\""));
            $oDb->construir_Tablas(array('tipo_probabilidad left outer join tipo_probabilidad_idiomas on tipo_probabilidad.id=tipo_probabilidad_idiomas.probabilidad'));
            $oDb->construir_Where(array("tipo_probabilidad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_probabilidad.nombre is NULL"));
            $oDb->construir_Order(array('tipo_probabilidad_idiomas.valor'));
            break;
        }

    case 'severidad':
        {
            $aCampos = array($sPANombre, $sPAValor);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("tipo_severidad_idiomas.valor as \"" . $sPANombre . "\"", "tipo_severidad.valor as \"" . $sPAValor . "\""));
            $oDb->construir_Tablas(array('tipo_severidad left outer join tipo_severidad_idiomas on tipo_severidad.id=tipo_severidad_idiomas.severidad'));
            $oDb->construir_Where(array("tipo_severidad_idiomas.idioma_id=" . $_SESSION['idiomaid'] . " OR tipo_severidad.nombre is NULL"));
            $oDb->construir_Order(array('tipo_severidad_idiomas.valor'));
            break;
        }

    case 'ayuda':
        {
            $aCampos = array($sAMenu, $sABoton, $sAIdioma, $sATexto);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array("menu as \"" . $sAMenu . "\"", "boton as \"" . $sABoton . "\"", "idiomas.nombre as \"" . $sAIdioma . "\"", "texto as \"" . $sATexto . "\""));
            $oDb->construir_Tablas(array('division_ayuda join idiomas on division_ayuda.idioma=idiomas.id'));
            $oDb->construir_Order(array('menu'));

            break;
        }

    case 'permisos':
        {
            $aCampos = array($sMCModulo);
            $oDb->iniciar_Consulta('SELECT');
            $oDb->construir_Campos(array('menu_idiomas_nuevo.valor as "' . $sMCModulo . '"'));
            $oDb->construir_Tablas(array('menu_nuevo', 'menu_idiomas_nuevo'));
            $oDb->construir_Where(array("menu_idiomas_nuevo.menu=menu_nuevo.id", "menu_idiomas_nuevo.idioma_id=" . $_SESSION['idiomaid'],
                "menu_nuevo.padre=0"));
            $oDb->construir_Order(array('menu_idiomas_nuevo.valor'));
            break;
        }

    default:
        {
            echo "error";
            die();
            break;
        }
}
$oDb->consulta();

$aResultado = array();

while ($aIterador = $oDb->coger_Fila()) {
    $aElemento = array();
    foreach ($aIterador as $iKey => $sValor) {
        $aElemento[$aCampos[$iKey]] = $sValor;
    }
    $aResultado[] = $aElemento;
}
$sExport_file = "xlsfile:/" . $sPathXls . $sAccion . ".xls";
$rFp = fopen($sExport_file, "wb");
if (!is_resource($rFp)) {
    die("Cannot open $sExport_file");
}
fwrite($rFp, serialize($aResultado));
fclose($rFp);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"" . basename($sExport_file) . "\"");
header("Content-Description: PHP/INTERBASE Generated Data");
readfile($sExport_file);

