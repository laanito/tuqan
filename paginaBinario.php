<?php
/**
* LICENSE see LICENSE.md file
 */

require_once 'HTML/Page.php';
$oPagina = new HTML_Page();

$oPagina->setTitle("Flujograma");
$oPagina->addBodyContent("<img src=\"muestrabinario.php?id=" . $_GET['id'] . "&tipo=imagen\">");
$oPagina->display();
