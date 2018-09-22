<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.pseudo.localalign.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('LA_LEFT', 0);
define('LA_CENTER', 1);
define('LA_RIGHT', 2);

class CSSLocalAlign extends CSSProperty
{
    function CSSLocalAlign()
    {
        $this->CSSProperty(false, false);
    }

    function default_value()
    {
        return LA_LEFT;
    }

    function parse($value)
    {
        return $value;
    }

    function ps($writer)
    {
        switch ($this->get()) {
            case LA_LEFT:
                $writer->write("{text-align-left} 1 index put-local-align\n");
                break;
            case LA_CENTER:
                $writer->write("{text-align-center} 1 index put-local-align\n");
                break;
            case LA_RIGHT:
                $writer->write("{text-align-right} 1 index put-local-align\n");
                break;
        };
    }

    function pdf($writer)
    {
        return $this->get();
    }
}

register_css_property('-localalign', new CSSLocalAlign);

?>