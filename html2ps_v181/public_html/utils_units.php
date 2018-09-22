<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/utils_units.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('UNIT_PT', 0);
define('UNIT_PX', 1);
define('UNIT_MM', 2);
define('UNIT_CM', 3);
define('UNIT_EM', 4);
define('UNIT_EX', 5);

class Value
{
    var $unit;
    var $number;

    function unit_from_string($value)
    {
        switch (substr($value, strlen($value) - 2, 2)) {
            case "pt":
                return UNIT_PT;
            case "px":
                return UNIT_PX;
            case "mm":
                return UNIT_MM;
            case "cm":
                return UNIT_CM;
            case "ex":
                return UNIT_EX;
            case "em":
                return UNIT_EM;
        }
    }
}

$g_pt_scale = 1;
$g_px_scale = 1;

function pt2pt($pt)
{
    global $g_pt_scale;
    return $pt * $g_pt_scale;
}

function px2pt($px)
{
    global $g_px_scale;
    return $px * $g_px_scale;
}

function mm2pt($mm)
{
    return $mm * 2.83464567;
}

function units2pt($value, $font_size = null)
{
    $units = substr($value, strlen($value) - 2, 2);
    switch ($units) {
        case "pt":
            return pt2pt((double)$value);
        case "px":
            return px2pt((double)$value);
        case "mm":
            return mm2pt((double)$value);
        case "cm":
            return mm2pt((double)$value * 10);
        // FIXME: check if it will work correcty in all situations (order of css rule application may vary).
        case "em":
            if (is_null($font_size)) {
                $fs = get_font_size();

//       $fs_parts = explode(" ", $fs);
//       if (count($fs_parts) == 2) {
//         return units2pt(((double)$value) * $fs_parts[0]*EM_KOEFF . $fs_parts[1]);
//       } else {
                return pt2pt(((double)$value) * $fs * EM_KOEFF);
//       };
            } else {
                return $font_size * (double)$value * EM_KOEFF;
            };
        case "ex":
            if (is_null($font_size)) {
                $fs = get_font_size();
//       $fs_parts = explode(" ", $fs);
//       if (count($fs_parts) == 2) {
//         return units2pt(((double)$value) * $fs_parts[0]*EX_KOEFF . $fs_parts[1]);
//       } else {
                return pt2pt(((double)$value) * $fs * EX_KOEFF);
//       };
            } else {
                return $font_size * (double)$value * EX_KOEFF;
            };
        default:
            return px2pt((double)$value);
    };
}

function ps_units($value)
{
    $units = substr($value, strlen($value) - 2, 2);
    switch ($units) {
        case "pt":
            return (double)$value . " pt ";
        case "px":
            return (double)$value . " px ";
        case "mm":
            return (double)$value . " mm ";
        case "cm":
            return (double)$value . " cm ";
        // FIXME: check if it will work correcty in all situations (order of css rule application may vary).
        case "em":
            $fs = get_font_size();
            $fs_parts = explode(" ", $fs);
            if (count($fs_parts) == 2) {
                return ((double)$value) * $fs_parts[0] * EM_KOEFF . " " . $fs_parts[1];
            } else {
                return ((double)$value) * $fs_parts[0] * EM_KOEFF . " pt ";
            };
        case "ex":
            $fs = get_font_size();
            $fs_parts = explode(" ", $fs);
            if (count($fs_parts) == 2) {
                return ((double)$value) * $fs_parts[0] . " " . $fs_parts[1];
            } else {
                return ((double)$value) * $fs_parts[0] . " pt ";
            };
        default:
            return (double)$value . " px ";
    };
}

?>