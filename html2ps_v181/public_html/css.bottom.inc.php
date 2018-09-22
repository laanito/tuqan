<?php

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