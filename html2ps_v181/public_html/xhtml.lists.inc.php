<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.lists.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function process_li(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(ul|ol|li|/li|/ul|/ol)",
        array("ul" => "process_ul",
            "ol" => "process_ol"),
        "/li");
}

;

function process_ol(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(li|/ol)",
        array("li" => "process_li"),
        "/ol");
}

;

function process_ul(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(li|/ul)",
        array("li" => "process_li"),
        "/ul");
}

;

function process_lists(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(ul|ol)",
        array("ul" => "process_ul",
            "ol" => "process_ol"),
        "");
}

;

?>