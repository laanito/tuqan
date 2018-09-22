<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/utils_text.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function squeeze($string)
{
    return preg_replace("![ \n\t]+!", " ", trim($string));
}

?>