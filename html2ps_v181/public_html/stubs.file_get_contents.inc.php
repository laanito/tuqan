<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/stubs.file_get_contents.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function file_get_contents($file)
{
    $lines = file($file);
    if ($lines) {
        return implode('', $lines);
    } else {
        return "";
    };
}

?>