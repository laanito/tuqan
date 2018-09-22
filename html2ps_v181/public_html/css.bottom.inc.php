<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.bottom.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSBottom extends CSSProperty
{
    function CSSBottom()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return null;
    }

    function parse($value)
    {
        return $value;
    }
}

register_css_property('bottom', new CSSBottom);

?>