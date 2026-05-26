<?php
/**
 * Created on 21-nov-2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 
 * Notas sobre el FCKEditor
 * Se han realizado dos cambios en el codigo fuente del editor para su correcta integracion
 * con la aplicacion qnova:
 * 1- Archivo editor/_source/classes/fckpanel_gecko.js
 *         A�adida la linea "this._IFrame.style.zIndex        = '2' ;" dentro de la funcion
 *         "FCKPanel.prototype.Create = function()" para la correcta visualizacion de los menus
 *         desplegables de opciones del editor (fuente,tama�o,formato...)
 *
 * 2- Archivo editor/_source/internals/fcknamespace.js
 *         Eliminado el if para que no haya problema por cargarlo por segunda y sucesivas veces.
 *
 * Estas modificaciones han sido realizadas tambien en los archivos compactados.
 */
?>
