<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/box.img.php,v 1.1 2006-08-31 08:36:05 jmartinez Exp $

define('SCALE_NONE', 0);
define('SCALE_WIDTH', 1);
define('SCALE_HEIGHT', 2);

// class GenericImgBox extends GenericInlineBox {
class GenericImgBox extends TextBox
{
    function get_min_width(&$context)
    {
        return $this->get_full_width();
    }

    function get_max_width(&$context)
    {
        return $this->get_full_width();
    }

    function is_null()
    {
        return false;
    }

    function pre_reflow_images()
    {
        switch ($this->scale) {
            case SCALE_WIDTH:
                // Only 'width' attribute given
                $size =
                    $this->src_width / $this->src_height *
                    $this->get_width();

                $this->put_height($size);

                // Update baseline according to constrained image height
                $this->default_baseline = $this->get_height();
                break;
            case SCALE_HEIGHT:
                // Only 'height' attribute given
                $size =
                    $this->src_height / $this->src_width *
                    $this->get_height();

                $this->put_width($size);
                $this->put_width_constraint(new WCConstant($size));

                $this->default_baseline = $this->get_height();
                break;
        };
    }

    function reflow(&$parent, &$context)
    {
        $this->pre_reflow_images();

        GenericBox::reflow($parent, $context);

        // Check if we need a line break here
        $this->maybe_line_break($parent, $context);

        // set default baseline
        $this->baseline = $this->default_baseline;

        // append to parent line box
        $parent->append_line($this);

        // Move box to the parent current point
        $this->guess_corner($parent);

        // Move parent's X coordinate
        $parent->_current_x += $this->get_full_width();

        // Extend parent height
        $parent->extend_height($this->get_bottom_margin());
    }

    function reflow_text(&$viewport)
    {
        // In XHTML images are treated as a common inline elements; they are affected by line-height and font-size
        global $g_config;
        if ($g_config['mode'] == 'xhtml') {
            $width = $this->width;
            $height = $this->height;
            $default_baseline = $this->default_baseline;

            if (is_null(TextBox::reflow_text($viewport))) {
                return null;
            };

            $this->height = $height;
            $this->width = $width;
            $this->default_baseline = $default_baseline;
        } else {
            $this->ascender = $this->get_height();
            $this->descender = 0;
        };

        return true;
    }

    // Image boxes are regular inline boxes; whitespaces after images should be rendered
    // 
    function reflow_whitespace(&$linebox_started, &$previous_whitespace)
    {
        $linebox_started = true;
        $previous_whitespace = false;
        return;
    }

    function scale2ps($scale)
    {
        switch ($scale) {
            case SCALE_NONE:
                return "/none";
            case SCALE_WIDTH:
                return "/width";
            case SCALE_HEIGHT:
                return "/height";
        }
    }

    function show_fixed(&$driver)
    {
        return $this->show($driver);
    }
}

class BrokenImgBox extends GenericImgBox
{
    var $alt;

    function BrokenImgBox(&$node)
    {
        $this->scale = SCALE_NONE;
        $this->src_encoding = DEFAULT_ENCODING;

        // Call parent constructor
        $this->GenericBox();

        if ($node->has_attribute('width')) {
            $this->put_width(px2pt($node->get_attribute('width')));
        } else {
            $this->put_width(px2pt(BROKEN_IMAGE_DEFAULT_SIZE_PX));
        };

        if ($node->has_attribute('height')) {
            $this->put_height(px2pt($node->get_attribute('height')));
        } else {
            $this->put_height(BROKEN_IMAGE_DEFAULT_SIZE_PX);
        };

        $this->alt = $node->get_attribute('alt');

        $this->default_baseline = $this->get_height();

        $this->src_height = $this->get_height();
        $this->src_width = $this->get_width();
    }

    function show(&$viewport)
    {
        $viewport->save();

        // draw generic box
        GenericBox::show($viewport);

        $viewport->setlinewidth(0.1);
        $viewport->moveto($this->get_left(), $this->get_top());
        $viewport->lineto($this->get_right(), $this->get_top());
        $viewport->lineto($this->get_right(), $this->get_bottom());
        $viewport->lineto($this->get_left(), $this->get_bottom());
        $viewport->closepath();
        $viewport->stroke();

        $viewport->moveto($this->get_left(), $this->get_top());
        $viewport->lineto($this->get_right(), $this->get_top());
        $viewport->lineto($this->get_right(), $this->get_bottom());
        $viewport->lineto($this->get_left(), $this->get_bottom());
        $viewport->closepath();
        $viewport->clip();

        // Output text with the selected font
        $size = pt2pt(BROKEN_IMAGE_ALT_SIZE_PT);

        $status = $viewport->setfont("Times-Roman", $viewport->encoding("iso-8859-1"), $size);
        if (is_null($status)) {
            return null;
        };

        $viewport->show_xy($this->alt,
            $this->get_left() + $this->width / 2 - $viewport->stringwidth($this->alt,
                "Times-Roman",
                $viewport->encoding("iso-8859-1"),
                $size) / 2,
            $this->get_top() - $this->height / 2 - $size / 2);

        $viewport->restore();

        return true;
    }
}

class ImgBox extends GenericImgBox
{
    function &create($root)
    {
        // Open image referenced by HTML tag
        // Some crasy HTML writers add leading and trailing spaces to SRC attribute value - we need to remove them
        //
        $src = trim($root->get_attribute("src"));

        // FIXME: I'm using globals here. Change it to the class variable
        global $g_baseurl;
        $url = guess_url($src, $g_baseurl);

        $src_img = Image::get($url);

        if (is_null($src_img)) {
            // image could not be opened, use ALT attribute
            // TODO: show_broken_image
            $box =& new BrokenImgBox($root);
            return $box;
        } else {
            $box =& new ImgBox($src_img);

            // Proportional scaling 
            if ($root->has_attribute('width') && !$root->has_attribute('height')) {
                $box->scale = SCALE_WIDTH;

                // Only 'width' attribute given
                $size =
                    $box->src_width / $box->src_height *
                    $box->get_width();

                $box->put_height($size);

                // Update baseline according to constrained image height
                $box->default_baseline = $box->get_height();

            } elseif (!$root->has_attribute('width') && $root->has_attribute('height')) {
                $box->scale = SCALE_HEIGHT;

                // Only 'height' attribute given
                $size =
                    $box->src_height / $box->src_width *
                    $box->get_height();

                $box->put_width($size);
                $box->put_width_constraint(new WCConstant($size));

                $box->default_baseline = $box->get_height();
            };

            return $box;
        }
    }

    function ImgBox($img)
    {
        $this->src_encoding = DEFAULT_ENCODING;
        $this->scale = SCALE_NONE;

        // Call parent constructor
        $this->GenericBox();

        // Store image for further processing
        $this->image = $img;

        $this->put_width(px2pt(imagesx($img)));

        $hc = $this->get_height_constraint();
        if ($hc->is_null()) {
            $this->put_height(px2pt(imagesy($img)));
        };
        $this->default_baseline = $this->get_height();

        $this->src_height = imagesx($img);
        $this->src_width = imagesy($img);
    }

    function show(&$viewport)
    {
        // draw generic box
        GenericBox::show($viewport);

        // Check if "designer" set the height or width of this image to zero; in this there will be no reason 
        // in drawing the image at all
        //
        if ($this->get_width() < EPSILON ||
            $this->get_height() < EPSILON) {
            return true;
        };

        $viewport->image_scaled($this->image,
            $this->get_left(), $this->get_bottom(),
            $this->get_width() / imagesx($this->image), $this->get_height() / imagesy($this->image));

        return true;
    }
}

?>