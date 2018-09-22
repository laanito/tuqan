<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.left.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSLeft extends CSSProperty
{
    function CSSLeft()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return null;
    }

    function parse($value)
    {
        return units2pt($value);
    }

    function value2ps($value)
    {
        return $value;
    }
}

register_css_property('left', new CSSLeft);

?>