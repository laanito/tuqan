<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.style.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function process_style(&$html)
{
    // Remove HTML comment bounds inside the <style>...</style> 
    $html = preg_replace("#(<style[^>]*>)\s*<!--#is", "\\1", $html);
    $html = preg_replace("#-->\s*(</style>)#is", "\\1", $html);

    // Remove CSS comments
    while (preg_match("#(<style[^>]*>.*)/\*.*?\*/.*(</style>)#is", $html)) {
        $html = preg_replace("#(<style[^>]*>.*)/\*.*\*/(.*</style>)#is", "\\1\\2", $html);
    };
}

?>