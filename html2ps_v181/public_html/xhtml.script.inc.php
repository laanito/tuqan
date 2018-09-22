<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.script.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function process_script($sample_html)
{
    return preg_replace("#<script.*?</script>#is", "", $sample_html);
}

?>