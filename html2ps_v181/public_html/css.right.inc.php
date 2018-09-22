<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.right.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSRight extends CSSProperty
{
    function CSSRight()
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

    function ps($writer)
    {
        $writer->write(
            ps_units($this->get()) . " neg 1 index put-right\n" .
            "dup get-position-dict /Right " . ps_units($this->get()) . " put\n"
        );
    }

    function pdf()
    {
        return $this->get() === null ? null : units2pt($this->get());
    }
}

register_css_property('right', new CSSRight);

?>