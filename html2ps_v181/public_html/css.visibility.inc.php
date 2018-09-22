<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.visibility.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('VISIBILITY_VISIBLE', 0);
define('VISIBILITY_HIDDEN', 1);
define('VISIBILITY_COLLAPSE', 2); // TODO: currently treated is hidden

class CSSVisibility extends CSSProperty
{
    function CSSVisibility()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return VISIBILITY_VISIBLE;
    }

    function parse($value)
    {
        if ($value === 'visible') {
            return VISIBILITY_VISIBLE;
        };
        if ($value === 'hidden') {
            return VISIBILITY_HIDDEN;
        };
        if ($value === 'collapse') {
            return VISIBILITY_COLLAPSE;
        };
        return VISIBILITY_VISIBLE;
    }

    function value2ps($value)
    {
        if ($value === VISIBILITY_VISIBLE) {
            return "/visible";
        }
        if ($value === VISIBILITY_HIDDEN) {
            return "/hidden";
        }
        if ($value === VISIBILITY_COLLAPSE) {
            return "/collapse";
        }
        return "/visible";
    }
}

register_css_property('visibility', new CSSVisibility);

?>