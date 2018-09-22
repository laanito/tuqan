<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/box.null.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

class NullBox extends GenericInlineBox
{
    function get_min_width(&$context)
    {
        return 0;
    }

    function get_max_width(&$context)
    {
        return 0;
    }

    function get_height()
    {
        return 0;
    }

    function NullBox()
    {
        // No CSS rules should be applied to null box
        push_css_defaults();
        $this->GenericBox();
        pop_css_defaults();
    }

    function &create(&$root)
    {
        $box =& new NullBox;
        return $box;
    }

    function show(&$viewport)
    {
        return true;
    }

    function reflow(&$parent, &$context)
    {
        // Move current "box" to parent current coordinates. It is REQUIRED, 
        // as some other routines uses box coordinates.
        $this->put_left($parent->get_left());
        $this->put_top($parent->get_top());
    }

    function is_null()
    {
        return true;
    }

    function to_ps(&$psdata)
    {
        // Just do nothing
    }
}

?>