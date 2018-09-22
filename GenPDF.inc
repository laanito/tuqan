<?php
//--------------------------------------------------------------------
// PHP GenPDF Class
//
// Copyright Jeff Redding, 2004, All Rights Reserved.
//
// This class creates a block/field entry model for creating PDF reports from various 
// data.
//
// Version: $Id: GenPDF.inc,v 1.1 2006-08-31 08:36:05 jmartinez Exp $
//
//--------------------------------------------------------------------
define('FPDF_FONTPATH', 'fpdf153/font/');
require('fpdf153/fpdf.php');

class PDF extends FPDF
{
    var $field_defs = array();
    var $font_defs = array();
    var $maxYoff = 0;
    var $blockPosX = 0;
    var $blockPosY = 0;
    var $blockHeight = 0;
    var $lineHeight = 5;
    var $aLogo;

    var $currFont = 'default';

    var $aTitulo;
    var $aSubtitulo;

    //var $maxWidth = 275;    // Landscape: 11in * 25mm 
    var $maxWidth = 195;    // Portrait: 8.5in * 25mm
    //var $maxHeight = 185;   // Kind of a kludgy number...
    var $maxHeight = 257;

    function setMaxWidth($width)
    {
        $this->maxWidth = $width;
    }

    function setMaxHeight($height)
    {
        $this->maxHeight = $height;
    }

    function setLogo($sFichero, $iPosx, $iPosy, $iAncho)
    {
        $this->aLogo['fichero'] = $sFichero;
        $this->aLogo['x'] = $iPosx;
        $this->aLogo['y'] = $iPosy;
        $this->aLogo['ancho'] = $iAncho;
    }

    function setTitulo($sTitulo, $iPosx, $iPosy, $iAncho)
    {
        $this->aTitulo['titulo'] = $sTitulo;
        $this->aTitulo['x'] = $iPosx;
        $this->aTitulo['y'] = $iPosy;
        $this->aTitulo['ancho'] = $iAncho;
    }

    function setSubtitulo($sTitulo, $iPosx, $iPosy, $iAncho)
    {
        $this->aSubtitulo['titulo'] = $sTitulo;
        $this->aSubtitulo['x'] = $iPosx;
        $this->aSubtitulo['y'] = $iPosy;
        $this->aSubtitulo['ancho'] = $iAncho;
    }

    function addField($name, $xoff, $yoff, $width)
    {
        $this->field_defs[$name] = array($xoff, $yoff, $width);
        if ($yoff > $this->blockHeight)
            $this->blockHeight = $yoff;
    }

    function addFont($name, $font_type, $font_weight, $font_size)
    {
        $this->font_defs[$name] = array($font_type, $font_weight, $font_size);
    }

    function ponFuente($fuente)
    {
        $this->_useFontDef($fuente);
    }

    function printField($text, $field_name = "", $font_name = "", $border = 0, $align = 'L', $fill = 0)
    {
        // Set offsets and width based on first field entry, or
        // given field entry.
        if ($field_name == "") {
            $field_xoff = $this->field_defs[0][0];
            $field_yoff = $this->field_defs[0][1];
            $field_width = $this->field_defs[0][2];
        } else {
            $field_xoff = $this->field_defs[$field_name][0];
            $field_yoff = $this->field_defs[$field_name][1];
            $field_width = $this->field_defs[$field_name][2];
        }

        // Set font information based on first font entry, or given
        // font entry.
        $this->_useFontDef($font_name);

        // Set the field position.            
        $this->SetXY($this->blockPosX + $field_xoff, $this->blockPosY + $field_yoff);


        if (($field_width + $field_xoff) <= $this->maxWidth) {
            // Shorten the field however much it needs
            if ($field_width == 0)
                $field_width = ($this->maxWidth - $this->GetX());
            $outText = $this->_cutField($text, $field_width);

            // Output the data
            $this->Cell($field_width, $this->lineHeight, $outText, $border, 0, $align, $fill);
        } else {
            $no_lineas = (int)(($field_width / $this->maxWidth) + 1);
            $this->Multicell(($this->maxWidth - $field_xoff), $this->lineHeight, $text, $border);
        }
        // Make sure to save the maximum y offset for this page.  This tells us 
        // how long the block is.  We use this to determine where to start the
        // next block.

        $t_yoff = $this->GetY();
        if ($this->maxYoff < $t_yoff)
            $this->maxYoff = $t_yoff;

    }

    function beginBlock($offset = 0, $title = "", $font_name = "")
    {
        $this->maxYoff += $offset;
        if (($this->maxYoff + $this->blockHeight) > $this->maxHeight) {
            $this->AddPage();
            $this->maxYoff = $this->GetY();
        }
        $this->blockPosY = $this->maxYoff;

        $this->SetXY($this->blockPosX, $this->blockPosY);
        $this->Ln();

        if ($title != "") {
            $this->_useFontDef($font_name);
            $this->SetFillColor(240, 240, 240);
            $this->Cell(0, $this->lineHeight, $title, 0, 0, 'L', 1);
            $this->Ln();
        }
        $this->blockPosY = $this->GetY();
        $this->maxYoff = $this->blockPosY;
    }

    function Header()
    {
        $fTime = date("Y-m-d G:i:s", time());
        $subtitle2 = "Generated on $fTime";

        $this->blockPosX = $this->GetX();

        if ($this->font_defs['header'][0] == "") {
            $this->_setFontDefs();
        }
        $font_type = $this->font_defs['header'][0];
        $font_weight = $this->font_defs['header'][1];
        $font_size = $this->font_defs['header'][2];

        $extra_width = 30;

        //Calculate width of title and position
        $this->SetFont($font_type, $font_weight, $font_size);
        $w = $this->GetStringWidth($this->title) + $extra_width;

        /*        $this->SetFont($font_type, $font_weight, $font_size-3);
                if(($this->GetStringWidth($this->subTitle)+ $extra_width) > $w)
                    $w = $this->GetStringWidth($this->subTitle)+ $extra_width;

                $this->SetFont($font_type, $font_weight, $font_size-6);
                if(($this->GetStringWidth($subtitle2)+ $extra_width) > $w)
                    $w = $this->GetStringWidth($subtitle2)+ $extra_width;*/

        //Colors of frame, background and text
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0, 0, 0);

        //Thickness of frame (1 mm)
        $this->SetLineWidth(1);

        //Ponemos el logo

        //Title
        if (is_array($this->aTitulo)) {
            $this->SetX($this->aTitulo['x']);
            $this->SetY($this->aTitulo['y']);
            $this->Cell($this->aTitulo['ancho'], $this->lineHeight + 7, $this->aTitulo['titulo'], 1, 0, 'C', 1);
        }
        if (is_array($this->aLogo)) {
            $this->Cell(0, $this->lineHeight + 7, '', 1, 1, 'L', 0);
            $this->Image($this->aLogo['fichero'], $this->aLogo['x'], $this->aLogo['y'] + 0.5, 0, $this->lineHeight + 6);
        }
        if (is_array($this->aSubtitulo)) {
            $this->Cell($this->aSubtitulo['ancho'], $this->lineHeight + 7, $this->aSubtitulo['titulo'], 1, 0, 'C', 1);
        }


        // Save the Y offset.  This is where the first block following the header will appear.*/

        $this->_resetFontDef();
        $this->Ln();
        $this->maxYoff = $this->GetY();
    }

    function Footer()
    {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);

        if ($this->font_defs['footer'][0] == "") {
            $this->_setFontDefs();
        }
        $font_type = $this->font_defs['footer'][0];
        $font_weight = $this->font_defs['footer'][1];
        $font_size = $this->font_defs['footer'][2];

        $this->SetFont($font_type, $font_weight, $font_size);
        $this->SetTextColor(128);

        //Page number
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        $this->_resetFontDef();
    }

    function _setFontDefs()
    {
        if ($this->font_defs['default'][0] == "")
            $this->font_defs['default'] = array('Arial', '', 10);

        if ($this->font_defs['header'][0] == "")
            $this->font_defs['header'] = array('Arial', 'B', 15);

        if ($this->font_defs['footer'][0] == "")
            $this->font_defs['footer'] = array('Arial', 'I', 8);
    }

    function _resetFontDef()
    {
        $this->_useFontDef($curr_font);
    }

    function _useFontDef($font_name)
    {
        // Set font information based on first font entry, or given
        // font entry.
        if ($font_name == "") {
            $this->curr_font = 'default';
            if ($this->font_defs['default'][0] == "") {
                $this->_setFontDefs();
            }
        } else {
            $this->curr_font = $font_name;
        }
        $font_type = $this->font_defs[$this->curr_font][0];
        $font_weight = $this->font_defs[$this->curr_font][1];
        $font_size = $this->font_defs[$this->curr_font][2];
        $this->SetFont($font_type, $font_weight, $font_size);
    }

    function _cutField($text, $max_width)
    {
        $tText = $text;
        $twidth = $this->GetStringWidth($tText);

        while ($twidth > $max_width) {
            $tText = substr($tText, 0, strlen($tText) - 1);
            $twidth = $this->GetStringWidth($tText);
        }
        return $tText;
    }


} // End class

