<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.border.collapse.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('BORDER_COLLAPSE', 1);
define('BORDER_SEPARATE', 2);

class CSSBorderCollapse extends CSSProperty
{
    function CSSBorderCollapse()
    {
        $this->CSSProperty(true, true);
    }

    function default_value()
    {
        return BORDER_SEPARATE;
    }

    function parse($value)
    {
        if ($value === 'collapse') {
            return BORDER_COLLAPSE;
        };
        if ($value === 'separate') {
            return BORDER_SEPARATE;
        };
        return $this->default_value();
    }

    function value2ps($value)
    {
        // Do nothing
    }
}

register_css_property('border-collapse', new CSSBorderCollapse);

?>