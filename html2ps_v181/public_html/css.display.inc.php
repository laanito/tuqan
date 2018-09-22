<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.display.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSDisplay extends CSSProperty
{
    function CSSDisplay()
    {
        $this->CSSProperty(false, false);
    }

    function get_parent()
    {
        if (isset($this->_stack[1])) {
            return $this->_stack[1][0];
        } else {
            return 'block';
        };
    }

    function default_value()
    {
        return "inline";
    }

    function parse($value)
    {
        return $value;
    }
}

register_css_property('display', new CSSDisplay);

function is_inline_element($display)
{
    return
        $display == "inline" ||
        $display == "inline-table" ||
        $display == "compact" ||
        $display == "run-in" ||
        $display == "-button" ||
        $display == "-checkbox" ||
        $display == "-iframe" ||
        $display == "-image" ||
        $display == "inline-block" ||
        $display == "-radio" ||
        $display == "-select";
}

?>