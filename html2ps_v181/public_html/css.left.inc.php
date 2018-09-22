<?php

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