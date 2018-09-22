<?php
// $Header: /home/cvs/qnovaunidoCVS/html2ps_v181/public_html/output.fpdf.class.php,v 1.2 2006-10-24 10:29:17 gcerezo Exp $

include('fpdf/fpdf.php');
include('fpdf/font/makefont/makefont.php');


class MIPDF extends FPDF
{

    function Header()
    {
        if (strlen($this->login) > 0) {
            require_once '../../Manejador_Base_Datos.class.php';
            $oBaseDatos = new Manejador_Base_Datos($this->login, $this->pass, $this->db);
            $oBaseDatos->iniciar_Consulta('SELECT');
            $oBaseDatos->construir_Campos(array('documentos.codigo', 'documentos.nombre', 'documentos.revision',
                'usuarios.nombre||\' \'||usuarios.primer_apellido||\' \'||usuarios.segundo_apellido', 'documentos.tipo_documento',
                'to_char(documentos.fecha_aprobacion,\'DD/MM/YYYY\')', 'tipo_documento_idiomas.valor'
            ));
            $oBaseDatos->construir_Tablas(array('documentos', 'usuarios', 'tipo_documento', 'tipo_documento_idiomas'));

            $oBaseDatos->construir_Where(array('(documentos.id=\'' . $this->doc . '\')', 'documentos.revisado_por=usuarios.id', 'documentos.tipo_documento=tipo_documento.id', 'tipo_documento.id=tipo_documento_idiomas.tipodoc', 'tipo_documento_idiomas.idioma_id=' . $_SESSION['idiomaid']));
            $oBaseDatos->consulta();
            if ($aIterador = $oBaseDatos->coger_Fila()) {
                $this->SetFont('times', 'cp1252', 'B', 12);
                //Caja principal
                $this->SetXY(28.3464567, 22.677165);

                $this->Cell(538.58268, 85.03937, '', 1);
                //Caja Izquierda
                $this->SetXY(28.3464567, 22.677165);

                $this->Cell(141.73228, 68.031496, '', 1);
                $this->image('../../images/logofaffe.jpg', 45.354331, 39.685039, 113.38583, 34.015748);
                //1� Caja medio
                $this->SetXY(60 * 2.83464567, 8 * 2.83464567);

                $this->Cell(90 * 2.83464567, 6 * 2.83464567, $aIterador[6], 1, 0, 'C');
                //1� Caja derecha
                $this->SetXY(150 * 2.83464567, 8 * 2.83464567);
                $this->Cell(50 * 2.83464567, 6 * 2.83464567, $aIterador[0], 1);
                //2� Caja medio
                $this->SetXY(60 * 2.83464567, 14 * 2.83464567);
                $this->SetFont('times', 'cp1252', 'B', 8);
                $this->MultiCell(90 * 2.83464567, 6 * 2.83464567, $aIterador[1], 0);
                $this->SetFont('times', 'cp1252', 'B', 12);
                //2� Caja derecha
                $this->SetXY(150 * 2.83464567, 14 * 2.83464567);
                $this->Cell(50 * 2.83464567, 6 * 2.83464567, 'P�gina ' . $this->PageNo() . ' de {nb}', 1);
                //3� Caja medio
                $this->SetXY(60 * 2.83464567, 20 * 2.83464567);
                $this->MultiCell(90 * 2.83464567, 6 * 2.83464567, '', 0);
                //3� Caja derecha
                $this->SetXY(150 * 2.83464567, 20 * 2.83464567);
                $this->Cell(50 * 2.83464567, 6 * 2.83464567, 'Edici�n: ' . $aIterador[2], 1);
                //4� Caja medio
                $this->SetXY(60 * 2.83464567, 26 * 2.83464567);
                $this->MultiCell(90 * 2.83464567, 6 * 2.83464567, '', 'B');
                //4� Caja derecha
                $this->SetXY(150 * 2.83464567, 26 * 2.83464567);
                $this->Cell(50 * 2.83464567, 6 * 2.83464567, 'Fecha: ' . $aIterador[5], 1);
                //Caja abajo
                if ($aIterador[6] != "Ficha Medio Ambiente") {
                    $this->SetXY(10 * 2.83464567, 32 * 2.83464567);
                    $this->Cell(190 * 2.83464567, 6 * 2.83464567, 'Identificaci�n inform�tica: ' . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['PHP_SELF'], 1, 0, 'C');
                } else {
                    $oBaseDatos->iniciar_Consulta('SELECT');
                    $oBaseDatos->construir_Campos(array('tipo_ambito_aplicacion_idiomas.valor', 'tipo_area_aplicacion.nombre'
                    ));
                    $oBaseDatos->construir_Tablas(array('documentos', 'legislacion_aplicable', 'tipo_ambito_aplicacion', 'tipo_ambito_aplicacion_idiomas', 'tipo_area_aplicacion'));

                    $oBaseDatos->construir_Where(array('documentos.id=\'' . $this->doc . '\'', 'legislacion_aplicable.id_ficha=documentos.id',
                        'legislacion_aplicable.tipo_area=tipo_area_aplicacion.id', 'legislacion_aplicable.tipo_ambito=tipo_ambito_aplicacion.id',
                        'tipo_ambito_aplicacion.id=tipo_ambito_aplicacion_idiomas.tipoamb', 'tipo_ambito_aplicacion_idiomas.idioma_id=' . $_SESSION['idiomaid']
                    ));
                    $oBaseDatos->consulta();
                    if ($aIterador = $oBaseDatos->coger_Fila()) {
                        $this->SetFont('times', 'cp1252', 'B', 10);
                        $this->SetXY(10 * 2.83464567, 32 * 2.83464567);
                        $this->Cell(95 * 2.83464567, 6 * 2.83464567, 'Ambito de aplicación: ' . $aIterador[0], 1);
                        $this->SetXY(105 * 2.83464567, 32 * 2.83464567);
                        $this->Cell(95 * 2.83464567, 6 * 2.83464567, '�rea de incidencia: ' . $aIterador[1], 1);
                    }
                    $this->SetXY(10 * 2.83464567, 38 * 2.83464567);
                    $this->SetFont('times', 'cp1252', 'B', 12);
                    $this->Cell(190 * 2.83464567, 6 * 2.83464567, 'Identificaci�n inform�tica: ' . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['PHP_SELF'], 1, 0, 'C');
                }

            }
        }
    }

    function Footer()
    {
        //
        $this->SetFont('times', 'cp1252', 'B', 8);
        $this->setXY(10 * 2.83464567, -15 * 2.83464567);
        $this->MultiCell(190 * 2.83464567, 4 * 2.83464567, 'Este Documento es propiedad de la Fundaci�n Andaluza Fondo de Formación y Empleo y está prohibida su reproducci�n sin autorizaci�n por escrito.', 0);
        $this->setXY(10 * 2.83464567, -11 * 2.83464567);
        $this->MultiCell(190 * 2.83464567, 4 * 2.83464567, '', 0);
    }
}

class OutputDriverFPDF extends OutputDriverGenericPDF
{
    var $pdf;

    var $locallinks;

    var $cx;
    var $cy;
    var $db;
    var $login;
    var $pass;
    var $doc;

    function add_link($x, $y, $w, $h, $target)
    {
        $y = $y - $this->offset;
        $this->_coords2pdf($x, $y);
        $this->pdf->add_link_external($x, $y, $w, $h, $target);
    }

    function add_local_link($left, $top, $width, $height, $anchor)
    {
        if (!isset($this->locallinks[$anchor->name])) {
            $x = 0;
            $y = $anchor->y;
            $this->_coords2pdf($x, $y);

            $this->locallinks[$anchor->name] = $this->pdf->AddLink();
            $this->pdf->SetLink($this->locallinks[$anchor->name],
                $y - 20,
                $anchor->page);
        };

        $x = $left;
        $y = $top - $this->offset;
        $this->_coords2pdf($x, $y);

        $this->pdf->add_link_internal($x,
            $y,
            $width,
            $height,
            $this->locallinks[$anchor->name]);
    }

    // UNfortunately, FPDF do not provide any coordinate-space transformation routines
    // so we need to reverse the Y-axis manually
    function _coords2pdf(&$x, &$y)
    {
        $y = mm2pt($this->media->height()) - $y;
    }

    function decoration($underline, $overline, $strikeout)
    {
        // underline
        $this->pdf->SetDecoration($underline, $overline, $strikeout);
    }

    function circle($x, $y, $r)
    {
        $this->pdf->circle($x, $y, $r);
    }

    function clip()
    {
        $this->pdf->Clip();
    }

    function close()
    {
        $this->pdf->Output($this->get_filename());
    }

    function closepath()
    {
        $this->pdf->closepath();
    }

    function dash($x, $y)
    {
        $this->pdf->SetDash(ceil($x), ceil($y));
    }

    function get_bottom()
    {
        return $this->bottom + $this->offset;
    }

    // Converts common encoding names to their PDFLIB equivalents
    // (for example, PDFLIB does not understand iso-8859-1 encoding name,
    // but have its equivalent names winansi..)
    //
    function encoding($encoding)
    {
        $encoding = trim(strtolower($encoding));

        $translations = array(
            'windows-1250' => 'cp1250',
            'windows-1251' => 'cp1251',
            'windows-1252' => 'cp1252'
        );

        if (isset($translations[$encoding])) {
            return $translations[$encoding];
        };
        return $encoding;
    }

    function field_text($x, $y, $w, $h, $value)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->add_field_text($x, $y, $w, $h, $value);
    }

    function field_pushbutton($x, $y, $w, $h, $actionURL)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->add_field_pushbutton($x, $y, $w, $h, $actionURL);
    }

    function field_checkbox($x, $y, $w, $h)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->add_field_checkbox($x, $y, $w, $h);
    }

    function field_radio($x, $y, $w, $h, $groupname)
    {
        static $generated_group_index = 0;
        if (is_null($groupname)) {
            $generated_group_index++;
            $groupname = "__generated_group_" . $generated_group_index;
        };

        $this->_coords2pdf($x, $y);
        $this->pdf->add_field_radio($x, $y, $w, $h, $groupname);
    }

    function fill()
    {
        $this->pdf->Fill();
    }

    function findfont($name, $encoding)
    {
        // Todo: encodings handling
        return $name;
    }

    function font_ascender($name, $encoding)
    {
        return $this->pdf->GetFontAscender($name, $encoding);
    }

    function font_descender($name, $encoding)
    {
        return $this->pdf->GetFontDescender($name, $encoding);
    }

    function image($image, $x, $y, $scale)
    {
        $tmpname = tempnam("/tmp", "IMG");
        imagepng($image, $tmpname);

        $this->_coords2pdf($x, $y);
        $this->pdf->Image($tmpname,
            $x,
            $y - imagesy($image) * $scale,
            imagesx($image) * $scale,
            imagesy($image) * $scale,
            "png");
        unlink($tmpname);
    }

    function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale)
    {
        $tmpname = tempnam("/tmp", "IMG");
        imagepng($image, $tmpname);

        // Fill part to the right
        $cx = $x;
        while ($cx < $right) {
            $tx = $cx;
            $ty = $y + units2pt(imagesy($image));
            $this->_coords2pdf($tx, $ty);
            $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
            $cx += $width;
        };

        // Fill part to the left
        $cx = $x;
        while ($cx + $width >= $x - $ox) {
            $tx = $cx - $width;
            $ty = $y + units2pt(imagesy($image));
            $this->_coords2pdf($tx, $ty);
            $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
            $cx -= $width;
        };

        unlink($tmpname);
    }

    function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale)
    {
        $tmpname = tempnam("/tmp", "IMG");
        imagepng($image, $tmpname);

        // Fill bottom-right quadrant
        $cy = $y;
        while ($cy + $height > $bottom) {
            $cx = $x;
            while ($cx < $right) {
                $tx = $cx;
                $ty = $cy + $height;
                $this->_coords2pdf($tx, $ty);

                $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
                $cx += $width;
            };
            $cy -= $height;
        }

        // Fill bottom-left quadrant
        $cy = $y;
        while ($cy + $height > $bottom) {
            $cx = $x;
            while ($cx + $width > $x - $ox) {
                $tx = $cx;
                $ty = $cy;
                $this->_coords2pdf($tx, $ty);
                $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
                $cx -= $width;
            };
            $cy -= $height;
        }

        // Fill top-right quadrant
        $cy = $y;
        while ($cy < $y + $oy) {
            $cx = $x;
            while ($cx < $right) {
                $tx = $cx;
                $ty = $cy;
                $this->_coords2pdf($tx, $ty);
                $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
                $cx += $width;
            };
            $cy += $height;
        }

        // Fill top-left quadrant
        $cy = $y;
        while ($cy < $y + $oy) {
            $cx = $x;
            while ($cx + $width > $x - $ox) {
                $tx = $cx;
                $ty = $cy;
                $this->_coords2pdf($tx, $ty);
                $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
                $cx -= $width;
            };
            $cy += $height;
        }

        unlink($tmpname);
    }


    function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale)
    {
        $tmpname = tempnam("/tmp", "IMG");
        imagepng($image, $tmpname);

        // Fill part to the bottom
        $cy = $y;
        while ($cy + $height > $bottom) {
            $tx = $x;
            $ty = $cy + units2pt(imagesy($image));
            $this->_coords2pdf($tx, $ty);
            $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
            $cy -= $height;
        };

        // Fill part to the top
        $cy = $y;
        while ($cy - $height < $y + $oy) {
            $tx = $x;
            $ty = $cy + units2pt(imagesy($image));
            $this->_coords2pdf($tx, $ty);
            $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
            $cy += $height;
        };

        unlink($tmpname);
    }

    function image_scaled($image, $x, $y, $scale_x, $scale_y)
    {
        $tmpname = tempnam("/tmp", "IMG");
        imagepng($image, $tmpname);

        $this->_coords2pdf($x, $y);
        $this->pdf->Image($tmpname, $x, $y - imagesy($image) * $scale_y, imagesx($image) * $scale_x, imagesy($image) * $scale_y, "png");
        unlink($tmpname);
    }

    function lineto($x, $y)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->lineto($x, $y);
    }

    function moveto($x, $y)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->moveto($x, $y);
    }

    function next_page()
    {
        $this->current_page++;
        $this->pdf->AddPage();
        // $this->pdf->InFooter=true;
        $this->pdf->Footer();
        // $this->pdf->InFooter=false;
        $this->pdf->Header();
        // Calculate coordinate of the next page bottom edge
        $this->offset -= $this->height - $this->offset_delta;

        // Reset the "correction" offset to it normal value
        // Note: "correction" offset is an offset value required to avoid page breaking
        // in the middle of text boxes
        $this->offset_delta = 0;

        $this->pdf->Translate(0, -$this->offset);
    }

    function OutputDriverFPDF()
    {
        $this->OutputDriverGenericPDF();
    }

    function reset(&$media)
    {
        OutputDriverGenericPDF::reset($media);

        $this->pdf = new MIPDF("P", "pt", array(mm2pt($media->width()), mm2pt($media->height())));
        $this->pdf->login = $this->login;
        $this->pdf->pass = $this->pass;
        $this->pdf->db = $this->db;
        $this->pdf->doc = $this->doc;
        $this->pdf->AliasNbPages();
        $this->pdf->SetCompression(0);

        $this->pdf->AddPage();

        $this->cx = 0;
        $this->cy = 0;

        $this->locallinks = array();
    }

    function restore()
    {
        $this->pdf->Restore();
    }

    function save()
    {
        $this->pdf->Save();
    }

    function setfont($name, $encoding, $size)
    {
        $this->pdf->SetFont($this->findfont($name, $encoding), $encoding, '', $size);

        return true;
    }

    function setlinewidth($x)
    {
        $this->pdf->SetLineWidth($x);
    }

    // PDFLIB wrapper functions
    function setrgbcolor($r, $g, $b)
    {
        $this->pdf->SetDrawColor($r * 255, $g * 255, $b * 255);
        $this->pdf->SetFillColor($r * 255, $g * 255, $b * 255);
        $this->pdf->SetTextColor($r * 255, $g * 255, $b * 255);
    }

    function show_xy($text, $x, $y)
    {
        $this->_coords2pdf($x, $y);
        $this->pdf->Text($x, $y, $text);
    }

    function stroke()
    {
        $this->pdf->stroke();
    }

    function stringwidth($string, $name, $encoding, $size)
    {
        $this->setfont($name, $encoding, $size);
        return $this->pdf->GetStringWidth($string);
    }
}

?>