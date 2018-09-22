<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/css.pseudo.listcounter.inc.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class CSSPseudoListCounter extends CSSProperty
{
    function CSSPseudoListCounter()
    {
        $this->CSSProperty(true, false);
    }

    function default_value()
    {
        return 1;
    }
}

register_css_property('-list-counter', new CSSPseudoListCounter);

?>