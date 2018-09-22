<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.valign.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

function is_default_valign($align)
{
    return $align == default_valign();
}

function default_valign()
{
    return "{valign-top}";
}

function parse_valign($value)
{
    switch (strtolower(trim($value))) {
        case "top":
            return "{valign-top}";
        case "middle":
        case "center":
            return "{valign-middle}";
        case "bottom":
            return "{valign-bottom}";
        case "baseline":
            return "{valign-baseline}";
    };

    return "{valign-top}";
}

function get_valign()
{
    global $g_valign;
    return $g_valign[0];
}

function push_valign($align)
{
    die("PUSH VALIFN");
    global $g_valign;
    array_unshift($g_valign, $align);
}

function pop_valign()
{
    global $g_valign;
    array_shift($g_valign);
}

function ps_valign()
{
    return get_valign() . " 1 index put-valign\n";
}

?>