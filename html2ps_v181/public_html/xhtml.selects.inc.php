<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.selects.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function process_option(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(option|/select|/option)",
        array(),
        "/option");
}

;

function process_select(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(option|/select)",
        array("option" => "process_option"),
        "/select");
}

;

function process_selects(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(select)",
        array("select" => "process_select"),
        "");
}

;

?>