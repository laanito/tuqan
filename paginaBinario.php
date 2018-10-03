<?php
/**
* LICENSE see LICENSE.md file
 */

use Tuqan\Classes\FakePage;

require_once 'Classes/FakePage.php';
$oPagina = new FakePage();

$oPagina->setTitle("Flujograma");
$oPagina->addBodyContent("<img src=\"muestrabinario.php?id=" . $_GET['id'] . "&tipo=imagen\">");
$oPagina->display();
