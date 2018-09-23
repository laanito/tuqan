<?php
/**
 * Created on 22-dic-2005
 *
* LICENSE see LICENSE.md file
 *
 *
 * @author Luis Alberto Amigo Navarro <u>lamigo@praderas.org</u>
 * @version 1.0b
 */

include_once 'include.php';

$aDivCalidad = array('inicio' => gettext('sInicio'),
    'documentacion' => gettext('sDocumentacion'),
    'proveedores' => gettext('sProveedores'),
    'mejora' => gettext('sMejora'),
    'formacion' => gettext('sFormacion'),
    'equipos' => gettext('sEquipos'),
    'auditorias' => gettext('sAuditoria'),
    'procesos' => gettext('sProcesos'),
    'indicadores' => gettext('sIndicadores'),
    'maspectos' => gettext('sAspectos'),

);
/**
 *     Este menu es para medioambiente solo
 *
 * $aDivCalidad = array('inicio' => gettext('sInicio'),
 * 'documentacion' => gettext('sDocumentacion'),
 * 'mejora' => gettext('sMejora'),
 * 'formacion' => gettext('sFormacion'),
 * 'auditorias' => gettext('sAuditoria'),
 * 'indicadores' => gettext('sIndicadores'),
 * 'maspectos' => gettext('sAspectos'),
 *
 * );
 */

$aDivAmbiente = array('inicio' => gettext('sInicio'),
    'documentacion' => gettext('sDocumentacion'),
    'mejora' => gettext('sMejora'),
    'formacion' => gettext('sFormacion'),
    'auditorias' => gettext('sAuditoria'),
    'aspectos' => gettext('sAspectos'),
    'indicadores' => gettext('sIndicadores'),
    'logout' => gettext('sLogout')
);

$aDivAdministracion = array('aadministracion' => gettext('sAplicacion'),
    'cadministracion' => gettext('sCalidad'),
    'maadministracion' => gettext('sMedio'),
    'logout' => gettext('sLogout')
);

$aDivQnova = array('logout' => gettext('sLogout'));

$iNumQnova = count($aDivQnova);
$iNumCalidad = count($aDivCalidad);
$iNumAmbiente = count($aDivAmbiente);
$iNumAdministracion = count($aDivAdministracion);
