<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.content.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSContent extends CSSProperty
{
    function CSSContent()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return "";
    }

    // CSS 2.1 p 12.2: 
    // Value: [ <string> | <uri> | <counter> | attr(X) | open-quote | close-quote | no-open-quote | no-close-quote ]+ | inherit
    //
    // TODO: process values other than <string>
    //
    function parse($value)
    {
        return css_remove_value_quotes($value);
    }

    function value2ps($value)
    {
    }
}

register_css_property('content', new CSSContent);

?>