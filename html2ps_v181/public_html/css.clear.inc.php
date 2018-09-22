<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.clear.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('CLEAR_NONE', 0);
define('CLEAR_LEFT', 1);
define('CLEAR_RIGHT', 2);
define('CLEAR_BOTH', 3);

class CSSClear extends CSSProperty
{
    function CSSClear()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return CLEAR_NONE;
    }

    function parse($value)
    {
        if ($value === 'left') {
            return CLEAR_LEFT;
        };
        if ($value === 'right') {
            return CLEAR_RIGHT;
        };
        if ($value === 'both') {
            return CLEAR_BOTH;
        };
        return CLEAR_NONE;
    }

    function value2ps($value)
    {
        if ($value === CLEAR_LEFT) {
            return "/left";
        }
        if ($value === CLEAR_RIGHT) {
            return "/right";
        }
        if ($value === CLEAR_BOTH) {
            return "/both";
        }
        return "/none";
    }
}

register_css_property('clear', new CSSClear);

?>