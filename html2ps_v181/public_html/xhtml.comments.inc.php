<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.comments.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function remove_comments(&$html)
{
    $html = preg_replace("#<!--.*?-->#is", "", $html);
    $html = preg_replace("#<!.*?>#is", "", $html);
}

?>