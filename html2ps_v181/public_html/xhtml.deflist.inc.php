<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/xhtml.deflist.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function process_dd(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(dt|dd|dl|/dl|/dd)", array("dl" => "process_dl"), "/dd");
}

function process_dt(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(dt|dd|dl|/dl|/dd)", array("dl" => "process_dl"), "/dt");
}

function process_dl(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(dt|dd|/dl)",
        array("dt" => "process_dt",
            "dd" => "process_dd"),
        "/dl");
}

;

function process_deflists(&$sample_html, $offset)
{
    return autoclose_tag($sample_html, $offset, "(dl)",
        array("dl" => "process_dl"),
        "");
}

;

?>