<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/ps.utils.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function trim_ps_comments($data)
{
    $data = preg_replace("/(?<!\\\\)%.*/", "", $data);
    return preg_replace("/ +$/", "", $data);
}

function format_ps_color($color)
{
    return sprintf("%.3f %.3f %.3f", $color[0] / 255, $color[1] / 255, $color[2] / 255);
}

?>